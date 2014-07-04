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
		

?>		
			
	
		

