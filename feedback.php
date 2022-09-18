<?php
$error=false;
if (isset($_POST['submit']))
{
  $email=$_POST['email'];
  $username=$_POST['username'];
  $subject=$_POST['subject'];
  $message=$_POST['message'];


 try{
         $db= new PDO('mysql:host=127.0.0.1;dbname=tesaract','root');
         if(!$error)
         {
          //$password=password_hash($password,PASSWORD_DEFAULT);
            $stmt=$db->prepare("INSERT INTO feedback VALUES(NULL,?,?,?,?);");
            $result=$stmt->execute([$email,$username,$subject,$message,]);

            if($result){echo"Successfully Submit";}
            else{echo"Successfully Submit";}

           // header('Location:feedback.php');
          }
            $db=null;
}
            catch(PDOException $e)
            {
              echo $e->getMessage();
              die();
            }
 }

?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
  <title></title>
</head>
<body>
 <?php require ('navbar.php'); ?>
 <div class="container my-4 p-5 border" style="width:460px;">
        <h3 class="text-center">feedback</h3><br>

        <form action="feedback.php" method="POST" class="row">

          <div class="mb-2 col-md-12">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" name="email" id="email" required>
            </div>

            <div class="mb-2 col-md-12">
                <label for="username" class="form-label">Userame</label>
                <input type="text" class="form-control" name="username" id="username" pattern="^[a-z]+[0-9]*$" required>
            </div>

            <div class="mb-2 col-md-12">
                <label for="email" class="form-label">Subject</label>
                <input type="text" class="form-control" name="subject" id="email" required>
            </div>
            <div class="mb-2 col-md-12">
              <label for="message" class="form-label">Message</label>
              <div class="form-floating">
                
  <textarea class="form-control" id="floatingTextarea" name="message"></textarea>
 
</div>
            </div>
            
            
           
            <div class="col-md-12">
                

                <button type="submit" name="submit" value="submit" class="btn btn-primary">submit</button>
            </div>
        </form>

</body>
</html>