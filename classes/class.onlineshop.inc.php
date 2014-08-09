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
	public $basket;

	public function OOOnlineShop () {
		global $REX;
		$this->REX = $REX;

        $this->sqlRef = new rex_sql();
		$this->basket = new OOBasket();
	}

	/*
		Function:		getProductsList
		Description:	Give out all products inside of the databases
		Parameters:		$param = Array of
									'from'  = Start with product number
									'limit' = Give out only "limit" products
									'cat'	= Give out only products in this category
		Return:			Array with all found products
	*/
	public function getProductsList($param) {
		$from     = htmlentities($param['from']);
		$limit    = htmlentities($param['limit']);
		$category = htmlentities($param['cat']);
		$where	  = ""; // The SQL WHERE

		// Category filter
		if (!empty($category)) {
			$where .= sprintf("%s = '%d' AND ", "rex_onlineshop_category", $category);
		}
		if (!empty($where)) {
			// remove the last "AND " string and add a " where "
			$where = substr($where, 0, -4);
			$where = sprintf(" where %s", $where);
		}
	
		if (!empty($limit)) {
			// Show only a limit count of products
			$this->sqlRef->setQuery(sprintf("select * from %s %s limit %d, %d","rex_onlineshop_products", $where, $from, $limit));
		} else {
			$this->sqlRef->setQuery(sprintf("select * from %s %s","rex_onlineshop_products", $where));
		}
		return $this->sqlRef->getArray();		
	}

	/*
		Function:		getDetailOfProduct
		Description:	Get out the detail information of a product id
		Parameters:		$param = Array of 
									'id' = The product id
		Return:			Array with the product details
	*/
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

	public function getTaxValue($id) {
		$this->sqlRef->setQuery(sprintf("select * from %s where id = '%d'","rex_onlineshop_tax", $id));
		$res = $this->sqlRef->getArray();
		return $res[0]['percent'];
	}

	/*
		Function:		getImageByName
		Description:	Get out the image from the mediapool by the file name
		Parameters:		$param = Array of
							'name' = Image File Name
							'width' = Image width for resizing (optional)
							'height' = Image height for resizing (optional)
		Return:			Image as HTML
	*/
	public function getImageByName($param) {
		$imageName = htmlentities($param['name']);

		// Without a ImageName, we can do nothing
		if (!$imageName)
			return;

		$imageWidth = "";
		$imageHeight = "";
		if (isset($param['width'])) {
			$imageWidth = htmlentities($param['width']);
		}
		if (isset($param['height'])) {
			$imageHeight = htmlentities($param['height']);
		}

		$image = OOMedia::getMediaByFileName($imageName);
		if ($image instanceof OOMedia && $image->isImage()) {
			$width  = $imageWidth ?: $image->getWidth();
			$height = $imageHeight ?: $image->getHeight();

			return $image->toHtml(array('width' => $width, 'height' => $height));
		}
	}
}
