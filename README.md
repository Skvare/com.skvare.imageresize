# com.skvare.imageresize

This extension adds image resize capability to CiviCRM for Contact and Custom Field images. The extension handles resizing of all types of images and will put the images into the cache directory
Image style has to pass into url along with other url parameter.

Functionality can be integrate in Drupal Views, WordPress Plugin to show civicrm contact, custom resized images with good quality to load we page faster as compared to original images.


## Requirements

* PHP v5.4+
* CiviCRM 5.7

## Installation (Web UI)

* Move the downloaded extension to your extensions folder.
* Goto civicrm/admin/extensions -- install the extension

## Installation (CLI, Git)

Sysadmins and developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone https://github.com/skvare/com.skvare.imageresize.git
cv en imageresize
```

## Usage

Admin can create custom style by visiting /civicrm/admin/options/image_styles
e.g
```bash
 /civicrm/contact/imagefile?photo=Logo_bfe0dba6f9eee24d0430016719ad219a.png&amp;image_styles=thumbnail
 /civicrm/file?reset=1&filename=Logo_bfe0dba6f9eee24d0430016719ad219a.png&mime-type=image/png&image_styles=thumbnail
```