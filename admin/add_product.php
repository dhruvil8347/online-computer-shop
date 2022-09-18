<?php
    session_start();
    
    if(!isset($_SESSION['is_logged_in']))
        header('Location:index.php');
    
    $success=null;
    if(isset($_POST['save'])){
        $name=$_POST['name'];
        $price=$_POST['price'];
        $quantity=$_POST['quantity'];
        $category=$_POST['category'];
        $dir='C:\\xampp\\htdocs\\tesaract\\images\\';
        $dir=$dir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $dir);
        $image='/tesaract/images/'. basename($_FILES['image']['name']);
        try{
            require ('connection.php');
            $stmt=$db->prepare("INSERT INTO products VALUES(NULL,?,?,?,?,?);");
            $result=$stmt->execute([$name,$price,$quantity,$category,$image]);
            if($result){
                $success="Product is added successfully";
            }
            $db=null;
        }
        catch(PDOException $e){
            echo $e->getMessage();
            die();
        }
    }
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <title>Add Product</title>
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
        <span class="mx-5">Add Product</span>
    </h3>
    <form action="add_product.php" method="post" enctype="multipart/form-data" class="mx-5">
        <table cellpadding="4" class="mx-5">
            
            <tr>
                <th>Name</th>
                <td><input type="text" class="form-control" id="name" name="name"></td>
            </tr>
            
            
            <tr>
                <th>Category</th>
                <td><input type="text" class="form-control" id="category" name="category"></td>
            </tr>
            <tr>
                <th>Price</th>
                <td><input type="number" class="form-control" id="price" name="price"></td>
            </tr>
            <tr>
                <th>Quantity</th>
                <td><input type="number" class="form-control" id="quantity" name="quantity"></td>
            </tr>
            
            <tr>
                <th>Product Image</th>
                <td><input type="file" class="form-control" id="image" name="image"></td>
            </tr>
            <tr>
                <th></th>
                <td><input type="reset" value="Clear" class="btn btn-primary w-25">
                <input type="submit" value="Save" name="save" class="btn btn-primary mx-2 w-25"></td>
            </tr>
        </table>
    </form>

</body>
</html>