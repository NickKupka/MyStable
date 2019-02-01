<?php
header('Content-Type: text/html; charset=utf-8');
$servername="localhost";
$benutzername="root";
$passwort="";
$dbname="mystable";

$ini = parse_ini_file('../my_stable_config.ini');

$db = new mysqli($ini['db_servername'], $ini['db_user'], $ini['db_password'], $ini['db_name']) or die ("Verbindungsfehler: " . $db->error);
?>
