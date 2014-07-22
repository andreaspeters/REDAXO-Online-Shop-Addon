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
	<form action="show-products.php" method="get">
<?php
	define('DEFAULT_LIMIT',    20);
	define('DEFAULT_CATEGORY', 1);
	define('DEFAULT_FROM',     0);

	$oshop = new OOOnlineShop();

	
	$param['from']  = $_GET['from']  ?:  constant('DEFAULT_FROM');
	$param['limit'] = $_GET['limit'] ?:  constant('DEFAULT_LIMIT');
	$param['cat']   = $_GET['cat']   ?:  constant('DEFAULT_CATEGORY');

	$products = $oshop.getProductsList($param);


	print '<div id="productList">';

	$i=0;
	foreach( $products as  $products ) {
		print '<div id="productItem__'.$i'">';
		print '<div id="productName_'.$i.'">'.$product['0']['name'].'</div>';	
		print '<div id="productThumbnail_'.$i.'">'.$product['image'].'</div>';	
		print '</div>';
		
		$i++;
	}

	print '</div>';

?>
		<div id="buttons">
			<input type="submit" name="btn[next]" value="Next Page" />
			<input type="submit" name="btn[prev]" value="Previous Page" />
		</div>
<?php
	}
?>
	</form>
</div>