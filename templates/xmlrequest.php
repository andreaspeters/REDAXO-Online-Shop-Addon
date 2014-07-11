<?php

$func   = htmlentities(rex_request("func","string",""));

date_default_timezone_set('UTC');

$param = json_decode(stripcslashes(rex_request("param", "string")), true);

switch ($func) {
	case "getProductsList": getProductsList($param);break;
	case "getDetailOfProduct": getDetailOfProduct($param);break;
	case "getCouponsList": getCouponsList();break;
	case "setProduct": setProduct();break;
	case "getTypeList": getTypeList();break;
	case "getDeliveryList": getDeliveryList();break;
	case "getCategoryList": getCategoryList();break;
	case "getTaxList": getTaxList();break;
	case "getBasket": getBasket();break;
	case "addProductToBasket": addProductToBasket($param);break;
	case "removeProductFromBasket": removeProductFromBasket($param);break;
	case "getCountOfBasket": getCountOfBasket($param);break;
	case "incBasketProduct": incBasketProduct($param);break;
	case "decBasketProduct": devBasketProduct($param);break;
	case "deleteBasket": deleteBasket();break;
}


function getProductsList() {
	$oshop = new OOOnlineShop();
    $res['method'] = "getProductsList";
	$res['from'] = htmlentities($param['from']);
	$res['limit'] = htmlentities($param['limit']);
	$res['cat'] = htmlentities($param['cat']);
	$res['data'] = $oshop->getProductsList($param);
	echo json_encode($res);		
}

function getDetailOfProduct($param) {
    $oshop = new OOOnlineShop();
	$res['method'] = "getDetailOfProduct";
	$res['data'] = $oshop->getDetailOfProduct($param);
	echo json_encode($res);		
}

function getCouponsList() {
    $oshop = new OOOnlineShop();
    $res['method'] = "getCouponsList";
	$res['data'] = $oshop->getCouponsList();
	echo json_encode($res);		
}
/////// DUMMY
function setProduct($param) {
return;
	global $REX;
	
	$date = date(DATE_RFC822);
    
	$res['method'] = "setProduct";	
	
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
		
	echo json_encode($res);		
}



///// DUMMY
function setProductSold($param) {
return;
	global $REX;
	$sqlRef = new rex_sql();
	
	$date = date(DATE_RFC822);

	$sqlRef->setTable("rex_com_sold");
	$sqlRef->value("productid", $param['id']);
	$sqlRef->value("name", $param['name']);
	$sqlRef->value("description", $param['description']);
	$sqlRef->value("price", $param['price']);
	$sqlRef->value("rex_com_category", $param['rex_onlineshop_category']);
	$sqlRef->value("size", $param['size']);
	$sqlRef->value("color", $param['color']);
	$sqlRef->value("dimension_h", $param['dimension_h']);
	$sqlRef->value("dimension_w", $param['dimension_w']);
	$sqlRef->value("dimension_d", $param['dimension_d']);
	$sqlRef->value("weight", $param['weight']);
	$sqlRef->value("solddata", $date);
	$sqlRef->value("rex_onlineshop_tax", $param['rex_onlineshop_tax']);
	$sqlRef->value("rex_onlineshop_type", $param['rex_onlineshop_type']);
		
	$sqlRef->insert();
		
    $res['method'] = "setProductSold";	
	$res['status'] = "new";
	echo json_encode($res);		
}


function getTypeList() {
    $oshop = new OOOnlineShop();
    $res['method'] = "getTypeList";
	$res['data'] = $ooshop->getTypeList();		
	echo json_encode($res);		
}

function getDeliveryList() {
    $oshop = new OOOnlineShop();
    $res['method'] = "getDeliveryList";
	$res['data'] = $oshop->getDeliveryList();		
	echo json_encode($res);		
}

function getCategoryList() {
    $oshop = new OOOnlineShop();
    $res['method'] = "getCategoryList";
	$res['data'] = $oshop->getCategoryList();		
	echo json_encode($res);		
}

function getTaxList() {
    $oshop = new OOOnlineShop();
    $res['method'] = "getTaxList";
	$res['data'] = $oshop->getTaxList();		
	echo json_encode($res);		
}

/*
	Function:		getBasket
	Description:	get out all product of the basket
	Parameters:
	Return:
*/
function getBasket() {
    $oshop = new OOOnlineShop();
    $res['method'] = "getBasket";
	$res['data'] = $oshop->basket->getBasket();		
	echo json_encode($res);		
}

/*
	Function:		addProductToBasket
	Description:	add a product to the basket
	Parameters:		$param = Array of
								'id' = Product Id
								'count' = How many of the product (optional)
	Return:
*/
function addProductToBasket($param) {
	// One item of the product should be inside
	if (!$param['count']) {
		$param['count'] = 1;
	}

    $oshop = new OOOnlineShop();
    $res['method'] = "addProductToBasket";
	$res['error'] = $oshop->basket->insertProduct($param);
	echo json_encode($res);		
}

/*
	Function:		removeProductFromBasket
	Description:	remove a product from the basket
	Parameters:		$param = Array of
								'id' = Product Id
	Return:
*/
function removeProductFromBasket($param) {
    $oshop = new OOOnlineShop();
    $res['method'] = "removeProductFromBasket";
	$res['error'] = $oshop->basket->deleteProduct($param);
	echo json_encode($res);		
}
		
/*
	Function:		getCountOfBasket	
	Description:	get the count of product in the basket
	Parameters:		
	Return:			
*/
function getCountOfBasket($param) {
    $oshop = new OOOnlineShop();
    $res['method'] = "getCountOfBasket";
	$res['data'] = $oshop->basket->getCountOfProducts();
	echo json_encode($res);		
}

/*
	Function:		incBasketProduct	
	Description:	increment the count of a product
	Parameters:		$param = Array of
								'id' = Product Id
	Return:			
*/
function incBasketProduct($param) {
    $oshop = new OOOnlineShop();
    $res['method'] = "incBasketProduct";
	$val = $oshop->basket->incProduct();
	if ($val == -1) {
		$res['error'] = $val;
	} else {
		$res['data'] = $val;
	}
	echo json_encode($res);		
}

/*
	Function:		decBasketProduct	
	Description:	decrement the count of a product
	Parameters:		$param = Array of
								'id' = Product Id
	Return:			
*/
function decBasketProduct($param) {
    $oshop = new OOOnlineShop();
    $res['method'] = "decBasketProduct";
	$val = $oshop->basket->decProduct();
	if ($val == -1) {
		$res['error'] = $val;
	} else {
		$res['data'] = $val;
	}
	echo json_encode($res);		
}

/*
	Function:		deleteBasket	
	Description:	delete all the basket items
	Parameters:		
	Return:			
*/
function deleteBasket() {
    $oshop = new OOOnlineShop();
    $res['method'] = "deleteBasket";
	$res['data'] = $oshop->basket->deleteBasket();
	echo json_encode($res);		
}
?>		
			
	
		

