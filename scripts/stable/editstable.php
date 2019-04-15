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

/*
Check if current user is admin
*/
$con=mysqli_connect($host,$dbUser,$dbPWD,$db);
$result = mysqli_query($con,"SELECT * FROM `users` WHERE `nachname` LIKE '%{$nachname}%' AND `vorname` LIKE '%{$vorname}%'");
$row = mysqli_fetch_array($result);

/*
Insert new Stable Object
*/
if (isset($_POST['submit'])) {
	$error = false;
	$stableObjectName = trim($_POST['nameDesObjekts']);
	$oeffnungsTage = filter_input(INPUT_POST, "oeffnungsTage");
	$startZeit = trim($_POST['uhrzeitVon']);
	$endZeit = trim($_POST['uhrzeitBis']);
	$anzahlParallelerReiterDropwdown = filter_input(INPUT_POST, "anzahlParallelerReiterDropwdown");
	$maximaleBelegungsdauer = filter_input(INPUT_POST, "maximaleBelegungsdauer");
	
	if(strlen($stableObjectName) == 0) {
        $error = true;
    }
	
	if(strlen($oeffnungsTage) == 0) {
        $error = true;
    }
    
	if(strlen($startZeit) == 0) {
        $error = true;
    }
	
	
	if(strlen($endZeit) == 0) {
        $error = true;
    }
	
	if(strlen($anzahlParallelerReiterDropwdown) == 0){
		$error = true;
	}
	if (strlen($maximaleBelegungsdauer) == 0){
		$error = true;
	}
	echo "stableObjectName: " . $stableObjectName . "</br>";
	echo "oeffnungsTage: " . $oeffnungsTage . "</br>";
	echo "startZeit: " . $startZeit . "</br>";
	echo "endZeit: " . $endZeit . "</br>";
	echo "anzahlParallelerReiterDropwdown: " . $anzahlParallelerReiterDropwdown . "</br>";
	echo "maximaleBelegungsdauer: " . $maximaleBelegungsdauer . "</br>";


	
    if(!$error) {    
		$statementInsertNewStableObject = $pdo->prepare("INSERT INTO stable_object (stable_id, stable_object_name , max_parallel_users, object_occupancy, openinghours_start, openinghours_end, object_endurance) 
		VALUES (:stableID, :stableObjectName, :anzahlParallelerReiterDropwdown,:oeffnungsTage, :startZeit, :endZeit, :maximaleBelegungsdauer)");
		$statementInsertNewStableObject->execute(array(
		':stableID' => $stableID
		, ':stableObjectName' => $stableObjectName
		, ':anzahlParallelerReiterDropwdown' => $anzahlParallelerReiterDropwdown
		, ':oeffnungsTage' => $oeffnungsTage
		, ':startZeit' => $startZeit
		, ':endZeit' => $endZeit
		, ':maximaleBelegungsdauer' => $maximaleBelegungsdauer));   
		$count = $statementInsertNewStableObject->rowCount();

		if($count == '0'){
			"Beim Anlegen Ihrer Daten ist ein Fehler aufgetreten";
		} else {
			"Objekt wurde erfolgreich hinzugefügt erfolgreich";
			header("Location: editstable.php");
		}
    }else{
		echo "can't do anything";
	}		
}

?><html>
	<head>
		<title>Ihr Stall - myStable</title>
		<meta charset="utf-8" />
		<!--<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />-->
	
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
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


	

		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

		<!-- Popper JS -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>

		<!-- Latest compiled JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
	</head>
	<body class="is-preload">
			<!--<script type="text/javascript" src="../js/fullscreen.js"></script>-->

		<div id="page-wrapper">
			<!-- Header -->
				<div id="header">
					<!-- Logo -->
						<h1><a id="logo">myStable <em>by Technick Solutions</em></a></h1>
					<!-- Nav -->
						<nav id="nav" style="background: white;">
							<ul>
								<li>
									<a href="#" title="Mein Kalendar"  onmouseover="this.style.background=' #4db8ff';" onmouseout="this.style.background='white';"><img border="0" alt="calendar" src="../../pictures/icons/myicons/png/005-calendar-1.png"  width="52" height="52"  ></a>
									<?php		
										echo '<ul>';
										$resultStableObjektName = mysqli_query($con,"SELECT stable_object_name, stable_object.id AS objectId FROM stable_object INNER JOIN users ON stable_object.stable_id = users.stable_id WHERE users.id LIKE '%{$userID}%'");
										while ($rowStableObject = mysqli_fetch_array($resultStableObjektName)){
												echo '<li><a href="../calendarview.php?id='.$rowStableObject['objectId'] .'" >'.$rowStableObject['stable_object_name'] . '</a></li>';
										}
										
										echo '</ul>';						
									?>
							</li><li title="Meine Daten"  onmouseover="this.style.background=' #4db8ff';" onmouseout="this.style.background='white';"><a href="../users/edituser.php	"><img border="0" alt="myentires" src="../../pictures/icons/myicons/png/008-settings.png"  width="52" height="52"></a></li>
									<?php 
										/*
											Admin only
										*/
										if ($row['adminAllowed'] == "1") {
											echo "<li title='Stall Verwaltung' class='current' onmouseover=\"this.style.background=' #4db8ff';\" onmouseout=\"this.style.background='white'\";'><a href='#'><img border='0' alt='allconfig' src='../../pictures/icons/myicons/png/settings.png'  width='52' height='52' ></a></li>";
											echo "<li title='Reiter Verwaltung' onmouseover=\"this.style.background=' #4db8ff';\" onmouseout=\"this.style.background='white'\";'><a href='../users/alluser.php'><img border='0' alt='allusers' src='../../pictures/icons/myicons/png/001-tasks.png'  width='52' height='52'></a></li>";
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
					<h2>Legen Sie ein neues Stallobjekt an.</h2>
						<form class="form-horizontal" action="#" method="POST"> <!--?editUser=1 -->
								<fieldset>

								<!-- Form Name -->
								<p>Sollten sie ein neues Objekt in Ihrem Stall verwalten möchten, tragen Sie es einfach hier ein.</br>Es wird automatisch übernommen.</p>
								<!-- Text input-->
								<div class="form-group">
								  <label class="col-md-4 control-label" >Name des neues Stallobjekts</label>  
								  <div class="col-md-4">
								  <input id="nameDesObjekts" name="nameDesObjekts" type="text" placeholder="Neue tolle große Reithalle" class="form-control input-md" required>
								  </div>
								</div>

								<div class="form-group">
								  <label class="col-md-4 control-label" for="oeffnungsZeiten">Belegungstage des Objekts</label>
								  <div class="col-md-4">
									<select id="oeffnungsTage" name="oeffnungsTage" class="form-control"  required>
									  <option value="Montag bis Sonntag">Montag bis Sonntag</option>
									  <option value="Montag bis Freitag">Montag bis Freitag</option>
									  <option value="Wochenende">Wochenende</option>
									  <option value="Montag">Montag</option>
									  <option value="Dienstag">Dienstag</option>
									  <option value="Mittwoch">Mittwoch</option>
									  <option value="Donnerstag">Donnerstag</option>
									  <option value="Freitag">Freitag</option>
									  <option value="Samstag">Samstag</option>
									  <option value="Sonntag">Sonntag</option>
									  </select>
								  </div>
								</div>
								<div class="form-group">
								  <label class="col-md-2 control-label" >Uhrzeit von:</label>  
								  <div class="col-md-2">
									<input id="uhrzeitVon" name="uhrzeitVon" type="time" placeholder="07:00" class="form-control input-md" required>
								  </div>
								</div>
								<div class="form-group">
								  <label class="col-md-2 control-label" >Uhrzeit bis:</label>  
								  <div class="col-md-2">
									<input id="uhrzeitBis" name="uhrzeitBis" type="time" placeholder="22:00" class="form-control input-md" required>
								  </div>
								</div>
								
								<div class="form-group">
								  <label class="col-md-4 control-label" for="anzahlParallelerReiterDropwdown">Maximale Anzahl an Reitern auf dem Platz</label>
								  <div class="col-md-4">
									<select id="anzahlParallelerReiterDropwdown" name="anzahlParallelerReiterDropwdown" class="form-control" required>
									  <option value="1">1</option>
									  <option value="2">2</option>
									  <option value="3">3</option>
									  <option value="4">4</option>
									  <option value="5">5</option>
									  </select>
								  </div>
								</div>
								
								<div class="form-group">
								  <label class="col-md-4 control-label" for="anzahlParallelerReiterDropwdown">Maximale Belegungsdauer</label>
								  <div class="col-md-4">
									<select id="maximaleBelegungsdauer" name="maximaleBelegungsdauer" class="form-control" required>
									  <option value="00:30:00">0:30 Stunde</option>
									  <option value="00:45:00">0:45 Stunde</option>
									  <option value="01:00:00">01:00 Stunde</option>
									  <option value="01:30:00">01:30 Stunden</option>
									  <option value="02:00:00">02:00 Stunden</option>
									  </select>
								  </div>
								</div>
								<!-- Text input-->
								<div class="form-group">
								  <label class="col-md-4 control-label" for="submit">Stallobjekt anlegen</label>
								  <div class="col-md-4">
									<button id="submit" type="submit" name="submit" class="btn btn-success">Anlegen</button>
								  </div>
								</div>
								</fieldset>
								</form></br></br>
								
						<h2>Hier sehen Sie alle Ihre Stallobjekte.</h2><br/>
					<div style="width: 100%; height: 400px; overflow-y: scroll;">
						
					<?php 
						$result = mysqli_query($con,"SELECT * FROM `stable_object` WHERE  `stable_id` = '{$stableID}'");
						$count = 1;
						echo "<table class='table' >
								<thead>
									<tr>";
								echo"<th scope='col'><b>#</b></th>
									 <th scope='col'><b>Name des Objekts</b></th>
									 <th scope='col'><b>Anzahl paralleler Nutzer</b></th>
									 <th scope='col'><b>Tage</b></th>
									 <th scope='col'><b>Nutzungszeiten</b></th>
									 <th scope='col'><b>Max. Belegungsdauer</b></th>
									 <th scope='col'><b>Löschen</b></th>
									 </tr>
								</thead>
							<tbody>";
						$dateStart = date('H:i');
						$dateEnd = date("H:i", strtotime('+5 hours'));
						while($rowStableObjects = mysqli_fetch_array($result)){
							setlocale(LC_TIME, 'de_DE', 'deu_deu');
							$stableObjectID = $rowStableObjects['id'];
							$stable_id = $rowStableObjects['stable_id'];
							$maxParallelUsers = $rowStableObjects['max_parallel_users'];
							$stableObjectName = $rowStableObjects['stable_object_name'];
							$stableDays = $rowStableObjects['object_occupancy'];
							$startzeit =  substr($rowStableObjects['openinghours_start'],0,5);
							$endzeit =  substr($rowStableObjects['openinghours_end'],0,5);
							$belegungsdauer =  substr($rowStableObjects['object_endurance'],0,5);
						
						
							echo "<td scope='row'><b>".$count."</b></td>
							      <td scope='row'>".$stableObjectName ."</td>
								  <td scope='row'>".$maxParallelUsers ."</td>
								  <td scope='row'>".$stableDays."</td>
								  <td scope='row'>".$startzeit." - ".$endzeit." Uhr</td>								  
								  <td scope='row'>".$belegungsdauer." Stunde(n)</td>";

							/*echo "<td>
								<form name='frmBearbeiten' action='#' method='loadAllData' >
									<input type='hidden' name='itemid' value=".$stableObjectID.">
									<input style='background-color: blue;' pointer-events: none;' type='submit'  name='bearbeitenButton' value='Objekt Bearbeiten'>
								</form>
								</td>";*/
							echo "<td>
								<form name='frmSperren' action='stableObjectBlocked.php' method='post' >
									<input type='hidden' name='itemid' value=".$stableObjectID.">
									<input style='background-color: red;' pointer-events: none;' type='submit'  name='sperrenButton' value='Objekt Sperren'>
								</form>
								</td>";
							
							
							echo"</tr>";
							$count = $count + 1;
							
						}
						echo "</tbody></table>";
						  //echo "Start: " . $row['start_event'] . " Ende : " . $row['end_event'] . " " . $row['title'] ; //these are the fields that you have stored in your database table employee
						  //echo "<br />";
							mysqli_close($con);?>"
					</div>
					
				
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
								<li>Icons made by <a href="http://okodesign.ru/" title="Elias Bikbulatov">Elias Bikbulatov</a> and <a href="https://www.freepik.com/" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></li>
							</ul>
						</div>
				</div>
		</div>

		<!-- Scripts -->
			<script src="../../assets/js/jquery.min.js"></script>
			<script src="../../assets/js/jquery.dropotron.min.js"></script>
			<script src="../../assets/js/browser.min.js"></script>
			<script src="../../assets/js/breakpoints.min.js"></script>
			<script src="../../assets/js/util.js"></script>
			<script src="../../assets/js/main.js"></script>
			  
	</body>
</html>
