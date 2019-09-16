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
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function imageresize_civicrm_xmlMenu(&$files) {
  _imageresize_civix_civicrm_xmlMenu($files);
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
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function imageresize_civicrm_postInstall() {
  _imageresize_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function imageresize_civicrm_uninstall() {
  _imageresize_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function imageresize_civicrm_enable() {
  _imageresize_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function imageresize_civicrm_disable() {
  _imageresize_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function imageresize_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _imageresize_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function imageresize_civicrm_managed(&$entities) {
  _imageresize_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function imageresize_civicrm_caseTypes(&$caseTypes) {
  _imageresize_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function imageresize_civicrm_angularModules(&$angularModules) {
  _imageresize_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function imageresize_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _imageresize_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function imageresize_civicrm_entityTypes(&$entityTypes) {
  _imageresize_civix_civicrm_entityTypes($entityTypes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function imageresize_civicrm_preProcess($formName, &$form) {

} // */

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