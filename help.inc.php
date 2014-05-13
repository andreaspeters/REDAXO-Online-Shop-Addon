<?php

/**
 * Logbook Classes 
 * @author mailbox[at]andreas-peters[dot]net Andreas Peters
 * @author <a href="http://www.andreas-peters.net">www.andreas-peters.net</a>
 * @package redaxo4
 */

 
if ( !isset( $mode)) $mode = '';
switch ( $mode) {
   case 'changelog': $file = '_changelog.txt'; break;
   default: $file = '_readme.txt'; 
}

echo str_replace( '+', '&nbsp;&nbsp;+', nl2br( file_get_contents( dirname( __FILE__) .'/'. $file)));

?>