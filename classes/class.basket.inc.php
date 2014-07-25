<?php

class OOBasket {
    
    public function OOBasket () {
        $basket = array();
        if(!isset($_SESSION['basket'])) {
            $_SESSION['basket']=$basket;
        } 
    }
    
	/*
		Function:		insertProduct(
		Description:	Insert a product into the basket
		Parameters:		$param = Array of 
									'id'    = Product ID
									'count' = How much the user want to buy the product
		Return:		 	-1 = Error, 0 = Good	
	*/
    public function insertProduct($param) {
		$id    = htmlentities($param['id']);
		$count = htmlentities($param['count']);
		if (!$id) 
			return -1;

		// if product already inside, then increse the count
		$pos = $this->getPosition($id);
		if ($pos > -1) {
			$this->incProduct($param);
		} else {
        	$product = array($id, $count);
	        $basket = $_SESSION['basket'];
	        array_push($basket, $product);
    	    $_SESSION['basket'] = $basket;
		}
		return 0;
    }


	/*
		Function:		deleteProduct(
		Description:	Delete a product from the basket
		Parameters:		$param = Array of 
									'id'    = Product ID									
		Return:		 	-1 = Error, 0 = Good	
	*/
    public function deleteProduct($param) {
		$id  = htmlentities($param['id']);
		if (!$id)
			return -1;

        $array = $_SESSION['basket'];
		$pos = $this->getPosition($id);
		unset($array[$pos]);
		$_SESSION['basket'] = $array;
		return 0;
    }

    
	/*
		Function:		getBasket
		Description:	Get out all product of the basket
		Parameters:	 	none	
		Return:			All products as array
	*/
    public function getBasket() {
        return $_SESSION['basket'];
    }
    
    
	/*
		Function:		deleteBasket
		Description:	Delete the basket
		Parameters:
		Return:
	*/
    public function deleteBasket() {
        $_SESSION['basket'] = array();
    }
    
   	 
	/*
		Function:		getCountOfProducts
		Description:	Get out the count of all products
		Parameters:
		Return:
	*/
    public function getCountOfProducts() {
        return count($_SESSION['basket']);
    }

	/*
		Function:		decProduct
		Description:	decrese the product count
		Parameters:		$param = Array of
								'id' = Product id to decrese
		Return:			-1 = Error, Count = The current count
	*/
    public function decProduct($param) {
		$id = htmlentities($param['id']);
		if (!$id) 
			return -1;
		// Get the postion
        $array = $_SESSION['basket'];
		$pos = $this->getPosition($id);
		if ($pos == -1) 
			return -1;
		// Increse the count of the product
		$array[$pos][1]--;
		$_SESSION['basket'] = $array;
		return $array[$pos][1];
    }

	/*
		Function:		incProduct
		Description:	increse the product count
		Parameters:		$param = Array of
								'id' = Product id to increse
		Return:			-1 = Error, Count = The current count
	*/
    public function incProduct($param) {
		$id = htmlentities($param['id']);
		if (!$id)
			return -1;
		
		// Get the postion
        $array = $_SESSION['basket'];
		$pos = $this->getPosition($id);
		if ($pos == -1) 
			return -1;
		// Increse the count of the product
		$array[$pos][1]++;
		$_SESSION['basket'] = $array;
		return $array[$pos][1];
    }

	/*
		Function:		getPosition
		Description:	get the position of a product in the array
		Parameters:		$id = Integer value of the products id							
		Return:
	*/
    private function getPosition($id) {
        $array = $_SESSION['basket'];
        $y = 0;
		foreach ($array as $i) {
			if ($i[0] == $id) {
				return $y;
			}
			$y++;
		}

		return -1;

    }
}

?>
