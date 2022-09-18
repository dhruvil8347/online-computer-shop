<?php
try{
	session_start();
	if(!isset($_SESSION['customer_id']))
		header('Location: account/login.php');

	require ('connection.php');
	$customer_id=$_SESSION['customer_id'];
	
	//add to cart
	if( isset($_GET['action']) && $_GET['action']=='add' ){
		
		$product_id=$_GET['id'];
		$q=$db->prepare("SELECT * FROM cart WHERE product_id=? AND customer_id=?;");
		$q->execute([$product_id,$customer_id]);
		$result=$q->fetch();
		if( !$result ){
			$q1=$db->prepare("INSERT INTO cart VALUES(NULL,?,?,?,?);");
			$q1->execute([$product_id,$customer_id,1,date('Y-m-d H:i:s')]);
		}

		if(!isset($_SESSION['success']))
			$_SESSION['success']='Product is successfully added to the cart !!';

		header('Location: index.php');
	}

	//increase quantity in cart
	elseif( isset($_GET['action']) && $_GET['action']=='iqty' ){
		
		$product_id=$_GET['id'];
		$q=$db->prepare("SELECT quantity FROM products WHERE id=?;");
		$q->execute([$product_id]);
		$product=$q->fetch();

		$q=$db->prepare("SELECT * FROM cart 
						 WHERE product_id=? AND customer_id=?;");
		$q->execute([$product_id,$customer_id]);
		$cart=$q->fetch();
		
		if( $cart ){		
			if($cart['quantity'] == $product['quantity']){
				if(!isset($_SESSION['error']))
					$_SESSION['error']='This Product is now out of stock';
			}
			else{
				$q=$db->prepare("UPDATE cart SET quantity=? 
										WHERE id=?");
				$q->execute( [$cart['quantity']+1,$cart['id']] );
			}
		}

		header('Location: cart.php#'.$product_id);	
	}

	//decrease quantity in cart
	elseif( isset($_GET['action']) && $_GET['action']=='dqty' ){
		
		$product_id=$_GET['id'];
		$q=$db->prepare("SELECT * FROM cart 
			             WHERE product_id=? AND customer_id=?;");
		$q->execute([$product_id,$customer_id]);
		$cart=$q->fetch();
		if( $cart ){
			$q=$db->prepare("UPDATE cart SET quantity=? WHERE id=?");
			$q->execute( [$cart['quantity']-1,$cart['id']] );
			if($cart['quantity']==1){
				$q=$db->prepare("DELETE FROM cart WHERE id=?;");
				$q->execute([$cart['id']]);
			}
		}

		header('Location: cart.php#'.$product_id);
	}

	//remove book from cart
	elseif( isset($_GET['action']) && $_GET['action']=='remove' ){
		
		$product_id=$_GET['id'];
		$customer_id=$_SESSION['customer_id'];

		$q=$db->prepare("DELETE FROM cart WHERE product_id=? AND customer_id=?;");
		$q->execute([$product_id,$customer_id]);

		header('Location: cart.php#'.$product_id);	
	}

	//cancel order
    elseif( isset($_GET['action']) && $_GET['action']=='cancel' ){

        $order_id=$_GET['id'];

        $q=$db->prepare("SELECT * FROM orders WHERE id=?;");
        $q->execute([$order_id]);
        $order=$q->fetch();

        if(!$order['order_status']){
            $q=$db->prepare("SELECT quantity FROM products WHERE id=?;");
            $q->execute([$order['product_id']]);
            $product=$q->fetch();

            $q=$db->prepare("UPDATE products SET quantity=? WHERE id=?;");
            $q->execute([$product['quantity']+$order['quantity'],
                        $order['product_id']]);

            $q=$db->prepare("DELETE FROM orders WHERE id=?;");
            $q->execute([$order_id]);
        }
    	
    	header("Location: orders.php");
    }


	$db=null;
}
catch(PDOException $e){
	echo $e->getMessage();
	die();
}

?>