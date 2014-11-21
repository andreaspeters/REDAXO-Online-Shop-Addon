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

		// If the product have no price, then it is for free
		if (!$product['price']) {
			$price = "###forfree###";
		} else {
			$price = $product['price'].'###currency###';
		}

		print '<div id="productItem_'.$i.'">';
		print '<div id="productThumbnail_'.$i.'">'.$oshop->getImageByName($param).'</div>';
		print '<div id="productName_'.$i.'"><a href="?article_id=25&id='.$product['id'].'">'.$product['name'].'</a></div>';	
		print '<div id="productPrice_'.$i.'">'.$price.'</div>';	
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
			print '<div id="prevButton"><a href="?article_id=4&from='.$prevVal.'&cat='.$cat.'"><span></span></a></div>';
		else
			print '<div id="prevButton"><a href="?article_id=4&from='.$prevVal.'"><span></span></a></div>';


	if($cat>0) 	
		print '<div id="nextButton"><a href="?article_id=4&from='.$nextVal.'&cat='.$cat.'"><span></span></a></div>';
	else
		print '<div id="nextButton"><a href="?article_id=4&from='.$nextVal.'"><span></span></a></div>';
	print '</div>';

?>


</div>

