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

	public function OOOnlineShop () {
		global $REX;
		$this->REX = $REX;

        $this->sqlRef = new rex_sql();
	}

	public function getProductsList() {
		$this->sqlRef->setQuery(sprintf("select * from %s","rex_onlineshop_products"));
		return $this->sqlRef->getArray();		
	}

	public function getDetailOfProduct($param) {
		$productId = htmlentities($param['id']);
		$this->sqlRef->setQuery(sprintf("select * from %s where id = '%d'","rex_onlineshop_products", $productId));
		return $this->sqlRef->getArray();		
	}


	public function getCouponsList() {
		$this->sqlRef->setQuery(sprintf("select * from %s where type = '2' ","rex_onlineshop_products"));
		return $this->sqlRef->getArray();		
	}

	public function getTypeList() {
	    $this->sqlRef->setQuery(sprintf("select * from %s","rex_onlineshop_type"));
		return $this->sqlRef->getArray();
	}

	public function getDeliveryList() {
		$this->sqlRef->setQuery(sprintf("select * from %s","rex_onlineshop_delivery"));
		return $this->sqlRef->getArray();
	}


	public function getCategoryList() {
	    $this->sqlRef->setQuery(sprintf("select * from %s","rex_onlineshop_category"));
		return $this->sqlRef->getArray();
	}

	public function getTaxList() {
		$this->sqlRef->setQuery(sprintf("select * from %s","rex_onlineshop_tax"));
		return $this->sqlRef->getArray();
	}



}

