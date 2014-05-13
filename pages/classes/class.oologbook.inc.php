<?php

/**
 * Logbook Classes
 * @author mailbox[at]andreas-peters[dot]net Andreas Peters
 * @author <a href="http://www.andreas-peters.net">www.andreas-peters.net</a>
 * @package redaxo4
 */

class OOLogbook {
  
  protected $REX, $tableProducts, $sqlRef;

  public function OOCoins($REX) {
    $this->REX = $REX;
    $this->tableClient     = $REX['TABLE_PREFIX']."".$REX['ADDON']['rxid']['logbook']."_logbook_client";
    $this->tableCat        = $REX['TABLE_PREFIX']."".$REX['ADDON']['rxid']['logbook']."_logbook_cat";
    $this->tableLogbook    = $REX['TABLE_PREFIX']."".$REX['ADDON']['rxid']['logbook']."_logbook";
    $this->tableCommunity  = "rex_com_user";
    $this->sqlRef = new rex_sql();

  }

  private function condate($condate) { 
    $dateElements = explode("-",$condate); 
    return mktime(0,0,0,$dateElements[1],$dateElements[3],$dateElements[0]); 
  }

  public function dayDiff($DATEA, $DATEB) {
    $sec1=condate($DATEA); 
    $sec2=condate($DATEB);
    $secdiff = $sec2-$sec1;
    $minuten=$secdiff/60; 
    $stunden = $minuten/60; 
    $tage = $stunden/24; 
    return $tage;
  }

  }
