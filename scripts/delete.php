<?php

//delete.php
session_start();
include("session_timeout.php");
if(!isset($_SESSION['userid'])) {
    header("Location:bittezuersteinloggen.php");
} 
header('Content-Type: text/html; charset=utf-8');
include("dbconnect.php");
$objectID = $_GET["id"];

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


if(isset($_POST["id"])){
$eventID = $_POST['id'];

$statement = $pdo->prepare("SELECT * FROM events WHERE id = :id");
$result = $statement->execute(array('id' => $eventID));
$eventInformation = $statement->fetch();

$startDateTime = $eventInformation['start_event'];
$endDateTime = $eventInformation['end_event'];
$eventTitle = $eventInformation['title'];

$queryLoggingInsert = "INSERT INTO logging (action, starttime, endtime, eventid, user, stable_id, stable_object_id)  VALUES (:action, :start_event, :end_event, :id, :title, :stable_id, :stable_object_id)";
$statementLogging = $pdo->prepare($queryLoggingInsert);
$statementLogging->execute(
	array(
		':action' => "DELETE",
		':start_event' => $startDateTime,
		':end_event' => $endDateTime,
		':id' => $eventID,
		':title'  => $eventTitle,
		':stable_id' => $stableID,
		':stable_object_id' => $objectID
	)
);

 $query = "DELETE from events WHERE id=:id";
 $statement = $pdo->prepare($query);
 $statement->execute(
  array(
   ':id' => $_POST['id']
  )
 );
 
 
 }

?>
