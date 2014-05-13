<?php

/**
 * LogbookClient Classes
 * @author mailbox[at]andreas-peters[dot]net Andreas Peters 
 * @author <a href="http://www.andreas-peters.net">www.andreas-peters.net</a>
 * @package redaxo4
 */
 
class OOLogbookClients extends OOLogbook {

  public function OOLogbookClients($REX) {
    parent::OOLogbook($REX);
  }

   
  /* This a client. */
  public function deleteClient($id) {
    $this->sqlRef->setTable($this->tableClient);
    $this->sqlRef->setWhere("id = '$id' and user = '$this->rexUser'");
    $this->sqlRef->delete();   
  }

  /* get the Price of the Client */
  public function getPrice($id) {
    $this->sqlRef->setQuery(sprintf("select price from %s where user = '%s' and id = '%d' limit 1 ",$this->tableClient, $this->rexUser, $id));
    $res = $this->sqlRef->getArray();
    return $res[0]['price'];	
  }

}
