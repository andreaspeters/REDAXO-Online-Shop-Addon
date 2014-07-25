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
	<form action="order.php" method="post">
<?php

	$oshop = new OOOnlineShop();

	$currentArticel = rex_getUrl($this->getValue("article_id"));
	$func = htmlentities(rex_request("func","string",""));
	$param = json_decode(stripcslashes(rex_request("param", "string")), true);

	switch($func) {
		case "removeProductFromBasket": $oshop->basket->deleteProduct($param); break;
	}


	$basket = $oshop->basket->getBasket();

	if ($basket) {
		$y = 0;
		foreach ($basket as $i) {
			$param['id'] = $i[0];

			$product = $oshop->getDetailOfProduct($param);
			$tax = $oshop->getTaxValue($product['rex_onlineshop_tax']);
			$price = str_replace(',','.',$product['0']['price']);
		
			// Get Brutto price
			$brPrice = ($price / 100) * $tax + $price;

			// The total amount
			$totalAmountNetto  += $price;
			$totalAmountBrutto += $brPrice;
			$totalAmountTax    += ($brPrice - $price);

			print '<div id="productItem_'.$y.'">';
			print '   <div id="productName_'.$y.'">'.$product['0']['name'].'</div>';
			print '   <div id="productPrice_'.$y.'">'.$brPrice.'</div>';
			print '   <div id="productTax_'.$y.'">'.$tax.'</div>';
			print '   <div id="productCount_'.$y.'"><input type="number" name="productCount_'.$y.'" min="0" value="'.$i[1].'" max="100"></div>';
			print '   <div id="remove_'.$y.'"><a href=\''.$currentArticel.'&func=removeProductFromBasket&param={"id":'.$i[0].'}\'><span icon="removeProduct">X</span></a>';
			print '</div>';
			

			$y++;
		}
?>
		<div id="costs">
			<div id="totalAmountNetto"><?php echo $totalAmountNetto ?></div>
			<div id="totalAmountBrutto"><?php echo $totalAmountBrutto ?></div>
			<div id="totalAmountTax"><?php echo $totalAmountTax ?></div>
		</div>

		<div id="buttons">
			<input type="submit" name="btn[order]" value="###zahlungspflichtigbestellen###" />
			<input type="submit" name="btn[update]" value="###update###" />
		</div>
<?php
	}
?>
	</form>
</div>
