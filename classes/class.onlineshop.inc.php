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
	private $lang;

	public function OOOnlineShop () {
	 	global $REX, $I18N;
		$this->lang = $I18N;
		$this->REX = $REX;

        $this->sqlRef = new rex_sql();
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
			$where .= sprintf("AND %s = '%d' ", "rex_onlineshop_category", $category);
		}
		if (!empty($where)) {
			// remove the last "AND " string and add a " where "
//			$where = substr($where, 0, -4);
//			$where = sprintf(" where %s", $where);
		}

		if (!empty($limit)) {
			// Show only a limit count of products
			$this->sqlRef->setQuery(sprintf("select * from %s where parent = 0 %s limit %d, %d","rex_onlineshop_products", $where, $from, $limit));
		} else {
			$this->sqlRef->setQuery(sprintf("select * from %s where parent = 0 %s","rex_onlineshop_products", $where));
		}
 
		return $this->sqlRef->getArray();		
	}

	/*
		Function:		getChildsOfProduct
		Description:	Give out all childrens of a products
		Parameters:		$param = Array of
									'id'  = Parent ID
		Return:			Array with all found products id's
	*/
	public function getChildsOfProduct($param) {
		$id = htmlentities($param['id']);

		$this->sqlRef->setQuery(sprintf("select * from %s where parent like %d or id = %d","rex_onlineshop_products", $id, $id));

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


	/*
		Function:		getCategoryValue
		Description:	Get out the name of the category id
		Parameters:		$id = category id
		Return:			String = Category Name
	*/
	public function getCategoryValue($id) {
		$this->sqlRef->setQuery(sprintf("select * from %s where id = '%d'","rex_onlineshop_category", $id));
		$res = $this->sqlRef->getArray();
		return $res[0]['name'];
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


	/*
		Function:		Set the Product Details
		Description:	Save the detailed information of a product
		Parameters:		$param = Array of
								'name'						= Product Name
								'description'				= Product Description
								'price'						= The price before tax
								'rex_onlineshop_category'	= The category of the product
								'size'						= Products Size
								'color'						= Color of the Product
								'dimension_h'				= Height
								'dimension_w'				= Width
								'dimension_d'				= Deepth
								'weight'					= Weight of the product
								'count'						= Product count in the warehouse
								'status'					= Product Status
								'rex_onlineshop_tax'		= TAX Id
								'rex_onlineshop_type'		= Type of the product (article, Coupon as example)
								'rex_onlineshop_delivery'	= Type of delivery (digital, mail as example)
								
		Return:		Array of
								'status' = "update" or "new"
								
	*/
	public function setDetailOfProduct($param) {

    	$date = date(DATE_RFC822);

    	$sqlRef = new rex_sql();
	    $sqlRef->setTable("rex_com_products");
	    $sqlRef->value("name", htmlentities($param['name']));
	    $sqlRef->value("description", htmlentities($param['description']));
	    $sqlRef->value("price", htmlentities($param['price']));
	    $sqlRef->value("rex_onlineshop_category", htmlentities($param['rex_onlineshop_category']));
	    $sqlRef->value("size",htmlentities($param['size']));
	    $sqlRef->value("color", htmlentities($param['color']));
	    $sqlRef->value("dimension_h", htmlentities($param['dimension_h']));
	    $sqlRef->value("dimension_w", htmlentities($param['dimension_w']));
	    $sqlRef->value("dimension_d", htmlentities($param['dimension_d']));
	    $sqlRef->value("weight", htmlentities($param['weight']));
	    $sqlRef->value("count", htmlentities($param['count']));
	    $sqlRef->value("status", htmlentities($param['status']));
	    $sqlRef->value("update", $date);
	    $sqlRef->value("rex_onlineshop_tax", htmlentities($param['rex_onlineshop_tax']));
	    $sqlRef->value("rex_onlineshop_type", htmlentities($param['rex_onlineshop_type']));
	    $sqlRef->value("rex_onlineshop_delivery", htmlentities($param['rex_onlineshop_delivery']));

	    if (htmlentities($param['id'])) {
    	    $sqlRef->where(sprintf("id = '%s'",htmlentities($param['id'])));
	        $sqlRef->update();
    	    $res['status'] = "update";
	    } else {
    	    $sqlRef->insert();
        	$res['status'] = "new";
	    }

		return $res;
	}


	/*
		Function:		searchProduct
		Description:	search in all products the given value
		Parameters:		$param = Array of
						'search' = Search String
		Return:			Array of the Product List	
	*/
	function searchProduct($param) {
		$param['search'] = htmlentities($param['search']);
		$param['from'] = "";
		$param['limit'] = "";
		$param['cat'] = "";

		$products = $this->getProductsList($param);
		$search = "";
		$count = count($products);
		$x = 0;

		for ($i = 0; $i <= $count; $i++) {
			$category = $this->getCategoryValue($products[$i]['rex_onlineshop_category']);
			if (stristr($products[$i]['description'], $param['search']) || stristr($products[$i]['name'], $param['search']) || stristr($category, $param['search']) ) {
				$search[$x] = $products[$i];
				$x++;
			}
		}
	
		return $search;		
	}

	public function langReplace($string) {
    	return preg_replace("/###([a-z]\w+)###/e", '$this->lang->msg(htmlentities($1))', $string);
	}



}

