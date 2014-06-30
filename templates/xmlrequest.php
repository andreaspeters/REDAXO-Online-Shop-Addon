<?php

global $REX;


$func   = htmlentities(rex_request("func","string",""));
$param = json_decode(stripcslashes(rex_request("param", "string")), true);

switch ($func) {
	case "getProductsList": getProductsList();break;
	case "getDetailOfProduct": getDetailOfProduct($param);break;
	case "getCouponsList": getCouponsList();break;
	case "setProduct": setProduct();break;
	case "getTypeList": getTypeList();break;
	case "getDeliveryList": getDeliveryList();break;
	case "getCategoryList": getCategoryList();break;
	case "getTaxList": getTaxList();break;
}


function getProductsList() {
	global $REX;
	$sqlRef = new rex_sql();
	$sqlRef->setQuery(sprintf("select * from %s","rex_onlineshop_products"));
    $res['method'] = "getProductsList";
	$res['data'] = $sqlRef->getArray();		
	echo json_encode($res);		
}

function getDetailOfProduct($param) {
	global $REX;
	$productId = htmlentities($param['id']);

	$sqlRef = new rex_sql();
	$sqlRef->setQuery(sprintf("select * from %s where id = '%d'","rex_onlineshop_products", $productId));
    
	$res['method'] = "getDetailOfProduct";
	$res['data'] = $sqlRef->getArray();		
	echo json_encode($res);		
}

function getCouponsList() {
	global $REX;
	$sqlRef = new rex_sql();
	$sqlRef->setQuery(sprintf("select * from %s where type = '2' ","rex_onlineshop_products"));
    $res['method'] = "getCouponsList";
	$res['data'] = $sqlRef->getArray();		
	echo json_encode($res);		
}

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
	global $REX;
	$sqlRef = new rex_sql();
	$sqlRef->setQuery(sprintf("select * from %s","rex_onlineshop_type"));
    $res['method'] = "getTypeList";
	$res['data'] = $sqlRef->getArray();		
	echo json_encode($res);		
}

function getDeliveryList() {
	global $REX;
	$sqlRef = new rex_sql();
	$sqlRef->setQuery(sprintf("select * from %s","rex_onlineshop_delivery"));
    $res['method'] = "getDeliveryList";
	$res['data'] = $sqlRef->getArray();		
	echo json_encode($res);		
}

function getCategoryList() {
	global $REX;
	$sqlRef = new rex_sql();
	$sqlRef->setQuery(sprintf("select * from %s","rex_onlineshop_category"));
    $res['method'] = "getCategoryList";
	$res['data'] = $sqlRef->getArray();		
	echo json_encode($res);		
}

function getTaxList() {
	global $REX;
	$sqlRef = new rex_sql();
	$sqlRef->setQuery(sprintf("select * from %s","rex_onlineshop_tax"));
    $res['method'] = "getTaxList";
	$res['data'] = $sqlRef->getArray();		
	echo json_encode($res);		
}
		
?>
		
			
	
		

