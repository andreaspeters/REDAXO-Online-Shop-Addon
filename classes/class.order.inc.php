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

class OOOrder {
	private $order;
	private $customer, $products;
    
    public function OOOrder() {
		$this->order = new OOOrderEmail();		
    }


	/*
		Function:		createInvoice
		Description:	This function should create the invoice for the customer
		Parameters:		
		Return:			Invoice ID
	*/
	public function createInvoice() {
		$this->order->createInvoice($this->customer, $this->products);
	}


	/*
		Function:		sendInvoiceToCustomer
		Description:	This function should send the createt Invoice to the customer
		Parameters:		
		Return:			none
	*/
	public function sendInvoiceToCustomer() {
		$this->order->sendInvoiceToCustomer($this->id);
	}


	/*
		Function:		sendOrderToWarehouse
		Description:	This function should send the order to the warehouse
		Parameters:		
		Return:			none
	*/
	public function sendOrderToWarehouse() {
		$this->order->sendOrderToWarehouse($this->id);
	}


	public function showOrderFormular() {
		$xform = new rex_xform;
		$form_data = <<<EOT
			text|firstname|###firstname###:*|
			text|name|###name###:*|
			text|street|####street_no####:*|
			text|plz|####zip####:*|
			text|city|####city####:*|
			text|email|###email###:*|
			radio|payment|###payment###:*|###invoice###²|[no_db]|

			checkbox|agb|###acceptagb###|0|0|no_db
			checkbox|privacy|###acceptprivacy###|0|0|no_db
			validate|empty|firstname|###enterfirstname###
			validate|empty|name|###entername###
			validate|preg_match|firstname|/[a-zA-Z]*/i|###firstnamefailure1###
			validate|preg_match|name|/[a-zA-Z ]*/i|###namefailure1###
			validate|empty|email|###enteremail###
			validate|email|email|###emailfailure1###
			validate|compare_value|agb|0|###agbfailure1###
			validate|compare_value|privacy|0|###privacyfailure1###
			submit||###zahlungspflichtigbestellen###|no_db
			
			hidden|func|order|
  
EOT;


		$form_data = trim(str_replace("<br/>","",rex_xform::unhtmlentities($form_data)));
		$xform->setFormData($form_data);

		$form = $xform->getForm();

		if ($form == '') {
			$basket = new OOBasket();
			// Customer finished the order. Ready to create the invoice and delete the basket
		    $this->customer = $xform->objparams['value_pool']['email'];
			$this->products = $basket->getBasket();
			$this->createInvoice();
			$basket->deleteBasket();
		} else {
		    // show form
		    echo $form;
?>
			<div id="payfoodnote">
				<p>
					1) ###includevat### 
				</p>
				<p>
					2) ###paymentvia### 
				</p>
				<p>
					###valuehaveto### 
				</p>
			</div>
<?php
		}
	}

}

?>
