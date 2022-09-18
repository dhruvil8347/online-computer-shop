<?php
try{
    if(isset($_POST['search'])){
        session_start();
        require ('connection.php');

        $query=strtolower($_POST['q']);

        $q=$db->prepare("SELECT * FROM products 
            WHERE LOWER(category)=? ;");
        $q->execute([$query]);
        $all_products=$q->fetchAll(PDO::FETCH_ASSOC);
    }
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
    <title>Tesaract</title>
</head>
<body>

<?php require ('navbar.php'); ?>

<?php if($all_products){ ?>

<div class="container-fluid p-3"> 
    <h5 class="mt-3 mx-5">Search results for 
        <span style="color: #1a0dab">"<?php echo $query ?>"</span>
    </h5>   
    <div class="g-2 p-5">       
        <div class="row row-cols-1 row-cols-md-4 p-5">
        <?php foreach($all_products as $product){ ?> 
            <div class="col my-1">
                <div class="card h-100" data-bs-toggle="modal" 
                    data-bs-target="#product<?php echo $product['id']; ?>">
                    <img src="<?php echo $product['image'];?>" 
                        class="card-img-top" />
                    <div class="card-body">
                        <h6 class="d-inline-block">
                            <?php echo $product['name']; ?>        
                        </h6>
                       
                        <h5>
                            &#x20B9;<?php echo $product['price']; ?>
                        </h5>
                    </div>
                </div>
            </div>
        <?php } ?>
        </div>
    </div>
</div>

<?php require ('product_details.php'); ?>

<?php }else{ ?>

<div class="d-flex justify-content-center" style="background-color:#eee8e8">
    <img src="images/no_result.gif">
</div>

<?php } ?>

</body>
</html>