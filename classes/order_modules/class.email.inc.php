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

class OOOrderEMail {
	private $customer;
	private	$products;
	private $oshop;

    public function OOOrderEMail () {
		$this->oshop = new OOOnlineShop();
    }

	/*
		Function:		createInvoice
		Description:	This function should create the invoice for the customer
		Parameters:		$customer = Array of all Customer informations
						$products= Array of all Products
		Return:			Invoice ID
	*/
	public function createInvoice($customer, $products) {
		$this->customer = $customer;
		$this->products = $products;

		$this->sendOrderToWarehouse();
	}


	/*
		Function:		sendInvoiceToCustomer
		Description:	This function should send the createt Invoice to the customer
		Parameters:		Invoice ID
		Return:			none
	*/
	public function sendInvoiceToCustomer() {
	}


	/*
		Function:		sendOrderToWarehouse
		Description:	This function should send the order to the warehouse
		Parameters:		Invoice ID
		Return:			none
	*/
	public function sendOrderToWarehouse() {
  		$recipient="mailbox@andreas-peters.net";
		$subject = "Bestellung fuer: " . $this->customer['firstname'] . " " . $this->customer['name'];
		$header = "From: noreply@baltic-turbo-boost.com\n";
		$mail_body  = "Date " . date("d.m.Y") . " " . date("H:i") . "\n\n";
  		$mail_body .= "Please finalize the invoice for the following user:\n\n";
  		$mail_body .= "Name: " . $this->customer['firstname'] . " " . $this->customer['name'] . "\n";
  		$mail_body .= "E-Mail: " . $this->customer['email'] . "\n";
		$mail_body .= "Strasse und Hausnummer: " . $this->customer['street'] . "\n";
		$mail_body .= "Plz und Stadt: " . $this->customer['plz'] . " " . $this->customer['city'] . "\n\n";

  
		foreach ($this->products as $i) {
			$param['id'] = $i[0];
			$detail = $this->oshop->getDetailOfProduct($param);

			$mail_body .= "Product ------ ".$detail[0]['name']." ------- \n";
			$mail_body .= "Product ID: ".$i[0]."\n";
			$mail_body .= "Anzahl: ".$i[1]."\n";
			$mail_body .= "\n\n";

		}

  		mail($recipient,$subject,$mail_body,$header);
	}

}

?>
