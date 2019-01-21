<?php
// Session expired
$expireAfter = 30; // Session timeout in minutes - Changeable
error_reporting(0);
if(isset($_SESSION['last_action'])){
    $secondsInactive = time() - $_SESSION['last_action'];
    $expireAfterSeconds = $expireAfter * 60;
    if($secondsInactive >= $expireAfterSeconds){
        session_unset();
        session_destroy();
		header("Location:session_timeout_tl.php");
    }
}
$_SESSION['last_action'] = time();
?>