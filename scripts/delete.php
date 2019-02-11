<?php

//delete.php

$ini = parse_ini_file('../my_stable_config.ini');
$host = $ini["db_servername"];
$db = $ini['db_name'];

$dsn = "mysql:host=$host;dbname=$db";
$connect = new PDO($dsn, $ini['db_user'], $ini['db_password']);

if(isset($_POST["id"])){
$eventID = $_POST['id'];

$statement = $connect->prepare("SELECT * FROM events WHERE id = :id");
$result = $statement->execute(array('id' => $eventID));
$eventInformation = $statement->fetch();

$startDateTime = $eventInformation['start_event'];
$endDateTime = $eventInformation['end_event'];
$eventTitle = $eventInformation['title'];

$queryLoggingInsert = "INSERT INTO logging (action, starttime, endtime, eventid, user)  VALUES (:action, :start_event, :end_event, :id, :title)";
$statementLogging = $connect->prepare($queryLoggingInsert);
$statementLogging->execute(
	array(
		':action' => "DELETE",
		':start_event' => $startDateTime,
		':end_event' => $endDateTime,
		':id' => $eventID,
		':title'  => $eventTitle,
	)
);

 $query = "DELETE from events WHERE id=:id";
 $statement = $connect->prepare($query);
 $statement->execute(
  array(
   ':id' => $_POST['id']
  )
 );
 
 
 }

?>
