<?php

/**
 * Logbook Classes
 * @author mailbox[at]andreas-peters[dot]net Andreas Peters
 * @author <a href="http://www.andreas-peters.net">www.andreas-peters.net</a>
 * @package redaxo4
 */

$basedir = dirname(__FILE__);

include $REX["INCLUDE_PATH"]."/layout/top.php";

// load env variables
$subpage = rex_request("subpage","string","");

// create menu list
$subpages = array ( 
		   array ('clients', $I18N->msg('kunden')), 
		   array ('categories', $I18N->msg('categories'))
		    );

// create title and show the menu list
rex_title($I18N->msg("Logbook"), $subpages);

if ( !isset($subpage)) $subpage = '';

// Load the subpage
switch ($subpage)
  {      
  case 'clients' :
    $file = $basedir.'/clients.inc.php';
    break;
  case 'categories' :
    $file = $basedir.'/categories.inc.php';
    break;
  default :
    $file = $basedir.'/clients.inc.php';
  }


include $file;


include $REX["INCLUDE_PATH"]."/layout/bottom.php";
