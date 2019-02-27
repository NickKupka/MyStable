<?php
$showFormular = true; //Variable ob das Registrierungsformular anezeigt werden soll
include ("dbconnect.php");
$ini = parse_ini_file('../my_stable_config.ini');
$checkLogin= true;
$registerSuccess = false;
if(isset($_GET['register'])) {
    $error = false;
    $vorname = trim($_POST['vorname']);
	$nachname = trim($_POST['nachname']);
	$email = trim($_POST['email']);
    $passwort = trim($_POST['passwort']);
    $passwort2 = trim($_POST['passwort2']);
	$NameDesPferdes = trim($_POST['NameDesPferdes']);
	$LizenzschluesselVomStall = trim($_POST['LizenzschluesselVomStall']);

  
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
    }     
    if(strlen($vorname) == 0) {
        $error = true;
    }
	if(strlen($nachname) == 0) {
        $error = true;
    }
	if(strlen($passwort) == 0) {
        $error = true;
    }
    if($passwort != $passwort2) {
        $error = true;
    }
	
	if(strlen($NameDesPferdes) == 0) {
        $error = true;
    }
    
	if(strlen($LizenzschluesselVomStall) == 0) {
        $error = true;
    }
	
    if(!$error) { 
		$select = mysqli_query($db, "SELECT * FROM users WHERE `email` = '".$email."'") or exit(mysqli_error($connectionID));
    }else{
		$_SESSION['message'] = "error occured";
	}
    
    //Keine Fehler, wir können den Nutzer registrieren
    if(!$error) {    
		//exec("java -jar licensekeygenerator/dist/LicenseKeyGenerator.jar 2>&1", $output);
		//$licensekey = $output[0];
		//$licensekey = generateLicenceKey();
		//echo $licensekey;
		$passwort_hash = password_hash($passwort, PASSWORD_DEFAULT);
		//echo $passwort_hash;

		
		/*
		Lizenzlaufzeit herausfinden vom Stall
		*/
		$findExpiryDateQuery = "SELECT * FROM users WHERE `LicenseKey` = '" .$LizenzschluesselVomStall."'";
		$findExpiryDateResult = mysqli_query($db, $findExpiryDateQuery);
		while ($row = mysqli_fetch_array($findExpiryDateResult)) {
			$currentExpiryDate = $row['ExpiryDate'];
			$currentStableID = $row['stable_id'];
		}

		$eintragen = mysqli_query($db, "INSERT INTO users (vorname, nachname, email, passwort, LicenseKey, NameDesPferdes, stable_id, ExpiryDate, active) VALUES ('$vorname', '$nachname', '$email', '$passwort_hash','$LizenzschluesselVomStall','$NameDesPferdes','$currentStableID','$currentExpiryDate','1')");
		if($eintragen) {     
			$php = $ini["php_path"];
			$checkLogin= true;
			$_SESSION['message'] = "Die Eingabe war erfolgreich<br>";
			exec("$php mailservice/sendMail.php $email $vorname $nachname $NameDesPferdes");
			//header("Location: Login.php");
            $showFormular = false;
			$registerSuccess = true;
        } else {
			$checkLogin= false;
			$_SESSION['message'] = "Bei der Eingabe ist leider einer unerwarteter Fehler aufgetreten. Bitte versuchen Sie es erneut.<br>";
        }
    }else{
		$_SESSION['message'] = "can't do anything";
	}		
}
function generateLicenceKey() {
$length = 19;
$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$charactersLength = strlen($characters);
$trenner ="-";
$randomString = '';
$counterForTrenner = 0;
for ($i = 0; $i < $length; $i++) {
	if ($counterForTrenner == 4){
		$counterForTrenner = 0;
		$randomString .= $trenner;
	}else{
		$randomString .= $characters[rand(0, $charactersLength - 1)];
		$counterForTrenner = $counterForTrenner+1;
	}
}
//echo $randomString;
return $randomString;
}
?>
<html>
	<head>
		<title>Registrierung bei einem Stall - myStable</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../assets/css/main.css" />
		<link rel="shortcut icon" href="../pictures/favicon.ico" type="image/x-icon">
		<link rel="icon" href="../pictures/favicon.ico" type="image/x-icon">
		<link rel="apple-touch-icon" sizes="57x57" href="../pictures/favicons/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="../pictures/favicons/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="../pictures/favicons/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="../pictures/favicons/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="../pictures/favicons/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="../pictures/favicons/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="../pictures/favicons/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="../pictures/favicons/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="../pictures/favicons/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="../pictures/favicons/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="../pictures/favicons/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="../pictures/favicons/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="../pictures/favicons/favicon-16x16.png">
		<link rel="manifest" href="../pictures/favicons/manifest.json">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="../pictures/favicons/ms-icon-144x144.png">
		<meta name="theme-color" content="#ffffff">
		<link rel="icon" href="../pictures/favicon.ico" type="image/x-icon">
	</head>
	<body class="is-preload">
	  
		<div id="page-wrapper">

			<!-- Header -->
				<div id="header">

					<!-- Logo -->
						<h1><a href="../index.html" id="logo">myStable <em>by Technick Solutions</em></a></h1>

					<!-- Nav -->
						<nav id="nav">
							<ul>
								<li ><a href="../index.html">Home</a></li>
								<li>
									<a href="#">Infos</a>
									<ul>
										<li><a href="../aboutmystable.html">Was ist <em>myStable</em></a></li>
										<li><a href="../ueberuns.html">Über uns</a></li>
										<li><a href="../preise.html">Preise</a></li>
									</ul>
								</li>
								<li class="current">
									<a href="#">Registrierung</a>
									<ul>
										<li><a href="newstable.php">Neuer Stall</a></li>
										<li><a href="registerpage.php">Mitglieder</a></li>
									</ul>
										
								</li>
								<li><a href="Login.php">Login</a></li>
								<li><a href="../impressum.html">Impressum</a></li>
								<li><a href="../datenschutz.html">Datenschutz</a></li>
							</ul>
						</nav>

				</div>

			<!-- Main -->
				<section class="wrapper style1">

				<?php
				if($checkLogin == false){
					?>
					<div class="container">
						<div class="panel-group">
							<div class="panel panel-danger">
							<div class="panel-heading">Es ist ein Fehler aufgetreten</div>
								<div class="panel-body"> <?php if(isset($_SESSION["message"])) echo $_SESSION["message"]; ?> </div>
							</div>

						</div>
					</div>
				<?php	
				}


				if($registerSuccess == true){
				
					?>
				
				<style type="text/css">#registerDiv{ display:none;} </style>
						<div align="center" class="container">	
							<h2>Die Registrierung war erfolgreich.</h2>
							<h2>Sie können Sich nun in Ihrem Stall einloggen.</h2>
							<a  href="Login.php">Zum Login</a>

						</div>

				<?php
				}
				
				?>
					<div id="registerDiv" class="container">
						<h2 align="center" >Registrierung eines neuen Nutzers.</h2>
						<p align="center">Hier können sich Reiter eines Stalls zu ihrem Stall mit dem Lizenzschlüssel registrieren.</p>
						<p align="center"> Bitte füllen Sie alle notwendigen Informationen aus.</p>


							<form action="?register=1" method="post" accept-charset="utf-8">
								<div class="form-group">
									Vorname: *<br>
									<input type="text" size="40" maxlength="250" name="vorname" required><br><br>
									
									Nachname: *<br>
									<input type="text" size="40" maxlength="250" name="nachname" required><br><br>
									
									E-Mail: *<br>
									<input type="email" size="40" maxlength="250" name="email" required><br><br>
									
									Bitte geben Sie den Namen Ihres Pferdes ein:*<br>
									<input id="NameDesPferdes" type="text" name="NameDesPferdes" />
									
									<br>
									Passwort: *<br>
									<input type="password" size="40"  maxlength="250" name="passwort" required><br>
									 
									Passwort wiederholen: *<br>
									<input type="password" size="40" maxlength="250" name="passwort2" required><br>
										<!-- Stall auswählen : TODO-->
									 
									 Lizenzschlüssel vom Stall:*<br>
									<input id="LizenzschluesselVomStall" type="text" name="LizenzschluesselVomStall" />
									 
									<br><br>
									<input type="submit" value="Abschicken">
								</div>
							</form>
							 


					</div>
				</section>

			<!-- Footer -->
				<div id="footer">
					<div class="container">
						<div class="row">
							
							<section  class="col-6 col-12-narrower">
								<h3>Schreiben Sie uns eine Nachricht</h3>
								<form class="form-horizontal" action="mailservice/sendRequestMail.php" method="post" enctype="multipart/form-data">
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
								<li>&copy; Technick Solutions - myStable. All rights reserved</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
							</ul>
						</div>

				</div>

		</div>
			<script>
				/*
				Unhide custom textfield if custom is used and restrict nr for intervals to 1
				*/
				 $("#inputHorseName1").hide();
				$( "#anzahlDerPferde" ).change(function () {
				if($( "option:selected", this ).text()=="1"){
				   $("#inputHorseName1").show();
				   
				}else{
				   $("#inputHorseName1").hide();        
				   
				}
			});
			</script>
		<!-- Scripts -->
			<script src="../assets/js/jquery.min.js"></script>
			<script src="../assets/js/jquery.dropotron.min.js"></script>
			<script src="../assets/js/browser.min.js"></script>
			<script src="../assets/js/breakpoints.min.js"></script>
			<script src="../assets/js/util.js"></script>
			<script src="../assets/js/main.js"></script>

	</body>
</html>