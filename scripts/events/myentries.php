<?php
session_start();

if(!isset($_SESSION['userid'])) {
    die('Bitte zuerst <a href="../Login.php">einloggen</a>');
}
//include("../dbconnect.php");
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
$userID = $sessionIDSPlitted[2]; // user id aus session id
$stableID = $sessionIDSPlitted[3]; // stable aus session id


$con=mysqli_connect($host,$dbUser,$dbPWD,$db);
// Check connection
if (mysqli_connect_errno()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
    
?>
<html>
	<head>
		<title>Ihre Kalendereinträge (My-Stable)</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="/Content/font-awesome/css/font-awesome.min.css" />
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
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
	<body  class="is-preload">
		<div style="background-color: white"  id="page-wrapper"align="center">
			<!-- Header -->
				<div id="header">
					<!-- Logo -->
						<h1><a id="logo">MyStable <em>by Technick Solutions</em></a></h1>
					<!-- Nav -->
						<nav id="nav">
							<ul>
							<li ><a href="../calendarview.php">Mein Kalendar</a></li>
							<li ><a href="../users/edituser.php">Meine Daten</a></li>
							<?php 
															/*
								Check if current user is admin - otherwise page can not be visited
								*/
								$con=mysqli_connect($host,$dbUser,$dbPWD,$db);

								$result = mysqli_query($con,"SELECT * FROM `users` WHERE `nachname` LIKE '%{$nachname}%' AND `vorname` LIKE '%{$vorname}%'");
								$row = mysqli_fetch_array($result);

								if ($row['adminAllowed'] == "1") {
									echo "<li><a href='../users/alluser.php'>Reiter Verwaltung</a></li>";
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
							<li class="current"><a >Meine Einträge</a></li>
							<li><a href="../impressum.php">Impressum</a></li>
							<li><a href="../datenschutz.php">Datenschutz</a></li>
							<li><a href="../Logout.php">Logout</a></li>					
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
						$result = mysqli_query($con,"SELECT * FROM `events` WHERE `title` LIKE '%{$nachname}%' AND `title` LIKE '%{$vorname}%' and `stable_id` LIKE '%{$stableID}%'");
						$count = 1;
						echo "<table id='exportTable' class='table'><thead><tr>";
						echo"<th scope='col'><b>Nr.</b></th><th scope='col'><b>Wochentag</b></th><th scope='col'><b>Datum</b></th><th scope='col'><b>Startzeit</b></th><th scope='col'><b>Endzeit</b></th><th scope='col'><b>Name des Events</b></th></tr></thead><tbody>";
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
								echo " <th scope='row'><b>".$count."</b></th>  <th scope='row'>".$dayOfWeek  ."</th>  <th scope='col'>".date('d.m.Y', $dateOfEvent)."</th> <td>" . date('H:i', $dateOfEvent)."</td><td>".date('H:i', $EnddateOfEvent)."</td><td>".$eventTitle."</td></tr>";
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
						$result = mysqli_query($con,"SELECT * FROM `events` WHERE `title` LIKE '%{$nachname}%' AND `title` LIKE '%{$vorname}%' and `stable_id` LIKE '%{$stableID}%'");
						$count = 1;
						echo "<table class='table'><thead><tr>";
						echo"<th scope='col'><b>#</b></th><th scope='col'><b>Wochentag</b></th><th scope='col'><b>Datum</b></th><th scope='col'><b>Startzeit</b></th><th scope='col'><b>Endzeit</b></th><th scope='col'><b>Name des Events</b></th></tr></thead><tbody>";
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
								echo " <th scope='row'><b>".$count."</b></th>  <th scope='row'>".$dayOfWeek  ."</th>  <th scope='col'>".date('d-m-Y', $dateOfEvent)."</th> <td>" . date('H:i', $dateOfEvent)."</td><td>".date('H:i', $EnddateOfEvent)."</td><td>".$eventTitle."</td></tr>";
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
							<li><img  src="../../pictures/logoPNG.png"/></li><br/>
								<li>&copy; Technick Solutions - My Stable Organizer. All rights reserved</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
							</ul>
						</div>
				</div>

		<!-- Scripts -->
			<script src="../assets/js/jquery.min.js"></script>
			<script src="../assets/js/jquery.dropotron.min.js"></script>
			<script src="../assets/js/browser.min.js"></script>
			<script src="../assets/js/breakpoints.min.js"></script>
			<script src="../assets/js/util.js"></script>
			<script src="../assets/js/main.js"></script>

			<script type="text/javascript" src="tableExport.js">
			<script type="text/javascript" src="jquery.base64.js">	
			<script type="text/javascript" src="jspdf/libs/sprintf.js">
			<script type="text/javascript" src="jspdf/jspdf.js">
			<script type="text/javascript" src="jspdf/libs/base64.js">

	  
	</body>
</html>
