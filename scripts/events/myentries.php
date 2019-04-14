<?php
session_start();
include("../session_timeout.php");
if(!isset($_SESSION['userid'])) {
    header("Location:../bittezuersteinloggen.php");
	exit;
}
header('Content-Type: text/html; charset=utf-8');
include("../dbconnect.php");
$ini = parse_ini_file('../../my_stable_config.ini');
$host = $ini["db_servername"];
$db = $ini['db_name'];

$dsn = "mysql:host=$host;dbname=$db";
$pdo = new PDO($dsn, $ini['db_user'], $ini['db_password']);
$dbUser = $ini['db_user'];
$dbPWD = $ini['db_password'];
$con=mysqli_connect($host,$dbUser,$dbPWD,$db);

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


// Check connection
if (mysqli_connect_errno()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$resultStableObjektName = mysqli_query($con,"SELECT stable_object_name, stable_object.id AS objectId FROM stable_object INNER JOIN users ON stable_object.stable_id = users.stable_id WHERE users.id LIKE '%{$userID}%'");
$resultStableName = mysqli_query($con,"SELECT stable_name from stable stbl inner join users usr on stbl.id = usr.stable_id where usr.id LIKE '%{$userID}%'");
    
?>
<html>
	<head>
		<title>Ihre Kalendereinträge - myStable</title>
		<meta charset="utf-8" />
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="/Content/font-awesome/css/font-awesome.min.css" />
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
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
	<script type="text/javascript" src="../js/fullscreen.js"></script>
	<div id="page-wrapper">
		<div style="background-color: white"  id="page-wrapper" align="center">
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
							</li>	
							<li title="Meine Daten" onmouseover="this.style.background=' #4db8ff';" onmouseout="this.style.background='white';"><a href="../users/edituser.php"><img border="0" alt="myentires" src="../../pictures/icons/myicons/png/008-settings.png"  width="52" height="52"></a></li>
							
							<?php 
															/*
								Check if current user is admin - otherwise page can not be visited
								*/
								$con=mysqli_connect($host,$dbUser,$dbPWD,$db);

								$result = mysqli_query($con,"SELECT * FROM `users` WHERE `nachname` LIKE '%{$nachname}%' AND `vorname` LIKE '%{$vorname}%'");
								$row = mysqli_fetch_array($result);

								if ($row['adminAllowed'] == "1") {
									//echo "<li title='Stall Verwaltung' onmouseover=\"this.style.background=' #4db8ff';\" onmouseout=\"this.style.background='white'\";'><a href='../stable/editstable.php'><img border='0' alt='allconfig' src='../../pictures/icons/myicons/png/settings.png'  width='52' height='52' ></a></li>";
									echo "<li title='Reiter Verwaltung' onmouseover=\"this.style.background=' #4db8ff';\" onmouseout=\"this.style.background='white'\";' ><a href='../users/alluser.php'><img border='0' alt='allusers' src='../../pictures/icons/myicons/png/001-tasks.png'  width='52' height='52'></a></li>";
									$reservation_Time = 24;				
								}

								$result = mysqli_query($con,"SELECT * FROM `events` WHERE `title` LIKE '%{$nachname}%' AND `title` LIKE '%{$vorname}%'");
								$date = date('d-m-Y H:i');
								while($row = mysqli_fetch_array($result)){
									setlocale(LC_TIME, 'de_DE', 'deu_deu');
		
									$eventStartTime = $row['start_event'];
									$eventEndTime = $row['end_event'];
									
									$dateOfEvent = strtotime($eventStartTime);
									$EnddateOfEvent = strtotime($eventEndTime);
		
									//Convert the date string into a unix timestamp.
									$unixTimestamp = strtotime($eventStartTime);
		
									//Get the day of the week using PHP's date function.
									$dayOfWeek = date("l", $unixTimestamp);
								}
							
							?>
							<li title="Meine Einträge" class="current" onmouseover="this.style.background=' #4db8ff';" onmouseout="this.style.background='white';"><a href="#"><img border="0" alt="myentries" src="../../pictures/icons/myicons/png/012-clipboard.png"  width="52" height="52"></a></li>
							<li title="Impressum"  onmouseover="this.style.background=' #4db8ff';" onmouseout="this.style.background='white';"><a href="../impressum.php"><img border="0" alt="imprint" src="../../pictures/icons/myicons/png/013-advise.png"  width="52" height="52"></a></li>
							<li title="Datenschutz" onmouseover="this.style.background=' #4db8ff';" onmouseout="this.style.background='white';"><a href="../datenschutz.php"><img border="0" alt="datasecurity" src="../../pictures/icons/myicons/png/015-security.png"  width="52" height="52"></a></li>
							<li title="Logout" onmouseover="this.style.background=' #4db8ff';" onmouseout="this.style.background='white';"><a href="../Logout.php"><img border="0" alt="logout" src="../../pictures/icons/myicons/png/002-logout.png"  width="52" height="52"></a></li>
												
					</ul>
						</nav>
				</div>
			<!-- Main -->
				<section class="wrapper style1"  style="width: 80%" align="center">
					<div style="width: 100%">
					<br/><br/>
					<h2>Hier sehen Sie Ihre zukünftigen Reservierungen</h2><br/><br/>
					<div style="width: 100%; height: 300px; overflow-y: scroll;">
					<!-- <button id="exportButton" class="btn btn-lg btn-danger clearfix"><span class="fa fa-file-pdf-o"></span>Export PDF</button> -->
					<!--	<a href="#" class="exportBtn btn btn-info" id="btnExportToXLSNew" onClick ="$('#exportTable').tableExport({type:'pdf',escape:'false'});"><span class="far fa-file-excel"> Excel</a> -->
					<!--	<a href="#" class="exportBtn btn btn-info" id="btnExportToPDFNew"><span class="fa fa-file-pdf-o"> PDF</a>
						<a href="#" class="exportBtn btn btn-info" id="btnPrintNew" ><span class="fas fa-print"> Print</a>  -->
				

					<?php 
						$result = mysqli_query($con,"SELECT events.*, stable_object.stable_object_name AS objectName FROM events INNER JOIN stable_object ON (events.stable_object_id = stable_object.id) WHERE events.title LIKE '%{$nachname}%' AND events.title LIKE '%{$vorname}%' and events.stable_id LIKE '%{$stableID}%'");
						$count = 1;
						echo "<table id='exportTable' class='table'><thead><tr>";
						echo"<th scope='col'><b>Nr.</b></th><th scope='col'><b>Wochentag</b></th><th scope='col'><b>Datum</b></th><th scope='col'><b>Startzeit</b></th><th scope='col'><b>Endzeit</b></th><th scope='col'><b>Wo?</b></th></tr></thead><tbody>";
						$date = date('d-m-Y H:i');
						while($row = mysqli_fetch_array($result)){
							setlocale(LC_TIME, 'de_DE', 'deu_deu');

							$eventStartTime = $row['start_event'];
							$eventEndTime = $row['end_event'];
							
							$dateOfEvent = strtotime($eventStartTime);
							$EnddateOfEvent = strtotime($eventEndTime);

							//Convert the date string into a unix timestamp.
							$unixTimestamp = strtotime($eventStartTime);

							//Get the day of the week using PHP's date function.
							$dayOfWeek = date("l", $unixTimestamp);

							//Print out the day that our date fell on.
							switch ($dayOfWeek) {
								case "Sunday":
									$dayOfWeek = "Sonntag";
								break;
								case "Monday":
									$dayOfWeek = "Montag";
								break;
								case "Tueday":
									$dayOfWeek = "Dienstag";
									break;
								case "Wednesday":
									$dayOfWeek = "Mittwoch";
									break;
								case "Thursday":
									$dayOfWeek = "Donnerstag";
									break;
								case "Friday":
									$dayOfWeek = "Freitag";
									break;
								case "Saturday":
									$dayOfWeek = "Samstag";
									break;
								default:
									break;
							};

							
							

							if ($date > date('d-m-Y H:i', $dateOfEvent)) {
								# current time is greater than 2010-05-15 16:00:00
								# in other words, 2010-05-15 16:00:00 has passed
							}else{							
								$eventTitle = $row['title'];
								$stableObjectName = $row['objectName'];
								echo "<th scope='row'><b>".$count."</b></th>  <th scope='row'>".$dayOfWeek  ."</th>  <th scope='col'>".date('d.m.Y', $dateOfEvent)."</th> <td>" . date('H:i', $dateOfEvent)."</td><td>".date('H:i', $EnddateOfEvent)."</td><td>".$stableObjectName."</td></tr>";
								$count = $count + 1;
							}
						}
						echo "</tbody></table>";
						  //echo "Start: " . $row['start_event'] . " Ende : " . $row['end_event'] . " " . $row['title'] ; //these are the fields that you have stored in your database table employee
						  //echo "<br />";
						?>"
					</div>
					<!--<link rel="stylesheet" type="text/css" href="http://www.shieldui.com/shared/components/latest/css/light/all.min.css" />
					<script type="text/javascript" src="http://www.shieldui.com/shared/components/latest/js/shieldui-all.min.js"></script>
					<script type="text/javascript" src="http://www.shieldui.com/shared/components/latest/js/jszip.min.js"></script>

					<script type="text/javascript">
						jQuery(function ($) {
							$("#exportButton").click(function () {
								// parse the HTML table element having an id=exportTable
								var dataSource = shield.DataSource.create({
									data: "#exportTable",
									schema: {
										type: "table",
										fields: {
											Nr.: { type: String },
											Wochentag: { type: String },
											Datum: { type: String }
											Startzeit: { type: String }
											Endzeit: { type: String }
											Name des Events: { type: String }
										}
									}
								});

								// when parsing is done, export the data to PDF
								dataSource.read().then(function (data) {
									var pdf = new shield.exp.PDFDocument({
										author: "My Stable",
										created: new Date()
									});

									pdf.addPage("a4", "portrait");

									pdf.table(
										50,
										50,
										data,
										[						
											{ field: "Nr.", title: "Nr", width: 50 },
											{ field: "Wochentag", title: "Wochentag", width: 150 },
											{ field: "Datum", title: "Datum", width: 100 }
											{ field: "Startzeit", title: "Beginn", width: 100 }
											{ field: "Endzeit", title: "Ende", width: 100 }
											{ field: "Name des Events", title: "Name des Events", width: 100 }
											],
										{
											margins: {
												top: 50,
												left: 50
											}
										}
									);

									pdf.saveAs({
										fileName: "MeineStallTermine"
									});
								});
							});
						});
					</script>-->
					<br/><br/><br/><br/>
					<h2>Hier sehen Sie Ihre vergangenen Reservierungen</h2><br/><br/>
					<div style="width: 100%; height: 300px; overflow-y: scroll;">
					<!-- 	<a href="#" class="exportBtn btn btn-info" id="btnExportToXLSOld"><span class="far fa-file-excel"> Excel</a>
						<a href="#" class="exportBtn btn btn-info" id="btnExportToPDFOld"><span class="fa fa-file-pdf-o"> PDF</a>
						<a href="#" class="exportBtn btn btn-info" id="btnPrintOld" ><span class="fas fa-print"> Print</a>  -->
					<?php 
						$result = mysqli_query($con,"SELECT events.*, stable_object.stable_object_name AS objectName FROM events INNER JOIN stable_object ON (events.stable_object_id = stable_object.id) WHERE events.title LIKE '%{$nachname}%' AND events.title LIKE '%{$vorname}%' and events.stable_id LIKE '%{$stableID}%'");
						$count = 1;
						echo "<table class='table'><thead><tr>";
						echo"<th scope='col'><b>Nr.</b></th><th scope='col'><b>Wochentag</b></th><th scope='col'><b>Datum</b></th><th scope='col'><b>Startzeit</b></th><th scope='col'><b>Endzeit</b></th><th scope='col'><b>Wo?</b></th></tr></thead><tbody>";
						$date = date('d-m-Y H:i');
						while($row = mysqli_fetch_array($result)){
							setlocale(LC_TIME, 'de_DE', 'deu_deu');

							$eventStartTime = $row['start_event'];
							$eventEndTime = $row['end_event'];
							
							$dateOfEvent = strtotime($eventStartTime);
							$EnddateOfEvent = strtotime($eventEndTime);

							//Convert the date string into a unix timestamp.
							$unixTimestamp = strtotime($eventStartTime);

							//Get the day of the week using PHP's date function.
							$dayOfWeek = date("l", $unixTimestamp);

							//Print out the day that our date fell on.
							switch ($dayOfWeek) {
								case "Sunday":
									$dayOfWeek = "Sonntag";
								break;
								case "Monday":
									$dayOfWeek = "Montag";
								break;
								case "Tuesday":
									$dayOfWeek = "Dienstag";
									break;
								case "Wednesday":
									$dayOfWeek = "Mittwoch";
									break;
								case "Thursday":
									$dayOfWeek = "Donnerstag";
									break;
								case "Friday":
									$dayOfWeek = "Freitag";
									break;
								case "Saturday":
									$dayOfWeek = "Samstag";
									break;
								default:
									break;
							};

							if ($date > date('d-m-Y H:i', $dateOfEvent)) {
								$eventTitle = $row['title'];
								$stableObjectName = $row['objectName'];

								echo " <th scope='row'><b>".$count."</b></th>  <th scope='row'>".$dayOfWeek  ."</th>  <th scope='col'>".date('d.m.Y', $dateOfEvent)."</th> <td>" . date('H:i', $dateOfEvent)."</td><td>".date('H:i', $EnddateOfEvent)."</td><td>".$stableObjectName."</td></tr>";
								$count = $count + 1;
							}else{							
								
							}
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
				
			
		</div>
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

		<!-- Scripts -->
			<script src="../../assets/js/jquery.min.js"></script>
			<script src="../../assets/js/jquery.dropotron.min.js"></script>
			<script src="../../assets/js/browser.min.js"></script>
			<script src="../../assets/js/breakpoints.min.js"></script>
			<script src="../../assets/js/util.js"></script>
			<script src="../../assets/js/main.js"></script>

			<script type="text/javascript" src="tableExport.js">
			<script type="text/javascript" src="jquery.base64.js">	
			<script type="text/javascript" src="jspdf/libs/sprintf.js">
			<script type="text/javascript" src="jspdf/jspdf.js">
			<script type="text/javascript" src="jspdf/libs/base64.js">

	  
	</body>
</html>
