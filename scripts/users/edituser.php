<?php
session_start();

if(!isset($_SESSION['userid'])) {
    header("Location:../bittezuersteinloggen.php");
	exit;
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
	header("Location:../licenceexpired.php");
}else{
	//Licence is active
}

$sessionIDSPlitted = explode(" ", $session_value);
$vorname = $sessionIDSPlitted[0]; // vorname aus session id
$nachname = $sessionIDSPlitted[1]; // nachname aus session id
$userID = $sessionIDSPlitted[2]; // user id aus session id
$stableID = $sessionIDSPlitted[3]; // stable aus session id
$stableName = "";

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
$userVerwarnt = $user['Verwarnt'];
$userGesperrt = $user['Gesperrt'];


if (isset($_POST['submit'])) {
  $error = false;
	$vorname =  trim($_POST['vorname']);
	$nachname = trim($_POST['nachname']);
	$email = trim($_POST['Email']);
	$NameDesPferdes =trim($_POST['namedespferdes']);
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
		$statementUpdateUser = $pdo->prepare("UPDATE users SET vorname = :vorname_neu, nachname = :nachname_neu, email = :email_neu, NameDesPferdes = :NameDesPferdes_neu WHERE id = '$id'");
		$statementUpdateUser->execute(array(':vorname_neu' => $vorname, ':nachname_neu' => $nachname, ':email_neu' => $email, ':NameDesPferdes_neu' => $NameDesPferdes));   
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

/*
Check if current user is admin
*/
$con=mysqli_connect($host,$dbUser,$dbPWD,$db);
$result = mysqli_query($con,"SELECT * FROM `users` WHERE `nachname` LIKE '%{$nachname}%' AND `vorname` LIKE '%{$vorname}%'");
$row = mysqli_fetch_array($result);


?><html>
	<head>
		<title>Ihre Nutzerdaten - myStable</title>
		<meta charset="utf-8" />
		<!--<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />-->

		<link rel="stylesheet" href="../../assets/css/main.css" />
		<link rel="shortcut icon" href="../../pictures/favicon.ico" type="image/x-icon">
		<link rel="icon" href="../../pictures/favicon.ico" type="image/x-icon">
		<link rel="apple-touch-icon" sizes="57x57" href="../../pictures/favicons/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="../../pictures/favicons/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="../../pictures/favicons/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="../../pictures/favicons/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="../../pictures/favicons/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="../../pictures/favicons/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="../../pictures/favicons/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="../../pictures/favicons/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="../../pictures/favicons/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="../../pictures/favicons/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="../../pictures/favicons/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="../../pictures/favicons/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="../../pictures/favicons/favicon-16x16.png">
		<link rel="manifest" href="../../pictures/favicons/manifest.json">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="../../pictures/favicons/ms-icon-144x144.png">
		<meta name="theme-color" content="#ffffff">
		<link rel="icon" href="../pictures/favicon.ico" type="image/x-icon">


		
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
						<h1><a id="logo">myStable <em>by Technick Solutions</em></a></h1>
					<!-- Nav -->
						<nav id="nav" style="background: white;">
								<ul>
									<li title="Mein Kalendar" onmouseover="this.style.background=' #4db8ff';" onmouseout="this.style.background='white';"><a href="../calendarview.php"><img border="0" alt="calendar" src="../../pictures/icons/myicons/png/005-calendar-1.png"  width="52" height="52"></a></li>
									<li title="Meine Daten" class='current' onmouseover="this.style.background=' #4db8ff';" onmouseout="this.style.background='white';"><a href="#.php"><img border="0" alt="myentires" src="../../pictures/icons/myicons/png/008-settings.png"  width="52" height="52"></a></li>
									<?php 
										/*
											Admin only
										*/
										if ($row['adminAllowed'] == "1") {
											echo "<li title='Reiter Verwaltung' onmouseover=\"this.style.background=' #4db8ff';\" onmouseout=\"this.style.background='white'\";'><a href='alluser.php'><img border='0' alt='allusers' src='../../pictures/icons/myicons/png/001-tasks.png'  width='52' height='52'></a></li>";
											$reservation_Time = 24;				
										}
									?>
									<li title="Meine Einträge" onmouseover="this.style.background=' #4db8ff';" onmouseout="this.style.background='white';"><a href="../events/myentries.php"><img border="0" alt="myentries" src="../../pictures/icons/myicons/png/012-clipboard.png"  width="52" height="52"></a></li>
									<li title="Impressum" onmouseover="this.style.background=' #4db8ff';" onmouseout="this.style.background='white';"><a href="../impressum.php"><img border="0" alt="imprint" src="../../pictures/icons/myicons/png/013-advise.png"  width="52" height="52"></a></li>
									<li title="Datenschutz" onmouseover="this.style.background=' #4db8ff';" onmouseout="this.style.background='white';"><a href="../datenschutz.php"><img border="0" alt="datasecurity" src="../../pictures/icons/myicons/png/015-security.png"  width="52" height="52"></a></li>
									<li title="Logout" onmouseover="this.style.background=' #4db8ff';" onmouseout="this.style.background='white';"><a href="../Logout.php"><img border="0" alt="logout" src="../../pictures/icons/myicons/png/002-logout.png"  width="52" height="52"></a></li>
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
									<select id="dropdownuseractive" name="dropdownuseractive" class="form-control" readonly>
										<option selected hidden="true"><?php echo $userAktiv;?></option>
									  <option value="1">Reiter aktiv</option>
									  <option value="0">Reiter nicht mehr in Stall</option>
									</select>
								  </div>
								</div>
								
								<!-- Text input-->
								<div class="form-group">
								  <label class="col-md-4 control-label" for="meinStall">Mein Stall</label>  
								  <div class="col-md-4">
								  <input id="meinStall" name="meinStall" type="text" value="<?php 
										/*
										Get current stable
										*/
										$con=mysqli_connect($host,$dbUser,$dbPWD,$db);
										$resultStableName = mysqli_query($con,"SELECT stable_name from stable stbl inner join users usr on stbl.id = usr.stable_id where usr.id LIKE '%{$userID}%'");
										$rowStableResult = mysqli_fetch_array($resultStableName);
										$aktuellerStallname = $rowStableResult['stable_name'];
										echo "$aktuellerStallname";?>" 
									class="form-control input-md" readonly>
									
								  </div>
								</div>

								<div class="form-group">
								  <label class="col-md-4 control-label" for="reiterverwarnt">Verwarnung</label>  
								  <div class="col-md-4">
								  <?php 
									if ($userVerwarnt == "1"){
										$userVerwarnt = "Reiter wurde verwarnt.";
									}else if ($userVerwarnt == "0"){
										$userVerwarnt = "Keine derzeitige Verwarnung vorhanden.";
									}
									
								  ?>
								  <input id="reiterverwarnt" name="reiterverwarnt" type="text" value="<?php echo ($userVerwarnt);?>" class="form-control input-md" readonly>
									
								  </div>
								</div>
							<div class="form-group">
								  <label class="col-md-4 control-label" for="reitergesperrt">Sperre</label>  
								  <div class="col-md-4">
								  <?php 
									
									if ($userGesperrt == "1"){
										$userGesperrt = "Reiter ist derzeit gesperrt.";
									}else if ($userGesperrt == "0"){
										$userGesperrt = "Keine derzeitige Sperre vorhanden.";
									}
								  ?>
								  <input id="reitergesperrt" name="reitergesperrt" type="text" value="<?php echo ($userGesperrt);?>" class="form-control input-md" readonly>
									
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
								<li>&copy; Technick Solutions - myStable. All rights reserved</li>
								<li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
								<li>Icons made by <a href="http://okodesign.ru/" title="Elias Bikbulatov">Elias Bikbulatov</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></li>
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
