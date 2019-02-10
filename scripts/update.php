<?php

//update.php

$ini = parse_ini_file('../my_stable_config.ini');
$host = $ini["db_servername"];
$db = $ini['db_name'];

$dsn = "mysql:host=$host;dbname=$db";
$connect = new PDO($dsn, $ini['db_user'], $ini['db_password']);

if(isset($_POST["id"])){
 $query = "UPDATE events SET title=:title, start_event=:start_event, end_event=:end_event WHERE id=:id";
 $statement = $connect->prepare($query);
 $statement->execute(
  array(
   ':title'  => $_POST['title'],
   ':start_event' => $_POST['start'],
   ':end_event' => $_POST['end'],
   ':id'   => $_POST['id']
  )
 );
 

 $queryLogging = "INSERT INTO logging (action, starttime, endtime, eventid, user)  VALUES (:action, :start_event, :end_event, :id, :title)";
 $statementLogging = $connect->prepare($queryLogging);
 $statementLogging->execute(
  array(
   ':action' => "UPDATE",
   ':start_event' => $_POST['start'],
   ':end_event' => $_POST['end'],
   ':id' => $_POST['id'],
  ':title'  => $_POST['title'],
  )
 );
 
}

?>