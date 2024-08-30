<?php

require_once 'imageresize.civix.php';
use CRM_Imageresize_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function imageresize_civicrm_config(&$config) {
  _imageresize_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function imageresize_civicrm_install() {
  _imageresize_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function imageresize_civicrm_enable() {
  _imageresize_civix_civicrm_enable();
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *

 // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function imageresize_civicrm_navigationMenu(&$menu) {
  _imageresize_civix_insert_navigation_menu($menu, 'Mailings', array(
    'label' => E::ts('New subliminal message'),
    'name' => 'mailing_subliminal_message',
    'url' => 'civicrm/mailing/subliminal',
    'permission' => 'access CiviMail',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _imageresize_civix_navigationMenu($menu);
} // */


/**
 * Implements hook_civicrm_alterMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterMenu
 */
function imageresize_civicrm_alterMenu(&$items) {
  $items['civicrm/contact/imagefile']['page_callback'] = 'CRM_Imageresize_Page_ImageFile';
  $items['civicrm/file']['page_callback'] = 'CRM_Imageresize_Page_File';
}

/**
 * Implements hook_civicrm_pageRun().
 *
 */
function imageresize_civicrm_pageRun(&$page) {
  $class_name = get_class($page);
  if ($class_name == 'CRM_Contact_Page_View_Summary') {
    $smarty = CRM_Core_Smarty::singleton();
    $imageURL = CRM_Utils_Array::value('imageURL', $smarty->_tpl_vars);
    if (! $imageURL) {
      return;
    }
    $matches = array();
    if (preg_match('/filename\=([^&]*)/', $imageURL, $matches)) {
      $path = CRM_Core_Config::singleton()->customFileUploadDir . $matches[1];
      $matches = array();
      preg_match( '/src="([^"]*)"/i', $imageURL, $matches) ;
      if (!empty($matches)) {
        $url = $matches['1'];
        $urlImg = $matches['1']. '&image_styles=summary';
      }

      list($imageWidth, $imageHeight) = getimagesize($path);
      list($imageThumbWidth, $imageThumbHeight) = CRM_Contact_BAO_Contact::getThumbSize($imageWidth, $imageHeight);
      $url = "<a href=\"$url\" class='crm-image-popup'>
          <img src=\"$urlImg\" width=$imageThumbWidth height=$imageThumbHeight/>
          </a>";
      $smarty->assign("imageURL", $url);
    }

  }
}
