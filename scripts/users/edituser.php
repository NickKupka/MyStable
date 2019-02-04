<?php
session_start();

if(!isset($_SESSION['userid'])) {
    die('Bitte zuerst <a href="login.php">einloggen</a>');
}
include("../dbconnect.php");
$ini = parse_ini_file('../../my_stable_config.ini');
$host = $ini["db_servername"];
$db = $ini['db_name'];

$dsn = "mysql:host=$host;dbname=$db";
$pdo = new PDO($dsn, $ini['db_user'], $ini['db_password']);


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

$userEMail = $user['email'];
$userAktiv = $user['active'];
$userPferd = $user['NameDesPferdes'];
$userAngelegtAm = $user['created_at'];
$userLaueftAusAm = $user['ExpiryDate'];

if(isset($_GET['editUser'])) {
    $error = false;
	$vorname =  $_POST['vorname'];
	$nachname = $_POST['nachname'];
	$email = $_POST['email'];
	$NameDesPferdes =$_POST['namedespferdes'];
	
  
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
		$statementCheckUser = $pdo->prepare("SELECT * FROM users WHERE email = :email");
		$statementCheckUser->execute(array(':vorname' => $vorname, ':nachname' => $nachname));   
		$userCheck = $statementCheckUser->fetch();
    }else{
		echo "error occured";
	}
    
    if(!$error) {    
		$statementUpdateUser = $pdo->prepare("UPDATE users (nachname, email, NameDesPferdes) VALUES ('$nachname', '$email', '$NameDesPferdes') WHERE `email` = :email");
		$statementUpdateUser->execute(array(':vorname' => $vorname, ':nachname' => $nachname));   
		$userUpdate = $statementUpdateUser->fetch();
		
		if($userUpdate != false) {     
			// update hat funktioniert
			header("Location: edituser.php");
        } else {
            echo 'Beim Abspeichern ist leider ein Fehler aufgetreten<br>';
        }
    }else{
		echo "can't do anything";
	}		
}

?><html>
	<head>
		<title>Verwalte deinen Stall</title>
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
	<body >
		<div id="page-wrapper">
			<!-- Header -->
				<div id="header">
					<!-- Logo -->
						<h1><a href="../../index.html" id="logo">MyStable <em>by Technick Solutions</em></a></h1>
					<!-- Nav -->
						<nav id="nav">
							<ul>
							<li ><a href="../calendarview.php">Home</a></li>
								<li><a href="../Logout.php">Logout</a></li>
								
								<li><a href="../impressum.html">Impressum</a></li>
							</ul>
						</nav>
				</div>
			<!-- Main -->
				<section class="wrapper style1">
					<div align="center" class="container">
						<form class="form-horizontal" action="?editUser=1" method="post">
								<fieldset>

								<!-- Form Name -->
								<legend>Editiere hier Deine eigenen Daten.</legend>

								<!-- Text input-->
								<div class="form-group">
								  <label class="col-md-4 control-label" for="Vorname">Vorname</label>  
								  <div class="col-md-4">
								  <input id="vorname" name="vorname" type="text" size="100" value="<?php echo $vorname;?>" placeholder="" class="form-control input-lg" readonly></input>
								  </div>
								</div>

								<!-- Text input-->
								<div class="form-group">
								  <label class="col-md-4 control-label" for="nachname">Nachname</label>  
								  <div class="col-md-4">
								  <input id="nachname" name="nachname" type="text"  value="<?php echo $nachname;?>" placeholder="" class="form-control input-md">
									
								  </div>
								</div>

								<!-- Text input-->
								<div class="form-group">
								  <label class="col-md-4 control-label" for="namedespferdes">Name des Pferdes</label>  
								  <div class="col-md-4">
								  <input id="namedespferdes" name="namedespferdes" type="text" value="<?php echo $userPferd;?>" placeholder="" class="form-control input-md">
									
								  </div>
								</div>

								<!-- Text input-->
								<div class="form-group">
								  <label class="col-md-4 control-label" for="email">E-Mail Adresse</label>  
								  <div class="col-md-4">
								  <input id="email" name="email" type="text"value="<?php echo $userEMail;?>"  placeholder="" class="form-control input-md">
									
								  </div>
								</div>

								<!-- Text input-->
								<div class="form-group">
								  <label class="col-md-4 control-label" for="aktivseit">Registriert seit</label>  
								  <div class="col-md-4">
								  <input id="aktivseit" name="aktivseit" type="text" value="<?php echo $userAngelegtAm;?>" placeholder="" class="form-control input-md" readonly>
									
								  </div>
								</div>

								<!-- Text input-->
								<div class="form-group">
								  <label class="col-md-4 control-label" for="lizenzlaeuftbis">Lizenz läuft bis zum</label>  
								  <div class="col-md-4">
								  <input id="lizenzlaeuftbis" name="lizenzlaeuftbis" type="text" value="<?php echo $userLaueftAusAm;?>" placeholder="" class="form-control input-md" readonly>
									
								  </div>
								</div>

								<!-- Select Basic -->
								<div class="form-group">
								  <label class="col-md-4 control-label" for="dropdownuseractive">Nutzerdaten</label>
								  <div class="col-md-4">
									<select id="dropdownuseractive" name="dropdownuseractive" class="form-control">
									  <option value="aktiv">Reiter aktiv</option>
									  <option value="nichtaktiv">Reiter nicht mehr in Stall</option>
									</select>
								  </div>
								</div>


								<!-- Button -->
								<div class="form-group">
								  <label class="col-md-4 control-label" for="speicherButton"></label>
								  <div class="col-md-4">
									<button id="speicherButton" name="speicherButton" class="btn btn-primary">Speichern</button>
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
