<?php

try{
	session_start();
	if(!isset($_SESSION['customer_id']))
		header('Location: account/login.php');
	require ('connection.php');
	$customer_id=$_SESSION['customer_id'];

	$q=$db->prepare("SELECT * FROM cart WHERE customer_id=?;");
	$q->execute([$customer_id]);
	$all_carts=$q->fetchAll();

	if(!$all_carts){
		header("Location: index.php");
	}

	$quantity=array();
	$all_products=array();
	
	foreach($all_carts as $cart){
		$q=$db->prepare("SELECT * FROM products WHERE id=?;");
		$q->execute([$cart['product_id']]);
		$quantity[$cart['product_id']]=$cart['quantity'];
		$all_products[]=$q->fetch(PDO::FETCH_ASSOC);
	}

	if(isset($_POST['placeorder'])){

		$firstname=$_POST['firstname'];
		$lastname=$_POST['lastname'];
		$phoneno=$_POST['phoneno'];
		$pincode=$_POST['pincode'];
		$address=$_POST['address'];

		if(isset($_POST['save'])){
			$q=$db->prepare("UPDATE customer 
				SET first_name=?,last_name=?,phone_no=?,pincode=?,address=? 
				WHERE id=?;");
			$q->execute([$firstname,$lastname,$phoneno,$pincode,$address,$customer_id]);
		}

		foreach($all_products as $product){

			$q=$db->prepare("UPDATE products SET quantity=? WHERE id=?;");
			$q->execute([$product['quantity']-$quantity[$product['id']],$product['id']]);
			$q=$db->prepare("INSERT INTO orders 
							VALUES(NULL,?,?,?,?,?,NULL,0);");
			$q->execute([$product['id'],$customer_id,$product['price'],$quantity[$product['id']],date('Y-m-d')]);
		}

		$q=$db->prepare("DELETE FROM cart WHERE customer_id=?;");
		$q->execute([$customer_id]);

		if(!isset($_SESSION['success_order']))
			$_SESSION['success_order']="Your order is placed successfully...Check Here";

		header("Location:orders.php");

	}
	else{

		$total_price=0;
		$q=$db->prepare("SELECT * FROM customer WHERE id=?;");
		$q->execute([$customer_id]);
		$customer=$q->fetch();
	
	}
	
	$db=null;
}
catch(PDOException $e){
	echo $e->getMessage();
	die();
}

?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Checkout</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
</head>
<body>
<?php require ('navbar.php'); ?>
<div class="container">

	<div class="row">
		<div class="col-md-6 p-2 my-5">
			
			<hr class="text-primary">
			
			<h4 class="d-inline-block">Order Details</h4>
			<a class="btn btn-primary float-end" href="cart.php">Update</a>
			<button class="btn btn-primary float-end mx-2" data-bs-toggle="modal" data-bs-target="#checkoutform">Confirm</button>
			
			<hr class="text-primary"> 

			<ul class="list-group">
			<?php foreach($all_products as $product){
					$total_price+=$product['price']*$quantity[$product['id']]; ?>
  				<li class="list-group-item d-flex justify-content-between align-items-center">
    				<?php echo $product['name']; ?>
    				<span class="badge bg-primary rounded-pill">
    					<?php echo $quantity[$product['id']]; ?>
    				</span>
  				</li>
  			<?php } ?>
			</ul>	
		</div>
		<div class="col-md-6 p-5 my-2">
			<hr class="text-primary">
			<h4 class="mb-4">Price Details</h4>
			<hr class="text-primary">
			<table class="w-100">
				<tr>
					<th class="float-start my-2">Total Amount</th>
					<td class="float-end">
						&#x20B9;<?php echo $total_price ?>		
					</td>
				</tr>
				<tr>
					<th class="float-start my-2">Delivery Charge</th>
					<td class="float-end text-success">Free</td>
				</tr>
				<tr>
					<th class="float-start my-2">Amount To Pay</th>
					<td class="float-end">
						&#x20B9;<?php echo $total_price ?>
					</td>
				</tr>
				<tr>
					<th colspan="2" class="my-2">Delivery in 4 Days</th>
				</tr>
			</table>
		</div>
	</div>

</div>

<div class="modal fade" id="checkoutform" tabindex="-1" aria-labelledby="checkout" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkout">Checkout Form</h5>
            </div>
			<form action="checkout.php" method="POST" class="row g-3 p-3">
				
				<div class="col-md-6">
					<label for="firstname" class="form-label">First Name</label>
					<input type="text" class="form-control" name="firstname" id="firstname" value="<?php echo $customer['first_name']; ?>" pattern="[A-Za-z]+" required>
				</div>
				<div class="col-md-6">
					<label for="lastname" class="form-label">Last Name</label>
					<input type="text" class="form-control" name="lastname" id="lastname" value="<?php echo $customer['last_name']; ?>" pattern="[A-Za-z]+" required>
				</div>
				<div class="col-md-6">
					<label for="phoneno" class="form-label">Phone No.</label>
					<input type="tel" class="form-control" name="phoneno" id="phoneno" value="<?php echo $customer['phone_no']; ?>" pattern="[0-9]{10}" required>
				</div>
				<div class="col-md-6">
					<label for="pincode" class="form-label">Pincode</label>
					<input type="text" pattern="[0-9]{6}" class="form-control" name="pincode" id="pincode" 
					value="<?php echo $customer['pincode']; ?>" required>
				</div>
				<div class="col-12">
					<label for="address" class="form-label">Address</label>
					<textarea class="form-control" name="address" maxlength="250" id="address"
					required><?php echo $customer['address']; ?></textarea>
				</div>
				<div class="col-md-12">
					<input class="form-check-input" type="checkbox" name="save" value="True" id="save">
					<label class="form-check-label" for="save">Save details for later</label>
				</div>
				<div class="col-md-12">
					<select class="form-select" name="paymentmethod" aria-label="Default select example">
						<option selected value="cod">Cash on Delivery</option>
						<option value="credit">Pay by Creditcard</option>
						<option value="upi">Pay by UPI</option>
					</select>
				</div>
			
				<div class="modal-footer">
                	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            		<input class="btn btn-primary col-md-12 w-25" type="submit"
            		name="placeorder" value="Place Order">
            	</div>
            </form>
		</div>    
    </div>
</div>

</body>
</html>