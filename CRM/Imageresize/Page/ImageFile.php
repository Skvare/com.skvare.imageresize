<?php

class CRM_Imageresize_Page_ImageFile extends CRM_Contact_Page_ImageFile {
  /**
   * @var int Time to live (seconds).
   *
   * 12 hours: 12 * 60 * 60 = 43200
   */
  private $imageTtl = 43200;

  /**
   * Run page.
   *
   * @throws \Exception
   */
  public function run() {
    $photo  = CRM_Utils_Request::retrieve('photo', 'String', CRM_Core_DAO::$_nullObject);
    if (!preg_match('/^[^\/]+\.(jpg|jpeg|png|gif)$/i', $photo)) {
      throw new CRM_Core_Exception(ts('Malformed photo name'));
    }

    // FIXME Optimize performance of image_url query
    $sql = "SELECT id FROM civicrm_contact WHERE image_url like %1;";
    $params = [
      1 => ["%" . $photo, 'String'],
    ];
    $dao = CRM_Core_DAO::executeQuery($sql, $params);
    $cid = NULL;
    while ($dao->fetch()) {
      $cid = $dao->id;
    }
    if ($cid) {
      $config = CRM_Core_Config::singleton();
      $fileName = pathinfo($photo, PATHINFO_FILENAME);
      $fileExtension = pathinfo($photo, PATHINFO_EXTENSION);
      $path = $config->customFileUploadDir . $photo;
      $path = CRM_Imageresize_File::getNewPath($path);
      $this->download(
        $path,
        'image/' . (strtolower($fileExtension) == 'jpg' ? 'jpeg' : $fileExtension),
        $this->imageTtl
      );
      CRM_Utils_System::civiExit();
    }
    else {
      throw new CRM_Core_Exception(ts('Photo does not exist'));
    }
  }

}

