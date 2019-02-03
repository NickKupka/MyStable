<?php
header('Content-Type: text/html; charset=utf-8');
$servername="localhost";
//$benutzername="MyStableDBRoot";
//$passwort="Nick&Alex2019";
$benutzername="root";
$passwort="";

$dbname="mystable";
$db = new mysqli($servername, $benutzername, $passwort, $dbname) or die ("Verbindungsfehler: " . $db->error);
?>
