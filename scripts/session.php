<?php
   include('dbconnect.php');
   session_start();
   error_reporting(0); //(E_ALL ^  E_NOTICE);
   include ("session_timeout.php"); // must be after session_start();
   $user_check = $_SESSION['login_user'];
   
   $ses_sql = mysqli_query($db,"select username from user where username = '$user_check' ");
   
   $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
   
   $login_session = $row['username'];
   
   if(!isset($_SESSION['login_user'])){
      header("location:Login.php");
   }
?>