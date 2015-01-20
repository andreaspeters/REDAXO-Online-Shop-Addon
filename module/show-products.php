<?php
/*
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

	Company: avEnter UG (haftungsbeschraenkt)
	www:	 https://www.aventer.biz
	EMail:   support [at] aventer [dot] biz

*/
?>
<div id="productOverview">
<?php

	$oshop = new OOOnlineShop();	
	
	# default values
	$param['from']  = htmlentities(rex_request("from","integer", 0));
	$param['limit']  = htmlentities(rex_request("limit","integer", 20));
	$param['cat']  = htmlentities(rex_request("cat","integer", 0));

	$navButton = htmlentities(rex_request("navButton", "string", ""));
	
	# Get Products
	$products = $oshop->getProductsList($param);



	# Print the result
	print '<div id="productList">';
	$i=0;
	foreach( $products as  $product ) {
		$images = explode(",",$product['images']);
		$param['name'] = $images[0];
		$param['width'] = "120";
		$category = $oshop->getCategoryValue($product['rex_onlineshop_category']);

		$tax =  $oshop->getTaxValue($product['rex_onlineshop_tax']);
		$price = str_replace(',','.',$product['price']);


		// If the product have no price, then it is for free
		if (!$price) {
			$brprice = "###forfree###";
		} else {
			$brprice = ($price / 100) * $tax + $price .'###currency###';
			$brprice = sprintf("%01.2f", $brprice);
		}

		print '<div id="productItem_'.$i.'">';
		print '<div id="productThumbnail_'.$i.'">'.$oshop->getImageByName($param).'</div>';
		print '<div id="productName_'.$i.'"><a href="index.php?article_id=24&id='.$product['id'].'">'.$product['name'].'</a></div>';	
		print '<div id="productPrice_'.$i.'">'.$brprice.'</div>';	
		print '<div id="productCategory_'.$i.'">'.$category.'</div>';	
		print '</div>';
		
		$i++;
	}

	print '</div>';



	# add navigation
	print '<div id="navButtons">';
	$nextVal=$param['from']+$param['limit'];
	$prevVal=$param['from']-$param['limit'];
	$cat=$param['cat'];
	if($prevVal < 0) 
		$prevVal=0;
	else
		if($cat>0)
			print '<div id="prevButton"><a href="index.php?article_id=23&from='.$prevVal.'&cat='.$cat.'"><span></span></a></div>';
		else
			print '<div id="prevButton"><a href="index.php?article_id=23&from='.$prevVal.'"><span></span></a></div>';


	if($cat>0) 	
		print '<div id="nextButton"><a href="index.php?article_id=23&from='.$nextVal.'&cat='.$cat.'"><span></span></a></div>';
	else
		print '<div id="nextButton"><a href="index.php?article_id=23&from='.$nextVal.'"><span></span></a></div>';
	print '</div>';

?>


</div>

