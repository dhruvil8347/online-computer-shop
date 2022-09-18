<?php 
//require ('connection.php');
$conn = new mysqli('localhost','root','','tesaract');
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
$id=$_GET['id'];
$sql = "DELETE FROM products WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
  echo"<script>alert(\"Record deleted successfully\");</script>";
  echo"<script>window.location.href = \"viewer.php\";</script>";
} else {
  echo "Error deleting record: " . $conn->error;
}

$conn->close();
?>
