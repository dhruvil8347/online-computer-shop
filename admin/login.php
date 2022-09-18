.<?php
$error=null;
if(isset($_POST['login'])){
	$username=$_POST['username'];
	$password=$_POST['password'];

	try{
		require ('connection.php');

		$stmt=$db->prepare("SELECT * FROM admin WHERE username=?and password=?;");
		$stmt->execute([$username,$password]);

		if($stmt->fetch()){
			session_start();
			if(!isset($_SESSION['is_logged_in']))
				$_SESSION['is_logged_in']=true;

			header('Location:index.php');
		}
		else{
			$error="Incorrect Username/Password";
		}

		$db=null;
	}
	catch(PDOException $e){
		echo $e->getMessage();
		die();
	}
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
	<title>Login</title>
</head>
<body>
	<?php if($error){ ?>
		
		<div class="alert alert-warning alert-dismissible fade show text-center 
		text-white my-2" role="alert" style="background-color:#ee5b5b">
			<?php echo $error; ?>
  			<a href="add_books.php" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	
	<?php $error=null; } ?>
	
	<div class="container my-5 p-5 border" style="width:400px">
		<h3 class="text-center">Log In</h3><br>
		<form action="login.php" method="post">
			<div class="mb-3">
				<label for="username" class="form-label">Username</label>
				<input type="text" class="form-control" name="username" id="username" required>
			</div>
			<div class="mb-3">
				<label for="password" class="form-label">Password</label>
				<input type="password" class="form-control" name="password" id="password" required>
			</div>
			<input type="submit" name="login" value="LogIn" class="btn btn-primary w-100">
		</form>
	</div>
</body>
</html>