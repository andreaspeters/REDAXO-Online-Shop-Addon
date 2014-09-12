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
		print '<div id="productItem_'.$i.'">';
		print '<div id="productName_'.$i.'"><a href="http://'.$_SERVER['SERVER_NAME'].'/24-0-Product-Details.html?article_id='.$product['id'].'">'.$product['name'].'</a></div>';	
		print '<div id="productPrice_'.$i.'">'.$product['price'].'</div>';	
		print '<div id="productCat_'.$i.'">'.$product['rex_onlineshop_category'].'</div>';	
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
			print '<div id="prevButton"><a href="http://'.$_SERVER['SERVER_NAME'].'/4-0-O-Shop.html?from='.$prevVal.'&cat='.$cat.'">Previous</a></div>';
		else
			print '<div id="prevButton"><a href="http://'.$_SERVER['SERVER_NAME'].'/4-0-O-Shop.html?from='.$prevVal.'">Previous</a></div>';


	if($cat>0) 	
		print '<div id="nextButton"><a href="http://'.$_SERVER['SERVER_NAME'].'/4-0-O-Shop.html?from='.$nextVal.'&cat='.$cat.'">Next</a></div>';
	else
		print '<div id="nextButton"><a href="http://'.$_SERVER['SERVER_NAME'].'/4-0-O-Shop.html?from='.$nextVal.'">Next</a></div>';
	print '</div>';

?>


</div>
