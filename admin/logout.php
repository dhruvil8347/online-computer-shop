<?php

session_start();

if(isset($_SESSION['is_logged_in'])){
	unset($_SESSION['is_logged_in']);
	header('Location:index.php');
}

?>