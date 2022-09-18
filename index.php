<?php
try{
	session_start();
	require ('connection.php');

	$q=$db->prepare("SELECT DISTINCT category FROM products;");
	$q->execute();
	$all_categories=$q->fetchAll(PDO::FETCH_ASSOC);	

	if(isset($_GET['category'])){
		$q=$db->prepare("SELECT DISTINCT category FROM products 
			             WHERE category=?;");
		$q->execute([$_GET['category']]);
		$selected_categories=$q->fetchAll(PDO::FETCH_ASSOC);	
	}
	else
		$selected_categories=$all_categories;

	$q=$db->prepare("SELECT * FROM products;");
	$q->execute();
	$all_products=$q->fetchAll(PDO::FETCH_ASSOC);
	
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
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<style>
a{
	color: #808080;
  text-decoration: none;
}
</style>

	<title>Tesseract</title>
</head>
<body>

<?php require ('navbar.php'); ?>

<div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="images\t.jpg" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="images\gpu2.jpg" style="width:100%;" class="d-block w-15 h-15" alt="...">
    </div>
    <div class="carousel-item">
      <img src="images\cabinet.jpg" style="width:100%;" class="d-block w-15 h-15" alt="...">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>



<?php if(isset($_SESSION['success'])){ ?>

	<div class="alert alert-success alert-dismissible fade show text-center my-2" role="alert" style="background-color:#d1e7dd;color:#0f5132">
        <strong><?php echo $_SESSION['success']; ?></strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>



<?php unset($_SESSION['success']); } ?>




<div class="container-fluid">
	<div class="row">
		<div class="col-md-2 my-2 g-2">
			<hr class="text-primary">
			<h5 class="mx-2">Categories</h5>
			<hr class="text-primary">			

			<!--rowurlencode()- for including space in query string-->
  			<div class="mx-2 my-3">
    			<?php foreach($all_categories as $category){ ?>
    				<a href="index.php?category=<?php 
    				    echo rawurlencode($category['category']); ?>" 
    					class="my-2 d-block" style="text-transform: capitalize;">
    					<?php echo $category['category']; ?>		
    				</a>
    			<?php } ?>
  			</div>
		</div>
		
		<div class="col-md-10 g-2 my-2 px-5">		
		<?php foreach($selected_categories as $category){ ?>
			<hr class="text-primary">
			<h5 style="text-transform: capitalize;"><?php echo $category['category']; ?></h5>
			<hr class="text-primary">
			<div class="row row-cols-1 row-cols-md-4">
			<?php foreach($all_products as $product){ 
				if($product['category']==$category['category']){ ?>
				<div class="col" id="<?php echo $product['id']; ?>">
					<div class="card h-100" data-bs-toggle="modal" 
						data-bs-target="#product<?php echo $product['id']; ?>">
						<img src="<?php echo $product['image'];?>" 
							class="card-img-top" />
						<div class="card-body">
							<h6 class="d-inline-block">
								<?php echo $product['name']; ?></h6>
							
							<h5>
								&#x20B9;<?php echo $product['price']; ?>
							</h5>
						</div>
					</div>
				</div>
			<?php } }  ?>
			</div>
		<?php } ?>
		</div>
	</div>
</div>
<div class="card">
  <div class="card-header">
  	<div class="col-md-12">
    <a href="index.php"><button type="button" class="btn btn-secondary w-100">Back to Top</button></a>
  </div>
</div>
  <div class="card-body">
    <blockquote class="blockquote mb-0">
      <p align="center">© 2000-2021, Tesaract.com, Inc. or its affiliates.</p>
      <div align="center"><footer class="blockquote-footer">Contact Us    <cite title="Source Title">
      	<img src="images/instagram.png" width="20" height="20">
      	<img src="images/facebook.png" width="20" height="20">
      	<img src="images/twitter.png" width="20" height="20"><br>
      	<img src="images/email.png" width="20" height="20"> : tesaract20@gmail.com
      </cite></footer></div>
    </blockquote>
  </div>
</div>

<?php require ('product_details.php'); ?>

</body>
</html>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>