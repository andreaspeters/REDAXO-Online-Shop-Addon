<?php

/**
 * Logbook Classes
 * @author mailbox[at]andreas-peters[dot]net Andreas Peters
 * @author <a href="http://www.andreas-peters.net">www.andreas-peters.net</a>
 * @package redaxo4
 */

class OOLogbook {
  
  protected $REX, $I18N;
  public $tableProducts, $tableClient, $tableCat, $table, $tableUser, $tableHour, $rexUser, $rexGroups;
  public $records, $sqlRef;

  public function OOLogbook ($REX) {
        $this->REX = $REX;
	if (!$REX['COM_USER']) {
	  return -1;
	}

        $this->sqlRef      = new rex_sql();

        $this->tableUser   = "rex_com_user";
        $this->rexUser     = $REX['COM_USER']->getValue("login");
	$this->rexGroups   = explode(",",$REX['COM_USER']->getValue('rex_com_group'));
        $this->intUserID   = $REX['COM_USER']->getValue("intUserID");
	$this->language    = "";
	if ($REX['CUR_CLANG']) {
		$this->language    = $REX['CUR_CLANG'];	
	}

        // wenn es noch keine interne userid gibt, dann muss diese angelegt werden und zusaetzlich wird die Logbook DB Struktur des
        // jeweiligen users erzeugt.
        if (!$this->intUserID) {
	  $this->intUserID = md5($this->rexUser."kunde".mt_rand(mt_rand(1,100),1000));
  	  $this->sqlRef->setTable("$this->tableUser");
	  $this->sqlRef->setValue("intUserID",$this->intUserID);
	  $this->sqlRef->setWhere(sprintf("login = '%s'",$this->rexUser));
	  $this->sqlRef->update();

          $this->tableCat    = $REX['TABLE_PREFIX']."".$REX['ADDON']['rxid']['logbook']."_logbook_cat_".$REX['COM_USER']->getValue("intUserID");
          $this->createLogbookTables();
        }

	$this->tableClient = $REX['TABLE_PREFIX']."".$REX['ADDON']['rxid']['logbook']."_logbook_client_".$REX['COM_USER']->getValue("intUserID");
	$this->tableCat    = $REX['TABLE_PREFIX']."".$REX['ADDON']['rxid']['logbook']."_logbook_cat_".$REX['COM_USER']->getValue("intUserID");
	$this->tableHour   = $REX['TABLE_PREFIX']."".$REX['ADDON']['rxid']['logbook']."_logbook_hour_".$REX['COM_USER']->getValue("intUserID");
	$this->table       = $REX['TABLE_PREFIX']."".$REX['ADDON']['rxid']['logbook']."_logbook_".$REX['COM_USER']->getValue("intUserID");
        $this->defaultClient = $this->getDefaultClient();

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

        $this->lang        = new i18n($language, $basedir.'/../lang');
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

 /*
        Paul's Simple Diff Algorithm v 0.1
        (C) Paul Butler 2007 <http://www.paulbutler.org/>
        May be used and distributed under the zlib/libpng license.
       
        This code is intended for learning purposes; it was written with short
        code taking priority over performance. It could be used in a practical
        application, but there are a few ways it could be optimized.
       
        Given two arrays, the function diff will return an array of the changes.
        I won't describe the format of the array, but it will be obvious
        if you use print_r() on the result of a diff on some test data.
       
        htmlDiff is a wrapper for the diff command, it takes two strings and
        returns the differences in HTML. The tags used are <ins> and <del>,
        which can easily be styled with CSS. 
*/

 

  private function diff($old, $new){
   foreach($old as $oindex => $ovalue) { 
     $nkeys = array_keys($new, $ovalue);
     foreach($nkeys as $nindex){
       $matrix[$oindex][$nindex] = isset($matrix[$oindex - 1][$nindex - 1]) ?  $matrix[$oindex - 1][$nindex - 1] + 1 : 1;
       if($matrix[$oindex][$nindex] > $maxlen){
         $maxlen = $matrix[$oindex][$nindex];
         $omax = $oindex + 1 - $maxlen;
         $nmax = $nindex + 1 - $maxlen;
       }
     }       
   }
   if($maxlen == 0) return array(array('d'=>$old, 'i'=>$new));
   return array_merge(
     $this->diff(array_slice($old, 0, $omax), array_slice($new, 0, $nmax)),
     array_slice($new, $nmax, $maxlen),
     $this->diff(array_slice($old, $omax + $maxlen), array_slice($new, $nmax + $maxlen)));
 }

  public function htmlDiff($old, $new){
    $diff = $this->diff(explode(' ', $old), explode(' ', $new));
    foreach($diff as $k){
      if(is_array($k))
        $ret .= (!empty($k['d'])?"<del>".implode(' ',$k['d'])."</del> ":'').
                (!empty($k['i'])?"<ins>".implode(' ',$k['i'])."</ins> ":'');
      else $ret .= $k . ' ';
    }
    return $ret;
  }  
  
  /* Create a Array with all "clients".
     $array['id'] -> String
  */	 
  public function getClients($id = 0) {
    if ($id <> 0) {
	  $sql = " and id = '$id'";
	}
  
	$this->sqlRef->setQuery(sprintf("select * from %s where user = '%s' $sql",$this->tableClient, $this->rexUser));
	foreach($this->sqlRef->getArray() as $key => $value) {
	  $ret[$value['id']]['client']  = $value['client'];
	  $ret[$value['id']]['price']   = $value['price'];
	  $ret[$value['id']]['default'] = $value['default'];
	  $ret[$value['id']]['id']      = $value['id'];
          $ret[$value['id']]['street']   = $value['street'];
          $ret[$value['id']]['city']    = $value['city'];
          $ret[$value['id']]['companyname'] = $value['companyname'];
          $ret[$value['id']]['postalcode']  = $value['postalcode'];
          $ret[$value['id']]['country']     = $value['country'];
	}    
	return $ret;
  }

  /*
   * Das Array zu dem Firmennamen eines kunden ermitteln 
   */
  public function getClientByString($string) {
  	if ($string) {
		$allClients = $this->getClients();
		foreach ($allClients as $key => $value) {
			if ($string == $value['companyname']) {
				return $value;
			}			
		}	
  	}
  }
  
  
  /* Create a Array with all "categories".
     $array['id'] -> String
  */	 
  public function getCategories($id = 0) {
	$sql = "";
        if ($id <> 0) {
          $sql = " and id = '$id'";
        }

        $this->sqlRef->setQuery(sprintf("select distinct(cat), id from %s where user = '%s' $sql", $this->tableCat, $this->rexUser));

	foreach($this->sqlRef->getArray() as $key => $value) {
	  $ret[$value['id']]['id'] = $value['id'];
       	  $ret[$value['id']]['cat'] = $value['cat'];
	}    
	return $ret;
  }  

  /*
   * Gibt das Array eines Tags zu einem String aus  
   * 
   */
  public function getCategoriesByString($string) {
  	if ($string) {
  			
		$allTags = $this->getCategories();
		foreach ($allTags as $key => $value) {
			if ($string == $value['cat']) {
				return $value;
			}			
		}	
  	}
  }   

  /* Create the HTML PullDown option Items to filter "client" records*/
  public function getPullDownClient($selected) {
  	$this->sqlRef->setQuery(sprintf("select * from %s where user = '%s'",$this->tableClient, $this->rexUser));
 	foreach($this->sqlRef->getArray() as $key => $value) {
          $select = "";
          if ($selected == $value['id'] || $value['default']) {
            $select = 'selected="selected"';
          }
	  $pullDown .= '<option '.$select.' value="'.$value['id'].'">'.$value['companyname'].'</option>';
        }
	return $pullDown;
  }
  
  
  /* Create the HTML PullDown option Items to filter "categories" records*/
  public function getPullDownCategories($selected) {
  	$this->sqlRef->setQuery(sprintf("select distinct(cat), id from %s where user = '%s'", $this->tableCat, $this->rexUser));
 	foreach($this->sqlRef->getArray() as $key => $value) {
      $select = "";
	  $catarray = explode(",",$selected);
      if (in_array($value['id'],$catarray)) {

        $select = 'selected="selected"';
      }
	  $pullDown .= '<option '.$select.' value="'.$value['id'].'">'.$value['cat'].'</option>';
	}
	return $pullDown;
  }    

  
  /* Create the HTML PullDown option Items to filter "finish" records*/
  public function getPullDownFinish($selected) {
  	$this->sqlRef->setQuery(sprintf("select distinct(finish) from %s where user = '%s'",$this->table, $this->rexUser));
    $pullDown = '<option value=""></option>';
 	foreach($this->sqlRef->getArray() as $key => $value) {
      $select = "";
      if ($selected == $value['finish']) {
        $select = 'selected="selected"';
      }
	  $pullDown .= '<option '.$select.' value="'.$value['finish'].'">'.$value['finish'].'</option>';
	}
	return $pullDown;
  }  
  
  
  /* Create the HTML PullDown option Items to filter "kw" records*/
  public function getPullDownKw($selected) {
  	$this->sqlRef->setQuery(sprintf("select distinct(kw) from %s where user = '%s'", $this->table, $this->rexUser));
    $pullDown = '<option value=""></option>';
 	foreach($this->sqlRef->getArray() as $key => $value) {
      $select = "";
      if ($selected == $value['kw']) {
        $select = 'selected="selected"';
      }
	  $pullDown .= '<option '.$select.' value="'.$value['kw'].'">'.$value['kw'].'</option>';
	}
	return $pullDown;
  }   
  
  /* Create the HTML PullDown option Items to filter "year" records*/
  public function getPullDownYear($selected) {
  	$this->sqlRef->setQuery(sprintf("select distinct(year) from %s where user = '%s'", $this->table, $this->rexUser));
    $pullDown = '<option value=""></option>';
 	foreach($this->sqlRef->getArray() as $key => $value) {
      $select = "";
      if ($selected == $value['year']) {
        $select = 'selected="selected"';
      }
	  $pullDown .= '<option '.$select.' value="'.$value['year'].'">'.$value['year'].'</option>';
	}
	return $pullDown;
  }

  /* Create the HTML PullDown option Items to filter "month" records*/
  public function getPullDownMonth($selected) {
        $this->sqlRef->setQuery(sprintf("select distinct(months) from %s where user = '%s'", $this->table, $this->rexUser));
    $pullDown = '<option value=""></option>';
        foreach($this->sqlRef->getArray() as $key => $value) {
      $select = "";
      if ($selected == $value['months']) {
        $select = 'selected="selected"';
      }
          $pullDown .= '<option '.$select.' value="'.$value['months'].'">'.$value['months'].'</option>';
        }
        return $pullDown;
  }
    

  private function getDefaultClient() {
    $this->sqlRef->setQuery(sprintf("select * from %s where user = '%s'", $this->tableClient, $this->rexUser));
    foreach ($this->sqlRef->getArray() as $key => $value) {
      if ($value['default']) {
        return $value['id'];
      }
    }
  }

  // Erstellt ein Button  
  public function createButton($link,$label,$id) {
//		print '<a href="'.$link.'" alt="'.$label.'" title="'.$label.'"><button id="'.$id.'">'.$label.'</button></a>';
//	print '<button id="'.$id.'"><a href="'.$link.'" alt="'.$label.'" title="'.$label.'">'.$label.'</a></button>';
	print '<a id="'.$id.'" href="'.$link.'" alt="'.$label.'" title="'.$label.'">'.$label.'</button></a>';
  }

  public function createLogbookTables() {


    $this->sqlRef->setQuery(sprintf("CREATE TABLE IF NOT EXISTS rex_ap_logbook_%s (
  `id` int unsigned NOT NULL auto_increment,
  `subject` varchar(255) NOT NULL,
  `description` longtext default NULL,
  `year` varchar(6) default NULL,
  `months` varchar(2) default NULL,
  `day` varchar(2) default NULL,
  `week` varchar(2) default NULL,
  `project_id` int(11) default NULL,
  `client_id` int(11) default NULL,
  `cat_id` text default NULL, 
  `finish` bool default NULL, 
  `public` bool default NULL, 
  `kw` int(11) default NULL,
  `user` varchar(255) default NULL,
  `update` varchar(255) default NULL,
  `active` bool default NULL,
  `parent` int(10) default NULL,
  `scheduledend` varchar(255) default NULL,
  `scheduledbegin` varchar(255) default NULL, 
  `filecontent` longblob default NULL,
  `filetype` text default NULL,
  `delete` bool default NULL,  
  `concatwith` text default NULL,
  PRIMARY KEY  (`id`))  ENGINE=MYISAM DEFAULT CHARSET=utf8
",$this->intUserID));



    $this->sqlRef->setQuery(sprintf("CREATE TABLE IF NOT EXISTS rex_ap_logbook_cat_%s (
  `id` int unsigned NOT NULL auto_increment,
  `cat` varchar(255) NOT NULL,
  `user` varchar(255) default NULL,  
  PRIMARY KEY  (`id`)) ENGINE=MYISAM DEFAULT CHARSET=utf8
",$this->intUserID));

    $this->sqlRef->setQuery(sprintf("CREATE TABLE IF NOT EXISTS rex_ap_logbook_client_%s (
  `id` int unsigned NOT NULL auto_increment,
  `client` varchar(255) NOT NULL,
  `user` varchar(255) default NULL,  
  `default` varchar(255) default NULL,  
  `price` decimal(7,2) default NULL,  
  `street` varchar(255) default NULL,
  `companyname` varchar(255) default NULL,
  `city` varchar(255) default NULL,
  `postalcode` varchar(255) default NULL,
  `country` varchar(255) default NULL,
  PRIMARY KEY  (`id`)) ENGINE=MYISAM DEFAULT CHARSET=utf8
",$this->intUserID));

    $this->sqlRef->setQuery(sprintf("CREATE TABLE IF NOT EXISTS rex_ap_logbook_hour_%s (
  `id` int unsigned NOT NULL auto_increment,
  `user` varchar(255) NOT NULL,
  `year` varchar(6)  NOT NULL,
  `month` varchar(2) NOT NULL,
  `day` varchar(2) NOT NULL,
  `from` varchar(5) NOT NULL,
  `to` varchar(5) NOT NULL,
  `rest` varchar(5) NOT NULL,
  `subject` varchar(255) NOT NULL,  
  `price` decimal(7,2) default NULL,
  `client` varchar(255) default NULL,    
  PRIMARY KEY  (`id`)) ENGINE=MYISAM DEFAULT CHARSET=utf8
",$this->intUserID));

    $this->sqlRef->setQuery(sprintf("insert into rex_ap_logbook_cat_%s (`id`, `cat`, `user`) values ('1', 'Meeting', '%s')",$this->intUserID, $this->rexUser));
    $this->sqlRef->setQuery(sprintf("insert into rex_ap_logbook_cat_%s (`id`, `cat`, `user`) values ('2', 'Phone Call', '%s')",$this->intUserID, $this->rexUser));
    $this->sqlRef->setQuery(sprintf("insert into rex_ap_logbook_cat_%s (`id`, `cat`, `user`) values ('3', 'Knowlege', '%s')",$this->intUserID, $this->rexUser));
    $this->sqlRef->setQuery(sprintf("insert into rex_ap_logbook_cat_%s (`id`, `cat`, `user`) values ('5', 'Task', '%s')",$this->intUserID, $this->rexUser));
    $this->sqlRef->setQuery(sprintf("insert into rex_ap_logbook_cat_%s (`id`, `cat`, `user`) values ('6', 'File', '%s')",$this->intUserID, $this->rexUser));
    $this->sqlRef->setQuery(sprintf("insert into rex_ap_logbook_cat_%s (`id`, `cat`, `user`) values ('7', 'Note', '%s')",$this->intUserID, $this->rexUser));


  }
}
