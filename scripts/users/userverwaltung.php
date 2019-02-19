<?php
session_start();
include("dbconnect.php");

$userid = $_SESSION['userid'];
$session_value=(isset($_SESSION['userid']))?$_SESSION['userid']:''; 
	
$sessionIDSPlitted = explode(" ", $session_value);
$vorname = $sessionIDSPlitted[0]; // vorname aus session id
$nachname = $sessionIDSPlitted[1]; // nachname aus session id
$userID = $sessionIDSPlitted[2]; // user id aus session id
$stableID = $sessionIDSPlitted[3]; // stable aus session id


$ini = parse_ini_file('../../my_stable_config.ini');
$host = $ini["db_servername"];
$db = $ini['db_name'];
$dsn = "mysql:host=$host;dbname=$db";
$pdo = new PDO($dsn, $ini['db_user'], $ini['db_password']);
$dbUser = $ini['db_user'];
$dbPWD = $ini['db_password'];


$dsn = "mysql:host=$host;dbname=$db";
$connect = new PDO($dsn, $ini['db_user'], $ini['db_password']);

/*
current date and time
*/
date_default_timezone_set('Europe/Berlin');
$date = date('d/m/Y h:i:s', time());

/*
current user
*/
$conCurrentUsr=mysqli_connect($host,$dbUser,$dbPWD,$db);
$resultCurrentUsr = mysqli_query($conCurrentUsr,"SELECT * FROM `users` WHERE `nachname` LIKE '%{$nachname}%' AND `vorname` LIKE '%{$vorname}%'");
$rowCurrentUsr = mysqli_fetch_array($resultCurrentUsr);
$currentUserID = $row['id'];	
if(isset($_POST['verwarnenButton'])){
	$id=$_POST['itemid'];
	$query = "UPDATE users SET Verwarnt=:verwarnt WHERE id=:id";
	$statement = $connect->prepare($query);
	$statement->execute(
		array(
		':verwarnt'  => "1",
		':id'   => $id
		)
	);
	$queryInsertCaution = "INSERT INTO user_cautions (stable_id, userid, adminid, Verwarnt)  VALUES (:stable_id, :userID, :adminID, :verwarnt)";
	$statementInsertCaution = $connect->prepare($queryInsertCaution);
	$statementInsertCaution->execute(
		array(
		':stable_id' => $stableID,
		':userID'  => $id,
		':adminID' => $userID,
		':verwarnt' => "1"
		)
	);
}
if(isset($_POST['verwarnenAufhebenButton'])){
 $id=$_POST['itemid'];
 $query = "UPDATE users SET Verwarnt=:verwarnt WHERE id=:id";
 $statement = $connect->prepare($query);
 $statement->execute(
  array(
   ':verwarnt'  => "0",
   ':id'   => $id
  )
  );
  
  $queryDeleteCaution = "DELETE FROM user_cautions WHERE (userid) and Gesperrt = '0'";
	$statementDeleteCaution = $connect->prepare($queryDeleteCaution);
	$statementDeleteCaution->execute(
		array(
		':userID'  => $id,
		)
	);
}


if(isset($_POST['sperrenButton'])){
 $id=$_POST['itemid'];
 $query = "UPDATE users SET Gesperrt=:gesperrt WHERE id=:id";
 $statement = $connect->prepare($query);
 $statement->execute(
  array(
   ':gesperrt'  => "1",
   ':id'   => $id
  )
  );
  
  $queryInsertCaution = "INSERT INTO user_cautions (stable_id, userid, adminid, Verwarnt, Gesperrt)  VALUES (:stable_id, :userID, :adminID, :verwarnt, :gesperrt)";
	$statementInsertCaution = $connect->prepare($queryInsertCaution);
	$statementInsertCaution->execute(
		array(
		':stable_id' => $stableID,
		':userID'  => $id,
		':adminID' => $userID,
		':verwarnt' => "1",
		':gesperrt' => "1",
		)
	);
}
if(isset($_POST['sperrenAufhebenButton'])){
 $id=$_POST['itemid'];
 $query = "UPDATE users SET Gesperrt=:gesperrt, Verwarnt=:verwarnt WHERE id=:id";
 $statement = $connect->prepare($query);
 $statement->execute(
	  array(
	   ':gesperrt'  => "0",
	   ':verwarnt' => "0",
	   ':id'   => $id
	  )
  );
  
	$queryDeleteCaution = "DELETE FROM user_cautions WHERE (userid) and Gesperrt = '1' ";
	$statementDeleteCaution = $connect->prepare($queryDeleteCaution);
	$statementDeleteCaution->execute(
		array(
		':userID'  => $id,
		)
	);
}
header("Location: " . $_SERVER["HTTP_REFERER"]);

?>
