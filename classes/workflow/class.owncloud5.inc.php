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

class OOWorkflow_owncloud5 {
	private $customer;
	private	$products;
	private $oshop;

    public function OOWorkflow_owncloud5 () {
		$this->oshop = new OOOnlineShop();
    }

    /*
        Function:       setCustomer
        Description:    This function set the customer information
        Parameters:     $customer = Array of all Customer informations
                        $products = Array of all Products
    */
    public function setCustomer($customer, $products) {
        $this->customer = $customer;
        $this->products = $products;
    }


    /*
        Function:       getOrderFormular 
        Description:    This function get out special order formular items for this product
		return:			XFORM Order Formular
    */
	public function getOrderFormular() {
		return 'text|firstname| ###filalsdjlajsdlkjrstname###:*|';
	}


}

?>
