<?php

$addonname = 'onlineshop';

$I18N->appendFile($REX['INCLUDE_PATH'] . '/addons/community/lang');

if ($REX['VERSION'] != '4' || $REX['SUBVERSION'] < '5') {
  $REX['ADDON']['install']['community'] = 0;
  $REX['ADDON']['installmsg']['community'] = $I18N->msg('community_install_redaxo_version_problem', '4.5', $REX['VERSION'].".".$REX['SUBVERSION']);

} elseif (OOAddon::isAvailable('phpmailer') != 1 || version_compare(OOAddon::getVersion('phpmailer'), '2.8', '<')) {
  $REX['ADDON']['install']['community'] = 0;
  $REX['ADDON']['installmsg']['community'] = $I18N->msg('community_install_phpmailer_version_problem', '2.8');

} elseif (OOAddon::isAvailable('xform') != 1 || version_compare(OOAddon::getVersion('xform'), '4.5', '<')) {
  $REX['ADDON']['install']['community'] = 0;
  $REX['ADDON']['installmsg']['community'] = $I18N->msg('community_install_xform_version_problem', '4.5');

} elseif (OOPlugin::isAvailable('xform', 'manager') != 1 || version_compare(OOPlugin::getVersion('xform', 'manager'), '4.5', '<')) {
  $REX['ADDON']['install']['community'] = 0;
  $REX['ADDON']['installmsg']['community'] = $I18N->msg('community_install_xform_manager_version_problem', '4.5');

} elseif (OOPlugin::isAvailable('xform', 'email') != 1 || version_compare(OOPlugin::getVersion('xform', 'email'), '4.5', '<')) {
  $REX['ADDON']['install']['community'] = 0;
  $REX['ADDON']['installmsg']['community'] = $I18N->msg('community_install_xform_email_version_problem', '4.5');

} elseif (version_compare(PHP_VERSION, '5.3.0', '<')) {
  $REX['ADDON']['install']['community'] = 0;
  $REX['ADDON']['installmsg']['community'] = $I18N->msg('community_install_php_version_problem', "5.3.0", PHP_VERSION);

} else {

  $msg = '';

  $autoinstall = array('auth', 'group', 'newsletter');

  // GET ALL ADDONS & PLUGINS
  $all_addons = rex_read_addons_folder();
  $all_plugins = array();
  foreach ($all_addons as $_addon) {
    $all_plugins[$_addon] = rex_read_plugins_folder($_addon);
  }

  // DO AUTOINSTALL
  $pluginManager = new rex_pluginManager($all_plugins, $addonname);
  foreach ($autoinstall as $pluginname) {
    // INSTALL PLUGIN
    if (($instErr = $pluginManager->install($pluginname)) !== true) {
      $msg = $instErr;
    }

    // ACTIVATE PLUGIN
    if ($msg == '' && ($actErr = $pluginManager->activate($pluginname)) !== true) {
      $msg = $actErr;
    }

    if ($msg != '') {
      break;
    }
  }

  if ($msg != '') {
    $REX['ADDON']['installmsg'][$addonname] = $msg;

  } else {

    $REX['ADDON']['install']['community'] = 1;

    function rex_com_install()
    {
      $r = new rex_xform_manager;
      $r->generateAll();
    }
    rex_register_extension('OUTPUT_FILTER', 'rex_com_install');

    $c_file = $REX['INCLUDE_PATH'] . '/addons/community/install/rex_4.5_community';
    $ie_file = $REX['INCLUDE_PATH'] . '/addons/import_export/backup/rex_4.5_community';

    copy( $c_file . '.sql' , $ie_file . '.sql' );
    copy( $c_file . '.tar.gz' , $ie_file . '.tar.gz' );

  }

}
