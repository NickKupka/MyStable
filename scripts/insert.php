<?php

//insert.php
session_start();
include("session_timeout.php");
if(!isset($_SESSION['userid'])) {
    header("Location:bittezuersteinloggen.php");
} 
header('Content-Type: text/html; charset=utf-8');
include("dbconnect.php");
$ini = parse_ini_file('../my_stable_config.ini');
$host = $ini["db_servername"];
$db = $ini['db_name'];


$dsn = "mysql:host=$host;dbname=$db";
$pdo = new PDO($dsn, $ini['db_user'], $ini['db_password']);
$dbUser = $ini['db_user'];
$dbPWD = $ini['db_password'];

$userid = $_SESSION['userid'];
$session_value=(isset($_SESSION['userid']))?$_SESSION['userid']:''; 
$expireDate = $_SESSION['expiryDate'];

$expireDate = $_SESSION['expiryDate'];

$date = new DateTime($expireDate);
$now = new DateTime();
if($date < $now) {
	//echo 'date is in the past';
	header("Location:licenceexpired.php");
}else{
	//echo "date is ok";
}	
$sessionIDSPlitted = explode(" ", $session_value);
$vorname = $sessionIDSPlitted[0]; // vorname aus session id
$nachname = $sessionIDSPlitted[1]; // nachname aus session id
$userID = $sessionIDSPlitted[2]; // user id aus session id
$stableID = $sessionIDSPlitted[3]; // stable aus session id





if(isset($_POST["title"]))
{
 $query = "INSERT INTO events (title, start_event, end_event, stable_id)  VALUES (:title, :start_event, :end_event, :stable_id)";
 $statement = $pdo->prepare($query);
 $statement->execute(
  array(
   ':title'  => $_POST['title'],
   ':start_event' => $_POST['start'],
   ':end_event' => $_POST['end'],
   ':stable_id' => $stableID
  )
 );
 $id = $pdo->lastInsertId();

 $queryLogging = "INSERT INTO logging (action, starttime, endtime, eventid, user, stable_id)  VALUES (:action, :start_event, :end_event, :id, :title, :stable_id)";
 $statementLogging = $pdo->prepare($queryLogging);
 $statementLogging->execute(
  array(
   ':action' => "INSERT",
   ':start_event' => $_POST['start'],
   ':end_event' => $_POST['end'],
   ':id' => $id,
  ':title'  => $_POST['title'],
  ':stable_id' => $stableID
  )
 );
 }


?>
