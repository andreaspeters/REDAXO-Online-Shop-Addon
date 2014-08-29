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
<div id="product">
<?php

	$oshop = new OOOnlineShop();
	$orderUrl = "?article_id=23";

	$id = rex_request("id", "string");

	$param['id'] = $id;

	$product = $oshop->getDetailOfProduct($param);
	$tax = $oshop->getTaxValue($product[0]['rex_onlineshop_tax']);
	$price = str_replace(',','.',$product['0']['price']);

		
	// Get Brutto price
	$brPrice = ($price / 100) * $tax + $price;
?>

	<div id="information">
  	  <div id="productName"><?php echo $product['0']['name']; ?></div>
      <div id="productDescription"><?php echo $product[0]['description']; ?></div>
	</div>

	<div id="productImages">
<?php
	$images = explode(',',$product[0]['images']);
	if (is_array($images)) {
		$y = 0;
		foreach ($images as $i) {
			$param['name'] = $i;
			$url = $oshop->getImageByName($param);
			print '<div class="productImage_'.$y.'">';
			echo $url;
			print "</div>";
			$y++;
		}
	}
?>
	</div>

    <div id="price">
	  <div id="productPrice"><?php echo sprintf("%01.2f", $brPrice); ?> ###currency###</div>
	  <div id="productTax"><?php echo $tax; ?>%</div>
	</div>

	<div id="orderButton">
	  <a href="<?php echo $orderUrl; ?>&func=addProductToBasket&param={\"id\":<?php echo $id; ?>}">###tobasked###</a>
	</div>

	

</div>
