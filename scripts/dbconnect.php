<?php
/* 
* Hier wird die Verbindung zur Datenbank aufgebaut
*
* @author Alexander Freitag
* Stand 13.11.2017
* Version 2.5
*
*/

//header('Content-Type: text/html; charset=ISO-8859-1');
header('Content-Type: text/html; charset=utf-8');

$servername="localhost";

$benutzername="root";
$passwort="";
$dbname="mystable";
//$dbname="cedatenbanktestumgebung";


$db = new mysqli($servername, $benutzername, $passwort, $dbname) or die ("Verbindungsfehler: " . $db->error);
?>
