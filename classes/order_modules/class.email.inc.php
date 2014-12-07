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
    
    public function OOOrderEMail () {
    }

	/*
		Function:		createInvoice
		Description:	This function should create the invoice for the customer
		Parameters:		$param = Array of all Products
		Return:			Invoice ID
	*/
	public function createInvoice() {
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
		$subject="Order for $login";
		$header="From: noreply@aventer.biz\n";
		$mail_body ="Date " . date("d.m.Y") . " " . date("H:i") . "\n\n";
  		$mail_body.="Please finalize the invoice for the following user:\n\n";
  		$mail_body.="Name: " . $contactName . "\n";
  		$mail_body.="E-Mail: " . $email . "\n\n";
  		$mail_body.="Login: ".$login."\n\n";
  		$mail_body.="Client ID: ".$clientId."\n\n";
  		$mail_body.="Product: ".$product['name']."\n\n";
  		$mail_body.="Domain: ".$domain."\n\n";
  
  		mail($recipient,$subject,$mail_body,$header);
	}

}

?>
