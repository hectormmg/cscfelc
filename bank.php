<?php
session_name("CSCFELC");
session_start();
if(isset($_SESSION['Username'])){
  header("location:user/bank.php");
}else{
	header("location:user/signin.php");
}
?>