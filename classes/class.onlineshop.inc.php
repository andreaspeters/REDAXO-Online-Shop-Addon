<?php

/**
 * @author mailbox[at]andreas-peters[dot]net Andreas Peters
 * @author <a href="http://www.andreas-peters.net">www.andreas-peters.net</a>
 * @package redaxo4
 */

class OOOnlineShop {
  
	protected $REX, $I18N, $api;
	public $rexUser, $rexGroups;
	public $sqlRef;

	public function OOOnlineShop ($REX) {
		$this->REX = $REX;
		if (!$REX['COM_USER']) {
			return -1;
		}

        $this->sqlRef      = new rex_sql();
        $this->rexUser     = $REX['COM_USER']->getValue("login");
		$this->rexGroups   = explode(",",$REX['COM_USER']->getValue('rex_com_group'));
        $this->intUserID   = $REX['COM_USER']->getValue("id");

        // Add Multilanguage
        $basedir = dirname(__FILE__);

		switch ($this->language) {
          case 0: 
            $language = "de_de_utf8";break;
          case 1:
            $language = "en_en_utf8";break;
          default:
            $language = "de_de_utf8";          
        }

        $this->lang = new i18n($language, $basedir.'/../lang');
	}

	public function checkPermission($api) {
		$this->api = $api;
		return 0;
	}

	public function getArticle() {
		return 0;
	}

	private function condate($DATE) { 
		$dateElements = explode("-",$DATE); 
		return mktime(0,0,0,$dateElements[1],$dateElements[2],$dateElements[0]);
	}

	public function dayDiff($dateA, $dateB) {
		if ($dateA == "" || $dateB == "") {
			return;
		}
		$sec1=$this->condate($dateA); 
		$sec2=$this->condate($dateB); 
		$secdiff = $sec2-$sec1;  
		$minuten=$secdiff/60; 
		$stunden = $minuten/60; 
		$tage = $stunden/24; 
		return $tage;
	}
}

