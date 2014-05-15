<?php

/**
 * @author mailbox[at]andreas-peters[dot]net Andreas Peters
 * @author <a href="http://www.andreas-peters.net">www.andreas-peters.net</a>
 * @package redaxo4
 */


$mypage = "onlineshop"; 

if(!isset($_SESSION)) {
  session_start();
}

$REX['ADDON']['page'][$mypage] = $mypage;
$REX['ADDON']['rxid'][$mypage] = 'av';
$REX['ADDON']['name'][$mypage] = 'Onlineshop';
$REX['ADDON']['perm'][$mypage] = 'onlineshop[]';
$REX['ADDON']['version'][$mypage] = '0.1';
$REX['ADDON']['author'][$mypage] = 'Andreas Peters';
$REX['ADDON']['supportpage'][$mypage] = 'http://www.aventer.biz';

$REX['PERM'][] = 'onlineshop[]';

$basedir = dirname(__FILE__);

if (isset($I18N) && is_object($I18N))
  $I18N->appendFile($basedir.'/lang');


require_once $basedir.'/classes/class.onlineshop.inc.php';

if ($REX['REDAXO'] && $REX['USER']) {

#  $REX['ADDON'][$mypage]['SUBPAGES'] = array (
#					      array ('clients',$I18N->msg('clients')),
#					      array ('categories',$I18N->msg('categories'))
#					      );

}


?>
