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
	$orderUrl = "?article_id=130";
	$thisUrlId  = "129";

	$id = rex_request("id", "integer");
	$productSize = rex_request("productSize", "integer");

	$param['id'] = $id;
	$productSizeParam['id'] = $productSize;

	$product = $oshop->getDetailOfProduct($param);
	$tax = $oshop->getTaxValue($product[0]['rex_onlineshop_tax']);
	$price = str_replace(',','.',$product['0']['price']);
	$description = explode("^^^^°°°°",$product[0]['description']);
	$size = $oshop->getChildsOfProduct($param);
	

	// Get out the Payment Method
	switch ($product['0']['rex_onlineshop_type'] == 3) {
		case 3: $paymentTypeText = "###abo_monthly###"; break;
		case 4: $paymentTypeText = "###free###"; break;
		case 5: $paymentTypeText = "###onetime###"; break;
		default: $paymentTypeText = "###onetime###"; break;
	}

		
	// Get Brutto price
	// If a product size was choosen, then use the new price
	if ($productSize) {
		$child = $oshop->getDetailOfProduct($productSizeParam);
		$price = str_replace(',','.',$child['0']['price']);
	}
	$brPrice = ($price / 100) * $tax + $price;
?>

	<div id="productImages">
<?php
	$images = explode(',',$product[0]['images']);
	if (is_array($images)) {
		$y = 0;
		foreach ($images as $i) {
			$param['name'] = $i;
                        $param['height'] = 200;
			$url = $oshop->getImageByName($param);
			print '<div class="productImage_'.$y.'">';
			echo $url;
			print "</div>";
			$y++;
		}
	}
?>
	</div>

	<div id="information">
  	  <div id="productName"><?php echo $product['0']['name']; ?></div>
      <div id="productDescription"><?php echo $description[0]; ?></div>
	
	  <form action="" method="post">
		<input type="hidden" name="article_id" value="<?php echo $thisUrlId; ?>"/>
		<input type="hidden" name="id" value="<?php echo $param['id']; ?>"/>


	    <?php if ($size) { ?>
		  <div id="productSize">
    	    <div class="label">###size###</div>
	        <div class="value">
			  <select name="productSize" id="productSizeSelect">
			    <?php 
			    // Show Product Size and preselect if the customer already select a size
			    foreach ($size as $child) { 
				  	$select = "";
					if ($child['id'] == $productSize) {
						$select="selected";
					}
			     ?>
			  	    <option value="<?php echo $child['id']; ?>" <?php echo $select; ?>><?php echo $child['name']; ?></option>
			     <?php } ?>
 			  </select>
    	    </div>
			<button id="refreshButton" type="submit"></button>
	      </div> 
	    <?php } ?>
	  </form>

	</div>

    <div id="price">
	  <div id="productPrice"><div class="label">###price###</div><div class="value"><?php echo sprintf("%01.2f", $brPrice); ?> ###currency###</div></div>
	  <div id="productTax"><div class="label">###tax###</div><div class="value"><?php echo $tax; ?>%</div></div>

	  <div id="productPaymentType"><div class="label">###payment###</div><div class="value"><?php echo $paymentTypeText ?></div></div>

	  <div id="orderButton">
	    <?php 
			// If a product size was choosen, then add this one to the basked
			if ($productSize) {
				$id = $productSizeParam['id'];
			}
		?>
	    <a href='<?php echo $orderUrl; ?>&func=addProductToBasket&param={"id":<?php echo $id; ?>}'>###tobasked###</a>
	  </div>
	</div>

    <div class="clear"></div>
	

</div>
