<?php
session_start();
include("session_timeout.php");
if(!isset($_SESSION['userid'])) {
    die('Bitte zuerst <a href="Login.php">einloggen</a>');
} 
header('Content-Type: text/html; charset=utf-8');
include("dbconnect.php");
$ini = parse_ini_file('../my_stable_config.ini');
$host = $ini["db_servername"];
$db = $ini['db_name'];


$dsn = "mysql:host=$host;dbname=$db";
$pdo = new PDO($dsn, $ini['db_user'], $ini['db_password']);
$dbUser = $ini['db_user'];
$dbPWD = $ini['db_password'];

$userid = $_SESSION['userid'];
$session_value=(isset($_SESSION['userid']))?$_SESSION['userid']:''; 
$expireDate = $_SESSION['expiryDate'];

$expireDate = $_SESSION['expiryDate'];

$date = new DateTime($expireDate);
$now = new DateTime();
if($date < $now) {
	//echo 'date is in the past';
	header("Location:licenceexpired.php");
}else{
	//echo "date is ok";
}	
$sessionIDSPlitted = explode(" ", $session_value);
$vorname = $sessionIDSPlitted[0]; // vorname aus session id
$nachname = $sessionIDSPlitted[1]; // nachname aus session id

?>
<html>
	<head>
		<title>Verwalte deinen Stall</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" href="../assets/css/main.css" />
		<link rel="shortcut icon" href="../pictures/favicon.ico" type="image/x-icon">
		<link rel="icon" href="../pictures/favicon.ico" type="image/x-icon">
	</head>
	<body >
		<div id="page-wrapper">
			<!-- Header -->
				<div id="header">
					<!-- Logo -->
						<h1><a id="logo">MyStable <em>by Technick Solutions</em></a></h1>
					<!-- Nav -->
						<nav id="nav">
							<ul>
							<li class="current"><a href="calendarview.php">Mein Kalendar</a></li>
								<li><a href="users/edituser.php">Meine Daten</a></li>
								<?php 
/*
									Check if current user is admin - otherwise page can not be visited
									*/
									$con=mysqli_connect($host,$dbUser,$dbPWD,$db);

									$result = mysqli_query($con,"SELECT * FROM `users` WHERE `nachname` LIKE '%{$nachname}%' AND `vorname` LIKE '%{$vorname}%'");
									$row = mysqli_fetch_array($result);

									if ($row['adminAllowed'] == "1") {
										echo "<li><a href='users/alluser.php'>Nutzerübersicht</a></li>";
							
							}
								?>
								<li><a href="events/myentries.php">Meine Einträge</a></li>
								
								<li><a href="impressum.php">Impressum</a></li>
								<li><a href="datenschutz.php">Datenschutz</a></li>
								<li><a href="Logout.php">Logout</a></li>
					</ul>
						</nav>
				</div>
			<!-- Main -->
				<section class="wrapper style1">
					<div class="container" style="width:100%">
						<div id="content">
							
							<h2 align="center">Willkommen in deinem Bereich <?php echo "$userid";?></h2>
							<h3 align="center">Reithallen-Plan für die Kolm-Ranch in Heigenkam</h3>
							<h4 style="color: red" align="center">Hinweis: Aktuell kann maximal 1 Stunde am Stück gebucht werden.<br/>Längere Zeiten sind derzeit nur über mehrere Buchung verfügbar.</h4>
						</div>
						
						<br/>
						<!-- Calender integration -->
						<div class="container" style="height: 100%; width: 100%">
							<div id="calendar" style=" width: 65%"></div>
						</div>
					</div>
					<br/>
					<br/>
				</section>
				
			<!-- Footer -->
				<div  id="footer">
					<div  class="container" style="width: 90%">
						<div class="row">
							<section  class="col-6 col-12-narrower">
								<h3>Schreiben Sie uns eine Nachricht</h3>
								<form class="form-horizontal" action="sendRequestMailInSystem.php" method="post" enctype="multipart/form-data">
									<div class="row gtr-50">
										<div class="col-6 col-12-mobilep">
											<input type="text" name="name" id="name" placeholder="Name" />
										</div>
										<div class="col-6 col-12-mobilep">
											<input type="email" name="email" id="email" placeholder="Email" />
										</div>
										<div class="col-12">
											<textarea name="message" id="message" placeholder="Nachricht" rows="5"></textarea>
										</div>
										<div class="col-12">
											<ul class="actions">
												<li><input name="submit" value="Abfrage abschicken" type="submit" class="button alt" value="Send Message" /></li>
											</ul>
										</div>
									</div>
								</form>
							</section>
							<section >
							<br/><br/>
								<img align="center" src="../pictures/logoPNG.png"/>
							</section>
						</div>
					</div>

					<!-- Icons -->
						<!--<ul class="icons">
							<li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
							<li><a href="#" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
							<li><a href="#" class="icon fa-github"><span class="label">GitHub</span></a></li>
							<li><a href="#" class="icon fa-linkedin"><span class="label">LinkedIn</span></a></li>
							<li><a href="#" class="icon fa-google-plus"><span class="label">Google+</span></a></li>
						</ul>-->

					<!-- Copyright -->
						<div class="copyright">
							<ul class="menu">
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
		
		
		<link rel='stylesheet' href='fullcalendar/fullcalendar.css' />
		<script src='fullcalendar/lib/jquery.min.js'></script>
		<script src='fullcalendar/lib/moment.min.js'></script>
		<script src='fullcalendar/fullcalendar.min.js'></script>
		<script src='fullcalendar/locale/de.js'></script>
		<!--<script src="fcbasic.js"></script>-->
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
		<script type = "text/javascript">
         <!--
            function getConfirmation() {
               var retVal = confirm("Do you want to continue ?");
               if( retVal == true ) {
                  document.write ("User wants to continue!");
                  return true;
               } else {
                  document.write ("User does not want to continue!");
                  return false;
               }
            }
         //-->
      </script>     
		<script>
				
		window.mobilecheck = function() {
		  var check = false;
		  (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
		  return check;
		};				
		
		var username='<?php echo $session_value;?>';
		 
		 
		 
		$(document).ready(function() {
	   
	   
	   var calendar = $('#calendar').fullCalendar({

		displayEventTime: false,
		locale: 'de',
		editable:true,
		selectOverlap: false,
		timeFormat: 'HH:mm',
		 customButtons: {
			myCustomButton: {
			  text: '30 Minuten ',
			  click: function() {
				alert('clicked the 30 min button!');
			  }
			},
			myCustomButton2:{
			  text: '60 Minuten ',
			  click: function() {
				alert('clicked the 30 min button!');
			  }
			}
		  },
		defaultView: window.mobilecheck() ? 'basicDay' : 'agendaWeek',
		header:{
		 left:'prev,next today',
		 center:'title',
		 right:'agendaWeek,agendaDay'
		},
		/*
		max reservation time set to one hour : 02.02.2019
		*/
		selectAllow: function(selectInfo) { 
			 var duration = moment.duration(selectInfo.end.diff(selectInfo.start));
			 return duration.asHours() <= 1;
		},
		  

		businessHours: {
		  
		  dow: [ 1, 2, 3, 4,5,6,0 ], 

		  start: '07:00', 
		  end: '21:00', 
		},
		minTime: '07:00:00',
        maxTime: '21:00:00',
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
		 //var id = event.id;
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
