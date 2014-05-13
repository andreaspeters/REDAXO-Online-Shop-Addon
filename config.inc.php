<?php

/**
 * Logbook Classes
 * @author mailbox[at]andreas-peters[dot]net Andreas Peters
 * @author <a href="http://www.andreas-peters.net">www.andreas-peters.net</a>
 * @package redaxo4
 */


$mypage = basename(dirname(__FILE__)); 

$REX['ADDON']['page'][$mypage] = $mypage;
$REX['ADDON']['rxid'][$mypage] = 'ap';
$REX['ADDON']['name'][$mypage] = 'Logbook';
$REX['ADDON']['perm'][$mypage] = 'logbook[]';
$REX['ADDON']['version'][$mypage] = '0.3';
$REX['ADDON']['author'][$mypage] = 'Andreas Peters';
$REX['ADDON']['supportpage'][$mypage] = 'http://www.andreas-peters.net';

$REX['PERM'][] = 'logbook[]';

$basedir = dirname(__FILE__);

if (isset($I18N) && is_object($I18N))
  $I18N->appendFile($basedir.'/lang');


require_once $basedir.'/classes/class.oologbook.inc.php';
require_once $basedir.'/classes/class.oologbookcalendar.inc.php';
require_once $basedir.'/classes/class.oologbookrecords.inc.php';
require_once $basedir.'/classes/class.oologbookclients.inc.php';
require_once $basedir.'/classes/class.oologbookerror.inc.php';

if ($REX['REDAXO'] && $REX['USER']) {

  $REX['ADDON'][$mypage]['SUBPAGES'] = array (
					      array ('clients',$I18N->msg('clients')),
					      array ('categories',$I18N->msg('categories'))
					      );

   }


?>
