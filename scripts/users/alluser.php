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

/*
Check if current user is admin
*/

?>
<html>
	<head>
		<title>Alle Nutzer (My-Stable)</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />

		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
		<link rel="stylesheet" href="../../assets/css/main.css" />
		<link rel="shortcut icon" href="../../pictures/favicon.ico" type="image/x-icon">
		<link rel="icon" href="../../pictures/favicon.ico" type="image/x-icon">

		
	</head>
	
	<body class="is-preload">
		<div style="background-color: white" id="page-wrapper" align="center">
			<!-- Header -->
				<div id="header">
					<!-- Logo -->
						<h1><a id="logo">MyStable <em>by Technick Solutions</em></a></h1>
					<!-- Nav -->
					
						<nav id="nav">
							<ul>
								<li ><a href="../calendarview.php">Mein Kalendar</a></li>
								<li ><a href="edituser.php">Meine Daten</a></li>
								
								<?php 
									/*
									Check if current user is admin - otherwise page can not be visited
									*/
									$con=mysqli_connect($host,$dbUser,$dbPWD,$db);

									$result = mysqli_query($con,"SELECT * FROM `users` WHERE `nachname` LIKE '%{$nachname}%' AND `vorname` LIKE '%{$vorname}%'");
									$row = mysqli_fetch_array($result);

									if ($row['adminAllowed'] == "1") {
										echo "<li class='current'><a>Reiter Verwaltung</a></li>";
									 }
								?>								
								<li ><a href="../events/myentries.php">Meine Einträge</a></li>
								<li><a href="../impressum.php">Impressum</a></li>
								<li><a href="../datenschutz.php">Datenschutz</a></li>
								<li><a href="../Logout.php">Logout</a></li>					
						</ul>
					</nav>
				</div>
				
			<!-- Main -->
				<section class="wrapper style1" style="width: 80%" align="center">
					<div style="width: 100%">
					
					<h2>Hier sehen Sie alle Nutzer Ihres Stalls</h2><br/><br/>
					<div style="width: 100%; height: 400px; overflow-y: scroll;">
						
					<?php 
						$result = mysqli_query($con,"SELECT * FROM `users`WHERE  `stable_id` LIKE '%{$stableID}%'");
						$count = 1;
						echo "<table class='table' >
								<thead>
									<tr>";
								echo"<th scope='col'><b>#</b></th>
									 <th scope='col'><b>Vorname</b></th>
									 <th scope='col'><b>Nachname</b></th>
									 <th scope='col'><b>Name des Pferdes</b></th>
									 <th scope='col'><b>Aktiv seit</b></th>
									 <th scope='col'><b>Lizenz bis</b></th>
									 <th scope='col'><b>Reiter aktiv</b></th>
									 <th scope='col'><b>Reiter verwarnen</b></th>
									 <th scope='col'><b>Reiter sperren</b></th>

									 </tr>
								</thead>
							<tbody>";
						$date = date('d-m-Y H:i');
						while($row = mysqli_fetch_array($result)){
							setlocale(LC_TIME, 'de_DE', 'deu_deu');
							$idDB = $row['id'];
							$vornameDB = $row['vorname'];
							$nachnameDB = $row['nachname'];
							$NameDesPferdesDB = $row['NameDesPferdes'];
							$aktivSeitDB = $row['created_at'];
							$lizenzBisDB = $row['ExpiryDate'];
							$nutzerAktivDB = $row['active'];
							$nutzerVerwarntDB = $row['Verwarnt'];			
							$nutzerGesperrtDB = $row['Gesperrt'];			
							
							switch ($nutzerAktivDB) {
								case "1":
									$nutzerAktivDB = "Reiter aktiv";
									break;
								case "0":
									$nutzerAktivDB = "Reiter nicht aktiv";
									break;
								default:
									break;
							}
							echo "<td scope='row'><b>".$count."</b></td>
							      <td scope='row'>".$vornameDB  ."</td>
								  <td scope='row'>".$nachnameDB  ."</td>
								  <td scope='row'>".$NameDesPferdesDB  ."</td>  
								  <td scope='col'>". date('d.m.Y  H:i:s', strtotime($aktivSeitDB))."</td> 
								  <td>".  date('d.m.Y', strtotime($lizenzBisDB))."</td>
								  <td>".$nutzerAktivDB."</td>";
							if ($nutzerAktivDB == "Reiter aktiv"){
								switch($nutzerVerwarntDB){
									case "0":
										echo"<td>
											<form name='frmVerwarnen' action='userverwaltung.php' method='post'>
												<input type='hidden' name='itemid' value=".$idDB.">
												<input style='background-color: #FFFF00' type='submit' name='verwarnenButton' value='Verwarnen'>
											</form>
										  </td>";
										  echo "<td>
											<form name='frmSperren' action='userverwaltung.php' method='post' >
												<input type='hidden' name='itemid' value=".$idDB.">
												<input style='background-color: #e7e7e7;  pointer-events: none;' type='submit' disabled name='sperrenButton' value='Sperren'>
											</form>
										  </td>";
										break;
									  case "1":
										  if ($nutzerGesperrtDB == 0){
												echo"<td>
												<form name='frmVerwarnen' action='userverwaltung.php' method='post' >
													<input type='hidden' name='itemid' value=".$idDB.">
													<input style='background-color: #4CAF50' type='submit' name='verwarnenAufhebenButton' value='Verwarnung aufheben' >
												</form>
											  </td>";
												echo "<td>
												<form name='frmSperren' action='userverwaltung.php' method='post' >
													<input type='hidden' name='itemid' value=".$idDB.">
													<input style='background-color: #f44336' type='submit' name='sperrenButton' value='Sperren'>
												</form>
											  </td>";
											}else if ($nutzerGesperrtDB == 1){
												echo"<td>
												<form name='frmVerwarnen' action='userverwaltung.php' method='post' >
													<input type='hidden' name='itemid' value=".$idDB.">
													<input style='background-color: #e7e7e7; pointer-events: none;' type='submit' disabled name='verwarnenAufhebenButton' value='Verwarnung aufheben' >
												</form>
											  </td>";
												echo "<td>
												<form name='frmSperren' action='userverwaltung.php' method='post' disabled>
													<input type='hidden' name='itemid' value=".$idDB.">
													<input style='background-color: #4CAF50;' type='submit' name='sperrenAufhebenButton'  value='Sperre aufheben' >
												</form>
											  </td>";
											}									  
										break;									
								}
							}else{
									echo"<td>
										<form name='frmVerwarnen' action='userverwaltung.php' method='post'>
											<input type='hidden' name='itemid' value=".$idDB.">
											<input style='background-color: #e7e7e7; pointer-events: none;' disabled type='submit' name='verwarnenButton' value='Verwarnen'>
										</form>
									  </td>";
									  echo "<td>
										<form name='frmSperren' action='userverwaltung.php' method='post' >
											<input type='hidden' name='itemid' value=".$idDB.">
											<input style='background-color: #e7e7e7;  pointer-events: none;' type='submit' disabled name='sperrenButton' value='Sperren'>
										</form>
									  </td>";
									
							}
							
							
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
		
		
		<link rel='stylesheet' href='fullcalendar/fullcalendar.css' />
		<script src='fullcalendar/lib/jquery.min.js'></script>
		<script src='fullcalendar/lib/moment.min.js'></script>
		<script src='fullcalendar/fullcalendar.min.js'></script>
		<script src='fullcalendar/locale/de.js'></script>
		<script src="fcbasic.js"></script>
		<style>
			#calendar {
			width: 100%;
			height:60%;
			display: block;
			margin-left: auto;
			margin-right: auto;
			}

			.centered {
			text-align: center;
			}
			#calendar {
			width: 70%;
			height:30%;
			display: block;
			margin-left: auto;
			margin-right: auto;
			}

			.centered {
			text-align: center;
			}

		</style>
		<script>
				
		 var username='<?php echo $session_value;?>';
		 
		 
		$(document).ready(function() {
	   
	   
	   var calendar = $('#calendar').fullCalendar({

    
		locale: 'de',
		editable:true,
		selectOverlap: false,
		timeFormat: 'hh:mm',
		header:{
		 left:'prev,next today',
		 center:'title',
		 right:'agendaDay,agendaWeek'
		},
		  

		businessHours: {
		  
		  dow: [ 1, 2, 3, 4,5,6,0 ], 

		  start: '07:00', 
		  end: '21:00', 
		},
		
		selectable:true,
		selectHelper:true,
		
		eventConstraint: "businessHours",
		events: 'load.php',
						

		
		select: function(start, end, allDay){
			//  var title = <?php $userid; ?>;
		 var eventName = prompt("Bitte gib den Namen deines Pferdes ein.");
		 
		 if(eventName){
		  var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
		  var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
		  
		  eventName = eventName + ": " + username;
		  $.ajax({		  
		   url:"insert.php",
		   type:"POST",
		   data:{title:eventName, start:start, end:end},
		   success:function()
		   {
			location.reload();
			calendar.fullCalendar('refetchEvents');
			alert("Added Successfully");
			
		   }
		  })
		 }
		},

		editable:true,
		eventResize:function(event)
		{
		 var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
		 var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
		 var title = event.title;
		 var id = event.id;
		 $.ajax({
		  url:"update.php",
		  type:"POST",
		  data:{title:title, start:start, end:end, id:id},
		  success:function(){
			location.reload();
		   calendar.fullCalendar('refetchEvents');
		   alert('Event Update');
		  }
		 })
		},

		eventDrop:function(event){
		var id = event.id;
		var eventTitle = event.title;
		var isUserAllowedToUpdate = eventTitle.includes(username);
		if (isUserAllowedToUpdate){
		 var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
		 var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
		 var title = event.title;
		 var id = event.id;
		 $.ajax({
		  url:"update.php",
		  type:"POST",
		  data:{title:title, start:start, end:end, id:id},
		  success:function()
		  {
			  location.reload();
		   calendar.fullCalendar('refetchEvents');
		   alert("Event Updated");
		  }
		 });
		}else{
						location.reload()
						window.alert("Dieses event gehört " + eventTitle + " - du bist nicht berechtigt es zu ändern.");

		}
		},

		eventClick:function(event){
		var id = event.id;
		var eventTitle = event.title;
		var isUserAllowedToDelete = eventTitle.includes(username);
		if (isUserAllowedToDelete){
			// darf event löschen
			if(confirm("Bist du dir sicher, dass du deinen Eintrag löschen möchtest?")){
		  //var id = event.id;
		  $.ajax({
		   url:"delete.php",
		   type:"POST",
		   data:{id:id},
		   success:function(){
			   location.reload();
			calendar.fullCalendar('refetchEvents');
			alert("Event Removed");
		   }
		  })
		 }
		}else{
			window.alert("Dieses event gehört " + eventTitle + " - du bist nicht berechtigt es zu löschen.");
		}
		},		
	   });	   
	   calendar = $('#calendar').fullCalendar('changeView', 'agendaWeek');
	  });
	  </script>
	  
	</body>
</html>
