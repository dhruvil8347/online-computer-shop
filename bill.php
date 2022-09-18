<?php
try{
	session_start();
	if(!isset($_SESSION['customer_id']))
		header('Location: account/login.php');

	require ('connection.php');

    //view all orders
    $customer_id=$_SESSION['customer_id'];

    $q=$db->prepare("SELECT * FROM orders WHERE customer_id=? 
                        ORDER BY ordered_date DESC;");
	$q->execute([$customer_id]);
    $all_orders=$q->fetchAll();

    $product_names=array();

  

	foreach($all_orders as $order){

		$q=$db->prepare("SELECT name FROM products WHERE id=?;");
		$q->execute([$order['product_id']]);
		$product=$q->fetch();
		$product_names[$order['id']]=$product['name'];
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
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
	<title>Bill</title>
</head>
<body>
<?php require ('navbar.php'); 

?>


<?php if(isset($_SESSION['success_order'])){ ?>
    <div class="alert alert-success alert-dismissible fade show my-1 text-center" role="alert" style="background-color:#d1e7dd;color:#0f5132">
        <strong><?php echo $_SESSION['success_order']; ?></strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php } unset($_SESSION['success_order']); ?>


<div class="container my-4">

    <h4 class="my-5">Your Bill</h4>
    <h4></h4>
   

    <table class="table">
        <thead>
            <tr class="text-center">
                <th>#</th>
                <th>Username</th>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Ordered Date</th>
                <th>Delivered Date</th>
                <th>Order Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php $i=0;$total=0;
            	  foreach($all_orders as $order){
            	  $i++; ?>
            <tr  class="text-center">
                <td><?php echo $i; ?></td>
                <td><?php echo $_SESSION['username']?></td>
                <td><?php echo $product_names[$order['id']]; ?></td>
                <td>&#x20B9;<?php echo $price1=$order['price']; $total=$total+$price1;?></td>
                <td><?php echo $order['quantity']; ?></td>
                <td><?php echo $order['ordered_date']; ?></td>
                <td>
                    <?php if(!$order['delivery_date'])
                    		echo "-";
                    	  else
                    	  	echo $order['delivery_date'] 
                    ?>                   
                </td>
                <td class="text-center">
                    <?php if($order['order_status']){ ?>
                        <span class="badge bg-success">Delivered</span>
                    <?php }else{ ?>
                        <span class="badge bg-warning text-dark">In Progress</span>
                    <?php } ?>
                </td>
                <td>
                    <?php if(!$order['order_status']){ ?>
                    <a class="btn btn-danger btn-sm" href="functions.php?
                        id=<?php echo $order['id']; ?>&action=cancel">
                        Cancel
                    </a>


                        <td>
                    
                </td>

                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
	<h2 align="right">Total : <?php echo $total; ?> &#x20B9;</h2>
    <button class="btn btn-primary" onclick="window.print()">Print</button>

</div>

</body>
</html>