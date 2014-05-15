<?php

$addonname = 'onlineshop';

$I18N->appendFile($REX['INCLUDE_PATH'] . '/addons/onlineshop/lang');



if (OOAddon::isAvailable('xform') != 1 || version_compare(OOAddon::getVersion('xform'), '4.5', '<')) {
	$REX['ADDON']['install']['onlineshop'] = 0;
	$REX['ADDON']['installmsg']['onlineshop'] = $I18N->msg('onlineshop_install_xform_version_problem', '4.5');
} elseif (OOAddon::isAvailable('community') != 1 || version_compare(OOAddon::getVersion('community'), '4.5', '<')) {
	$REX['ADDON']['install']['onlineshop'] = 0;
	$REX['ADDON']['installmsg']['onlineshop'] = $I18N->msg('onlineshop_install_community_version_problem', '4.5');
} else {
	$REX['ADDON']['install']['onlineshop'] = 1;

    function rex_com_install() {
      $r = new rex_xform_manager;
      $r->generateAll();
    }
    rex_register_extension('OUTPUT_FILTER', 'rex_com_install');

}
