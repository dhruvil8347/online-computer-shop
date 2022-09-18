<?php
    session_start();
    
    if(!isset($_SESSION['is_logged_in']))
        header('Location:index.php');
    
    $success=null;
    if(isset($_POST['save'])){
        $id=$_POST['id'];
        $name=$_POST['name'];
        $price=$_POST['price'];
        $quantity=$_POST['quantity'];
        $category=$_POST['category'];
        $dir='C:\\xampp\\htdocs\\tesaract\\images\\';
        $dir=$dir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $dir);
        $image='/tesaract/images/'. basename($_FILES['image']['name']);
        $conn = new mysqli('localhost','root','','tesaract');
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "UPDATE products SET name='$name',price='$price',quantity='$quantity',category='$category' WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
  echo"<script>alert(\"Record updated successfully\");</script>";
  echo"<script>window.location.href = \"viewer.php\";</script>";
} else {
  echo "Error updating record: " . $conn->error;
}

$conn->close();

    }
?>
<?php

// Create connection
$conn = new mysqli('localhost','root','','tesaract');
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
error_reporting(0);
$id=$_GET['id'];
$sql = "SELECT * FROM products WHERE id='$id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    $name=$row["name"];
    $category=$row["category"];
    $price=$row["price"];
    $quantity=$row["quantity"];
    
  }
} else {
    if($id!=NULL){
  echo "No Record Found !!!";}
}
$conn->close();
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <title>Update Product</title>
</head>
<body class="mx-5 my-4">
    <?php if($success){ ?>
        
    <div class="alert alert-success alert-dismissible fade show text-center text-white my-2" role="alert" style="background-color: #51b97f;">
        <?php echo $success; ?>
        <a href="add_book.php" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></a>
    </div>
        
    <?php $success=null; } ?>
    <h3>
        <a href="index.php" class="btn btn-success btn-sm">
            Back
        </a>
        <span class="mx-5">Update Product</span>
    </h3>
    <form action="Update.php" method="post" enctype="multipart/form-data" class="mx-5">
        <table cellpadding="4" class="mx-5">
            
            <tr>
                 <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
                <th>Name</th>
                <td><input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>"></td>
            </tr>
            
            
            <tr>
                <th>Category</th>
                <td><input type="text" class="form-control" id="category" name="category" value="<?php echo $category; ?>"></td>
            </tr>
            <tr>
                <th>Price</th>
                <td><input type="number" class="form-control" id="price" name="price" value="<?php echo $price; ?>"></td>
            </tr>
            <tr>
                <th>Quantity</th>
                <td><input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo $quantity; ?>"></td>
            </tr>
            
            <tr>
                <th>Product Image</th>
                <td><input type="file" class="form-control" id="image" name="image"></td>
            </tr>
            <tr>
                <th></th>
                <td><input type="reset" value="Clear" class="btn btn-primary w-25">
                <input type="submit" value="Update" name="save" class="btn btn-primary mx-2 w-25"></td>
            </tr>
        </table>
    </form>

</body>
</html>