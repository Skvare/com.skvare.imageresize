<?php

class CRM_Imageresize_File {
  /**
   * Resize an image.
   *
   * @param string $sourceFile
   *   Filesystem path to existing image on server
   * @param int $targetWidth
   *   New width desired, in pixels
   * @param int $targetHeight
   *   New height desired, in pixels
   * @param string $suffix = ""
   *   If supplied, the image will be renamed to include this suffix. For
   *   example if the original file name is "foo.png" and $suffix = "_bar",
   *   then the final file name will be "foo_bar.png".
   * @param bool $preserveAspect = TRUE
   *   When TRUE $width and $height will be used as a bounding box, outside of
   *   which the resized image will not extend.
   *   When FALSE, the image will be resized exactly to $width and $height, even
   *   if it means stretching it.
   * @param string $subDir = null
   *   create sub dir and copy resize Image to it.
   * @param bool $returnPath = FALSE
   *   When TRUE return new file name path otherwise return url
   * @param string $targetFile = ""
   *   Specify custom target directory
   *
   * @return string
   *   Path to image
   * @throws \CRM_Core_Exception
   *   Under the following conditions
   *   - When GD is not available.
   *   - When the source file is not an image.
   */
  public static function resizeImage($sourceFile, $targetWidth, $targetHeight, $suffix = "", $preserveAspect = TRUE, $subDir = NULL, $returnPath = FALSE, $targetFile = '') {
    if (!file_exists($sourceFile) && $returnPath) {
      return $sourceFile;
    }
    if (!empty($targetFile)) {
      $pathParts = pathinfo($targetFile);
      $targetDirectory = $pathParts['dirname'] . DIRECTORY_SEPARATOR;
    } else {
      // If empty get it from source file.
      // figure out the new filename
      $pathParts = pathinfo($sourceFile);
      $targetDirectory = $pathParts['dirname'] . DIRECTORY_SEPARATOR;
    }
    if (!empty($subDir)) {
      $targetDirectory = $targetDirectory . $subDir . DIRECTORY_SEPARATOR;
      CRM_Utils_File::createDir($targetDirectory);
    }
    $targetFile = $targetDirectory
      . $pathParts['filename'] . $suffix . "." . $pathParts['extension'];
    // return file path if resize file already exist
    if (!empty($subDir) && file_exists($targetFile) && $returnPath) {
      return $targetFile;
    }
    // Check if GD is installed
    $gdSupport = FALSE;
    if (php_sapi_name() == "cli" && extension_loaded('gd')) {
      $gdSupport = TRUE;
    }
    else {
      $gdSupport = CRM_Utils_System::getModuleSetting('gd', 'GD Support');
    }
    if (!$gdSupport) {
      throw new CRM_Core_Exception(ts('Unable to resize image because the GD image library is not currently compiled in your PHP installation.'));
    }

    $sourceMime = mime_content_type($sourceFile);
    if ($sourceMime == 'image/gif') {
      $sourceData = imagecreatefromgif($sourceFile);
    }
    elseif ($sourceMime == 'image/png') {
      $sourceData = imagecreatefrompng($sourceFile);
    }
    elseif ($sourceMime == 'image/jpeg') {
      $sourceData = imagecreatefromjpeg($sourceFile);
    }
    else {
      throw new CRM_Core_Exception(ts('Unable to resize image because the file supplied was not an image.'));
    }

    // get image about original image
    $sourceInfo = getimagesize($sourceFile);
    $sourceWidth = $sourceInfo[0];
    $sourceHeight = $sourceInfo[1];

    // Adjust target width/height if preserving aspect ratio
    if ($preserveAspect) {
      $sourceAspect = $sourceWidth / $sourceHeight;
      $targetAspect = $targetWidth / $targetHeight;
      if ($sourceAspect > $targetAspect) {
        $targetHeight = $targetWidth / $sourceAspect;
      }
      if ($sourceAspect < $targetAspect) {
        $targetWidth = $targetHeight * $sourceAspect;
      }
    }

    $targetData = imagecreatetruecolor($targetWidth, $targetHeight);
    /* Check if this image is PNG or GIF, then set if Transparent*/
    if ($sourceMime == 'image/gif') {
      $transparent = imagecolortransparent($sourceData);
      if ($transparent >= 0) {
        // Find out the number of colors in the image palette. It will be 0 for truecolor images.
        $paletteSize = imagecolorstotal($sourceData);
        if ($paletteSize == 0 || $transparent < $paletteSize) {
          // Set the transparent color in the new resource, either if it is a
          // truecolor image or if the transparent color is part of the palette.
          // Since the index of the transparency color is a property of the
          // image rather than of the palette, it is possible that an image
          // could be created with this index set outside the palette size (see
          // http://stackoverflow.com/a/3898007).
          $transparentColor = imagecolorsforindex($sourceData, $transparent);
          $transparent = imagecolorallocate($targetData, $transparentColor['red'], $transparentColor['green'], $transparentColor['blue']);
          // Flood with our new transparent color.
          imagefill($targetData, 0, 0, $transparent);
          imagecolortransparent($targetData, $transparent);
        }
        else {
          imagefill($targetData, 0, 0, imagecolorallocate($targetData, 255, 255, 255));
        }
      }
    }
    elseif ($sourceMime == 'image/png') {
      // Set alphablending to off
      imagealphablending($targetData, FALSE);
      // alpha values
      $transparency = imagecolorallocatealpha($targetData, 0, 0, 0, 127);
      imagefill($targetData, 0, 0, $transparency);
      // Set alphablending to on
      imagealphablending($targetData, TRUE);
      imagesavealpha($targetData, TRUE);
    }
    elseif ($sourceMime == 'image/jpeg') {
      imagefill($targetData, 0, 0, imagecolorallocate($targetData, 255, 255, 255));
    }
    // resize
    imagecopyresampled($targetData, $sourceData,
      0, 0, 0, 0,
      $targetWidth, $targetHeight, $sourceWidth, $sourceHeight);

    // save the resized image
    $fp = fopen($targetFile, 'w+');
    ob_start();
    switch ($sourceInfo[2]) {
      case 1:
        imagegif($targetData);
        break;
      case 2:
        imagejpeg($targetData);
        break;
      case 3:
        imagepng($targetData);
        break;
      default:
        imagejpeg($targetData);
        break;
    }
    $image_buffer = ob_get_contents();
    ob_end_clean();
    imagedestroy($targetData);
    fwrite($fp, $image_buffer);
    rewind($fp);
    fclose($fp);

    if ($returnPath) {
      return $targetFile;
    }
    // return the URL to link to
    $config = CRM_Core_Config::singleton();
    return $config->imageUploadURL . basename($targetFile);
  }

  public static function getNewPath($path, $targetFile = '') {
    // Functionality to resize image by passing image style in url along with photo name,
    // this will create imange with width and height as suffix to image name
    // e.g img_112112133313.png will be img_112112133313_w150_h150.png
    $imageStyle  = CRM_Utils_Request::retrieve('image_styles', 'String', CRM_Core_DAO::$_nullObject);
    if ($imageStyle) {
      $imageStyleValue = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionValue', $imageStyle, 'value', 'name');
      if ($imageStyleValue) {
        $imageStyleValues = explode('x', $imageStyleValue);
        $width = $imageStyleValues[0];
        $height = $imageStyleValues[1];
        if ($width && $height) {
          $suffix = '_w' . $width . '_h' . $height;
          try {
            $path = CRM_Imageresize_File::resizeImage($path, $width, $height, $suffix, TRUE, 'cache', TRUE, $targetFile);
          } catch (CRM_Core_Exception $e) {
            CRM_Core_Session::singleton()->setStatus($e->getMessage());
          }
        }
      }
    }
    return $path;
  }
}
