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
	$param['cat']  = htmlentities(rex_request("cat","integer", 1));

	$navButton = htmlentities(rex_request("navButton", "string", ""));
	


	# Get Products
	$products = $oshop->getProductsList($param);




	# Print the result
	print '<div id="productList">';
	print '<div id="debug">'.$param['from'].'</div>';
	print '<div id="debug">'.$param['limit'].'</div>';
	print '<div id="debug">'.$param['cat'].'</div>';
	print '<div id="debug">'.$navButton.'</div>';

	$i=0;
	foreach( $products as  $product ) {
		print '<div id="productItem_'.$i.'">';
		print '<div id="productName_'.$i.'">'.$product['0']['name'].'</div>';	
		print '<div id="productThumbnail_'.$i.'">thumbnail</div>';	
		print '</div>';
		
		$i++;
	}

	print '</div>';

	print '<div id="navButtons">';
	$nextVal=$param['from']+$param['limit'];
	$prevVal=$param['from']-$param['limit'];
	print '<div id="prevButton"><a href="'.$_SERVER['PHP_SELF'].'?from='.$prevVal.'">Previous</a>';
	print '<div id="nextButton"><a href="'.$_SERVER['PHP_SELF'].'?from='.$nextVal.'">Next</a>';
	print '</div>';

?>


</div>
