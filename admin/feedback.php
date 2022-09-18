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
    <title>Feedback</title>
</head>
<body>

<h3 class="my-4 mx-5">
    <a href="index.php" class="btn btn-success btn-sm">
        Back
    </a>
    <span class="mx-4">Feedback</span>
</h3>

<div class="container">
    <table cellpadding="4" class="table">
        <tr align="center">
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Subject</th>
            <th>Message</th>
            <th></th>
        </tr>
        <?php 
        $servername = "localhost";
$username = "root";
$password = "";
$dbname = "tesaract";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
        $sql = "SELECT * FROM feedback";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($order = $result->fetch_assoc()) {
    
    ?>
        <tr align="center">
            <td><?php echo $order['id']; ?></td>
            <td>
                <?php 
                    echo $order['username'];
                   
                ?>                
            </td>  
            <td><?php echo $order['email']; ?></td> 
            <td><?php echo $order['subject']; ?></td>   
            <td><?php echo $order['message']; ?></td>
        </tr>
        <?php
 }
} else {
  echo "0 results";
}
        ?>
    </table>
</div>

</body>
</html>