<?php
  if(isset($_SESSION['customer_id'])){
    require ('connection.php');
    $customer_id=$_SESSION['customer_id'];

    $q=$db->prepare("SELECT * FROM cart WHERE customer_id=?;");
    $q->execute([$customer_id]);
    $total_products=0;

    while($cart=$q->fetch()){
      $total_products+=$cart['quantity'];
    }
    $_SESSION['total_products']=$total_products;
  }
?>
<nav class="navbar navbar-expand-lg navbar-light bg-secondary">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Tesseract</a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="about.php">about</a>
        </li>
         
        <form class="d-flex mx-3" action="search.php" method="POST">
          <input class="form-control me-2" type="text" name="q" placeholder="Search" aria-label="Search" style="width: 550px" required>
          <button class="btn btn-success" name="search" type="submit">
            Search
          </button>
        </form>
      </ul>
      <ul class="navbar-nav mr-auto mb-2 mb-lg-0">
        <?php if(!isset($_SESSION['customer_id'])){ ?>
           <li class="nav-item">
          <a class="nav-link active" href="feedback.php">feedback</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="account/login.php">Login</a>
        </li>
         
        <li class="nav-item">
          <a class="nav-link" href="account/signup.php">Signup</a>
        </li>
        <?php }else{ ?>
        <li class="nav-item">
          <a class="nav-link" href="account/logout.php">Logout</a>
        </li>
        <?php } ?>
        <li class="nav-item">
          <a class="nav-link" href="orders.php">Orders</a>
        </li>
        <li class="nav-item mx-1">
          <a class="btn btn-success" href="cart.php">
            <?php 
                if(isset($_SESSION['customer_id']))
                   echo "Cart (".$total_products.")";
                else
                   echo "Cart";
            ?>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
