<?php

$error=null;

if(isset($_POST['login'])){
    $username=$_POST['username'];
    $password=$_POST['password'];

    try{
        $db= new PDO('mysql:host=127.0.0.1;dbname=tesaract','root');
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        $stmt=$db->prepare("SELECT id,password FROM customer WHERE username=?;");
        $stmt->execute([$username]);

        if( $result=$stmt->fetch() ){
            if(password_verify($password,$result['password'])){
                session_start();            
                if(!isset($_SESSION['customer_id']))
                    $_SESSION['customer_id']=$result['id'];

                header('Location:/tesaract/index.php');
                session_start();
                $_SESSION['username'] = $username;
            }
            else   
                $error="Incorrect Username/Password";
        }
        else   
            $error="Incorrect Username/Password";
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
        <?php require ('navbar.php'); ?>

        <?php if($error){ ?>

        <div class="alert alert-success alert-dismissible fade show text-center my-2 text-white" role="alert" style="background-color:#ee5b5b;">
            <?php echo $error; ?>
            <a href="login.php" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></a>
        </div>

        <?php } ?>

        <div class="container my-4 p-5 border" style="width:400px">
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
                <input type="submit" name="login" value="LogIn" class="btn btn-primary w-100"><br>
                <hr>
                <p class="text-center">Don't have an account?</p>
                <a href="signup.php" class="btn btn-primary w-100">Create New Account</a>
            </form>
        </div>
    </body>
</html>