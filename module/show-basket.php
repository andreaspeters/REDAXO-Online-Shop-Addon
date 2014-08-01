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
<div id="basket">
	<form action="" method="post">
<?php

	$oshop = new OOOnlineShop();
	$order = new OOOrder();

	$currentArticel = rex_getUrl($this->getValue("article_id"));
	$func = htmlentities(rex_request("func","string",""));
	$param = json_decode(stripcslashes(rex_request("param", "string")), true);

	switch($func) {
		case "removeProductFromBasket": $oshop->basket->deleteProduct($param); break;
		case "order": $order->showOrderFormular(); break;
	}


	$basket = $oshop->basket->getBasket();

	if ($basket) {
		$y = 0;
		foreach ($basket as $i) {
			$param['id'] = $i[0];

			$product = $oshop->getDetailOfProduct($param);
			$tax = $oshop->getTaxValue($product[0]['rex_onlineshop_tax']);
			$price = str_replace(',','.',$product['0']['price']);
		
			// Get Brutto price
			$brPrice = ($price / 100) * $tax + $price;

			// The total amount
			$totalAmountNetto  += $price;
			$totalAmountBrutto += $brPrice;
			$totalAmountTax    += ($brPrice - $price);

			print '<div id="productItem_'.$y.'">';
			print '   <div id="productName_'.$y.'">'.$product['0']['name'].'</div>';
			print '   <div id="productPrice_'.$y.'">'.sprintf("%01.2f", $brPrice).' ###currency###</div>';
			print '   <div id="productTax_'.$y.'">'.$tax.'%</div>';

			// Show the remove an count field only if the user didn't pressed the order button
			if ($func != "order") {
				print '   <div id="productCount_'.$y.'"><input type="number" name="productCount_'.$y.'" min="0" value="'.$i[1].'" max="100"></div>';
				print '   <div id="remove_'.$y.'"><a href=\''.$currentArticel.'&func=removeProductFromBasket&param={"id":'.$i[0].'}\'><span icon="removeProduct">X</span></a>';
			}
			print '</div>';
			

			$y++;
		}
?>
		<div id="costs">
			<div id="totalAmountNetto"><div class="title">###totalamountnetto###</div><div class="value"><?php printf("%01.2f", $totalAmountNetto) ?> ###currency###</div></div>
			<div id="totalAmountBrutto"><div class="title">###totalamountbrutto###</div><div class="value"><?php printf("%01.2f", $totalAmountBrutto) ?> ###currency###</div></div>
			<div id="totalAmountTax"><div class="title">###totalamounttax###</div><div class="value"><?php printf("%01.2f", $totalAmountTax) ?> ###currency###</div></div>
		</div>
		
		<input type="hidden" name="func" value="order"/>

<?php
		// Show the order button only before it was pressed
		if ($func != "order") {
?>

			<div id="buttons">
				<input type="submit" value="###order###" />
			</div>
<?php
		}
	}



?>
	</form>
</div>
