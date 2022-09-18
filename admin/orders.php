<?php
    session_start();
    $today=date('Y-m-d');
    if(!isset($_SESSION['is_logged_in']))
        header('Location:index.php');
    
    try{
        require ('connection.php');

        if(isset($_POST['save'])){
            $status=0;
            if(isset($_POST['order_status']))
                $status=1;
            $date=$_POST['delivery_date'];
            $order_id=$_POST['id'];
            $q=$db->prepare("UPDATE orders 
                SET order_status=?,delivery_date=?
                WHERE id=?;");
            $q->execute([$status,$date,$order_id]);            
        }

        $q=$db->prepare("SELECT * FROM orders;");
        $q->execute();
        $all_orders=$q->fetchAll(PDO::FETCH_ASSOC);

    }
    catch(PDOException $e){
        echo $e->getMessage();
        die();
    }
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <title>Orders</title>
</head>
<body>

<h3 class="my-4 mx-5">
    <a href="index.php" class="btn btn-success btn-sm">
        Back
    </a>
    <span class="mx-4">Orders</span>
</h3>

<div class="container">
    <table cellpadding="4" class="table">
        <tr align="center">
            <th>#</th>
            <th>Product Details</th>
            <th>Quantity</th>
            <th>Customer Details</th>
            <th>Ordered Date</th>
            <th>Order Status</th>
            <th>Delivered Date</th>
            <th></th>
        </tr>
        <?php 
        foreach($all_orders as $order){ 
            $q=$db->prepare("SELECT * FROM products 
                    WHERE id=?;");
            $q->execute([$order['product_id']]);
            $product=$q->fetch(PDO::FETCH_ASSOC);
            $q=$db->prepare("SELECT * FROM customer 
                    WHERE id=?;");
            $q->execute([$order['customer_id']]);
            $customer=$q->fetch(PDO::FETCH_ASSOC);
        ?>
        <tr align="center">
            <td><?php echo $order['id']; ?></td>
            <td>
                <?php 
                    echo $product['name']."<br>";
                   
                ?>                
            </td>   
            <td><?php echo $order['quantity']; ?></td>   
            <td>
                <?php 
                    echo $customer['first_name']." "; 
                    echo $customer['last_name']."<br>";
                    echo "Phone No : ".$customer['phone_no']."<br>";
                ?>    
            </td>   
            <td><?php echo $order['ordered_date']; ?></td>   
            <?php if($order['order_status']){ ?>
                <td>
                    <span class="badge bg-success">
                        Delivered
                    </span>
                </td>
                <td>
                    <?php echo $order['delivery_date']; ?>
                </td>
                <td></td>
            <?php }else{ ?>
                <form action="orders.php" method="POST">
                <td>
                    <input type="hidden" name="id" 
                    value="<?php echo $order['id']; ?>">
                    <input type="checkbox" name="order_status" 
                    class="form-check-input"/>
                </td>  
                <td>
                    <input type="date" name="delivery_date" 
                    value="<?php echo $today; ?>">
                </td>
                <td>
                    <input type="submit" value="Save" 
                    name="save" class="btn btn-primary btn-sm">
                </td>    
                </form>
            <?php } ?>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>