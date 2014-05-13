<?php

/**
 * LogbookRecords Classes
 * @author mailbox[at]andreas-peters[dot]net Andreas Peters 
 * @author <a href="http://www.andreas-peters.net">www.andreas-peters.net</a>
 * @package redaxo4
 */
 
class OOLogbookRecords extends OOLogbook {

  public function OOLogbookRecords($REX) {
    parent::OOLogbook($REX);
  }

   
  /* This deactivate a record. That will hide it from the lists. */
  public function deleteRecord($id) {
    $this->sqlRef->setTable($this->table);
    $this->sqlRef->setValue('active',0);
    $this->sqlRef->setValue('delete',1);
    $this->sqlRef->setWhere(sprintf("id = '%d' and user = '%s'",$id, $this->rexUser));
    $this->sqlRef->update();   
  }

  /* Get the content of a record. */
  public function getContentOfRecord($id) {
    $this->sqlRef->setQuery(sprintf("select * from %s where id  = %d and user = '%s' limit 1", $this->table, $id, $this->rexUser));
    $res = $this->sqlRef->getArray();
    $content = $res[0];
    return $content;
  }

  
  /* Get out records as array */
  public function getRecords($text, $cat, $client, $finish, $displayFrom = "0", $displayLength = "100000000000000") {
    $sql = "";

    if ($finish == "1") {
      $sql .= "and finish = '1'";
    } 

    if ($finish == "0") {
      $sql .= "and finish = '0'";
    } 

    if ($cat) {
      $sql .= "and cat_id like '%$cat%'";
    }

    if ($client) {
      $sql .= "and client_id = '$client'";
    }


    $this->sqlRef->setQuery("select `id`,`subject`,`year`,`months`,`day`,`project_id`,`client_id`,`cat_id`,`finish`,`update`,`active`,`scheduledend`,`scheduledbegin`,`delete` from $this->table where (description like '%$text%' or subject like '%$text%' ) and active = 1 and user = '$this->rexUser' $sql order by id desc limit $displayFrom, $displayLength");

    
    return $this->sqlRef->getArray();
  }

  /* Get out all deletes objects */
  public  function getTrash($displayFrom = "0", $displayLength = "100000000000000") {
  	  $this->sqlRef->setQuery("select `id`,`subject`,`year`,`months`,`day`,`project_id`,`client_id`,`cat_id`,`finish`,`update`,`active`,`scheduledend`,`scheduledbegin`,`delete` from $this->table where `delete` = 1 and user = '$this->rexUser' order by id desc limit $displayFrom, $displayLength");

	  return $this->sqlRef->getArray();
  }


  /* Recover the trashcan */
  public function recoverTrashRecord($id) {
    $this->sqlRef->setTable($this->table);
    $this->sqlRef->setValue('delete',0);
    $this->sqlRef->setValue('active',1);
    $this->sqlRef->setWhere(sprintf("id = '%d' and user = '%s'",$id, $this->rexUser));
    $this->sqlRef->update();     	
  }

	/*
	 * Create New Record
	 */
	public function createNewRecord($subject, $date, $client, $cat, $kw, $finish, $scheduledbegin, $scheduledend, $description) {
	       		$date = preg_split("/\-/", $date);

			$sqlRef = new rex_sql;
			$sqlRef->setTable($this->table);
			$sqlRef->setValue("subject", $subject);
			$sqlRef->setValue("day", $date[2]);
			$sqlRef->setValue("months", $date[1]);
			$sqlRef->setValue("year", $date[0]);
			$sqlRef->setValue("client_id", $client);
			$sqlRef->setValue("cat_id", $cat);
			$sqlRef->setValue("kw", $kw);
			$sqlRef->setValue("finish", $finish);
			$sqlRef->setValue("active", 1);
			$sqlRef->setValue("scheduledbegin", $scheduledbegin);		
			$sqlRef->setValue("scheduledend", $scheduledend);		
			$sqlRef->setValue("user", $this->rexUser );
			$sqlRef->setValue("description", $description);
			
			if ($subject) {
	 	  		$sqlRef->insert();
			} else {
				return -1;
			}
	}  
}
