<?php
session_start();

if(!isset($_SESSION['userid'])) {
    die('Bitte zuerst <a href="../Login.php">einloggen</a>');
}
//include('../dbconnect.php'); entweder direkt die ini-Datei ODER die dbconnect
$ini = parse_ini_file('../../my_stable_config.ini');
$host = $ini["db_servername"];
$db = $ini['db_name'];

$dsn = "mysql:host=$host;dbname=$db";
$pdo = new PDO($dsn, $ini['db_user'], $ini['db_password']);
$dbUser = $ini['db_user'];
$dbPWD = $ini['db_password'];

$userid = $_SESSION['userid'];
$session_value=(isset($_SESSION['userid']))?$_SESSION['userid']:''; 
$expireDate = $_SESSION['expiryDate'];

$date = new DateTime($expireDate);
$now = new DateTime();

if($date < $now) {
	// your licence has expired - you can't login anymore.
	header("Location:licenceexpired.php");
}else{
	//Licence is active
}

$sessionIDSPlitted = explode(" ", $session_value);
$vorname = $sessionIDSPlitted[0]; // vorname aus session id
$nachname = $sessionIDSPlitted[1]; // nachname aus session id


$statement = $pdo->prepare("SELECT * FROM users WHERE vorname = :vorname AND nachname = :nachname");
$statement->execute(array(':vorname' => $vorname, ':nachname' => $nachname));   
$user = $statement->fetch();

$id = $user['id'];
$userEMail = $user['email'];
if($user['active'] =='1'){
	$userAktiv = "Reiter aktiv";
} else {
	$userAktiv = "Reiter nicht aktiv";
}
$userPferd = $user['NameDesPferdes'];
$userAngelegtAm = $user['created_at'];
$userLaueftAusAm = $user['ExpiryDate'];

if (isset($_POST['submit'])) {
  $error = false;
	$vorname =  $_POST['vorname'];
	$nachname = $_POST['nachname'];
	$email = $_POST['Email'];
	$NameDesPferdes =$_POST['namedespferdes'];
	$aktiv = $_POST['dropdownuseractive'];
	
  
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
    }     
	if(strlen($nachname) == 0) {
        $error = true;
    }
	
	if(strlen($NameDesPferdes) == 0) {
        $error = true;
    }
    
	if(!$error) { 
		$statementCheckUser = $pdo->prepare("SELECT * FROM users WHERE id = '$id'");
		$statementCheckUser->execute(array(':vorname' => $vorname, ':nachname' => $nachname));   
		$userCheck = $statementCheckUser->fetch();
    }else{
		echo "error occured";
	}
    
    if(!$error) {    
		$statementUpdateUser = $pdo->prepare("UPDATE users SET vorname = :vorname_neu, nachname = :nachname_neu, email = :email_neu, NameDesPferdes = :NameDesPferdes_neu, active= :aktiv_neu WHERE id = '$id'");
		$statementUpdateUser->execute(array(':vorname_neu' => $vorname, ':nachname_neu' => $nachname, ':email_neu' => $email, ':NameDesPferdes_neu' => $NameDesPferdes, ':aktiv_neu' => $aktiv));   
		$count = $statementUpdateUser->rowCount();

		if($count == '0'){
			"Beim Aktualisieren Ihrer Daten ist ein Fehler aufgetreten";
		} else {
			"Aktualisieren erfolgreich";
			header("Location: edituser.php");
		}
		
		/* if($userUpdate != false) {     
			// update hat funktioniert
			
        } else {
						echo 'Beim Abspeichern ist leider ein Fehler aufgetreten<br>';
						var_dump($userUpdate);
        } */
    }else{
		echo "can't do anything";
	}		
}

?><html>
	<head>
		<title>Ihre Nutzerdaten (My-Stable)</title>
		<link rel="stylesheet" href="../../assets/css/main.css" />
		<link rel="shortcut icon" href="../../pictures/favicon.ico" type="image/x-icon">
		<link rel="icon" href="../../pictures/favicon.ico" type="image/x-icon">

		
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">

		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

		<!-- Popper JS -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>

		<!-- Latest compiled JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
	</head>
	<body class="is-preload">
		<div id="page-wrapper">
			<!-- Header -->
				<div id="header">
					<!-- Logo -->
						<h1><a href="../../index.html" id="logo">MyStable <em>by Technick Solutions</em></a></h1>
					<!-- Nav -->
						<nav id="nav">
							<ul>
							<li ><a href="../calendarview.php">Mein Kalendar</a></li>
							<li class="current"><a >Meine Daten</a></li>
							<?php 
															/*
								Check if current user is admin - otherwise page can not be visited
								*/
								$con=mysqli_connect($host,$dbUser,$dbPWD,$db);

								$result = mysqli_query($con,"SELECT * FROM `users` WHERE `nachname` LIKE '%{$nachname}%' AND `vorname` LIKE '%{$vorname}%'");
								$row = mysqli_fetch_array($result);

								if ($row['adminAllowed'] == "1") {
									echo "<li><a href='alluser.php'>Nutzerübersicht</a></li>";
								}
							?>
							<li><a href="../events/myentries.php">Meine Einträge</a></li>
							<li><a href="../impressum.php">Impressum</a></li>
							<li><a href="../Logout.php">Logout</a></li>					
					</ul>
						</nav>
				</div>
			<!-- Main -->
				<section class="wrapper style1">
					<div align="center" class="container">
						<form class="form-horizontal" action="#" method="POST"> <!--?editUser=1 -->
								<fieldset>

								<!-- Form Name -->
								<legend>Hier sehen Sie Ihre hinterlegten Daten.</legend>

								<!-- Text input-->
								<div class="form-group">
								  <label class="col-md-4 control-label" for="Vorname">Vorname</label>  
								  <div class="col-md-4">
								  <input id="vorname" name="vorname" type="text" size="100" value="<?php echo $vorname;?>" class="form-control input-lg" readonly>
								  </div>
								</div>

								<!-- Text input-->
								<div class="form-group">
								  <label class="col-md-4 control-label" for="nachname">Nachname</label>  
								  <div class="col-md-4">
								  <input id="nachname" name="nachname" type="text"  value="<?php echo $nachname;?>" class="form-control input-md" readonly> 
									
								  </div>
								</div>

								<!-- Text input-->
								<div class="form-group">
								  <label class="col-md-4 control-label" for="namedespferdes">Name des Pferdes</label>  
								  <div class="col-md-4">
								  <input id="namedespferdes" name="namedespferdes" type="text" value="<?php echo $userPferd;?>" class="form-control input-md" >
									
								  </div>
								</div>

								<!-- Text input-->
								<div class="form-group">
								  <label class="col-md-4 control-label" for="email">E-Mail Adresse</label>  
								  <div class="col-md-4">
								  <input id="email" name="Email" type="text"value="<?php echo $userEMail;?>" class="form-control input-md" >
									
								  </div>
								</div>

								<!-- Text input-->
								<div class="form-group">
								  <label class="col-md-4 control-label" for="aktivseit">Registriert seit</label>  
								  <div class="col-md-4">
								  <input id="aktivseit" name="aktivseit" type="text" value="<?php echo date('d.m.Y  H:i:s', strtotime($userAngelegtAm));?>" class="form-control input-md" readonly>
									
								  </div>
								</div>

								<!-- Text input-->
								<div class="form-group">
								  <label class="col-md-4 control-label" for="lizenzlaeuftbis">Lizenz läuft bis zum</label>  
								  <div class="col-md-4">
								  <input id="lizenzlaeuftbis" name="lizenzlaeuftbis" type="text" value="<?php echo date('d.m.Y', strtotime($userLaueftAusAm));?>" class="form-control input-md" readonly>
									
								  </div>
								</div>

								<!-- Select Basic -->
								<div class="form-group">
								  <label class="col-md-4 control-label" for="dropdownuseractive">Nutzerdaten</label>
								  <div class="col-md-4">
									<select id="dropdownuseractive" name="dropdownuseractive" class="form-control">
										<option selected hidden="true"><?php echo $userAktiv;?></option>
									  <option value="1">Reiter aktiv</option>
									  <option value="0">Reiter nicht mehr in Stall</option>
									</select>
								  </div>
								</div>


								<!-- Button -->
								<div class="form-group">
								  <label class="col-md-4 control-label" for="speicherButton"></label>
								  <div class="col-md-4">
									<input type="submit" name="submit" class="btn btn-primary" id="speichern" value="Speichern" />
								  </div>
								</div>

								</fieldset>
								</form>

					</div>
					<br/>
					<br/>
				</section>
				
			<!-- Footer -->
				<div  id="footer">
					
					<!-- Copyright -->
						<div class="copyright">
							<ul class="menu">
							<li><img  src="../../pictures/logoPNG.png"/></li><br/>
								<li>&copy; Technick Solutions - My Stable Organizer. All rights reserved</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
							</ul>
						</div>
				</div>
		</div>

		<!-- Scripts -->
			<script src="../assets/js/jquery.min.js"></script>
			<script src="../assets/js/jquery.dropotron.min.js"></script>
			<script src="../assets/js/browser.min.js"></script>
			<script src="../assets/js/breakpoints.min.js"></script>
			<script src="../assets/js/util.js"></script>
			<script src="../assets/js/main.js"></script>
			  
	</body>
</html>
