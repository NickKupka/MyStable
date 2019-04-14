<?php
$showFormular = true; //Variable ob das Registrierungsformular anezeigt werden soll
include ("dbconnect.php");
$ini = parse_ini_file('../my_stable_config.ini');
$checkLogin= true;
if(isset($_POST) & !empty($_POST)){
    $error = false;
    $vorname = trim($_POST['vorname']);
	$nachname = trim($_POST['nachname']);
	$email = trim($_POST['email']);
    $passwort = trim($_POST['passwort']);
    $passwort2 = trim($_POST['passwort2']);
	$NameDesPferdes = trim($_POST['NameDesPferdes']);
	$stableName= trim($_POST['NameDesStalls']);

  
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $checkLogin  = FALSE;
		$_SESSION['message'] = "E-Mail Adresse ist in einem ungültigen Format.<br>";
		$error = true;
    }     
    if(strlen($vorname) == 0) {
		$checkLogin  = FALSE;
		$_SESSION['message'] = "Es wurde kein Vorname eingegeben.<br>";
        $error = true;
	
    }
	if(strlen($nachname) == 0) {
        $checkLogin  = FALSE;
		$_SESSION['message'] = "Es wurde kein Nachname eingegeben.<br>";
		$error = true;
    }
	if(strlen($passwort) == 0) {
		$checkLogin  = FALSE;
		$_SESSION['message'] = "Es wurde kein Passwort eingegeben.<br>";
        $error = true;
    }
    if($passwort != $passwort2) {
		$checkLogin  = FALSE;
		$_SESSION['message'] = "Die beiden Passwörter stimmen nicht überein. Bitte versuchen Sie es erneut.<br>";
    
		$error = true;
    }
	if (strlen($stableName) == 0){
		$checkLogin  = FALSE;
		$_SESSION['message'] = "Der Stallname darf nicht leer sein. Bitte versuchen Sie es erneut.<br>";
		$error = true;
	}
	if(strlen($NameDesPferdes) == 0) {
        $checkLogin  = FALSE;
		$_SESSION['message'] = "Der Name des Pferdes darf nicht leer sein. Bitte versuchen Sie es erneut.<br>";
		$error = true;
    }
    
    if(!$error) { 
		$select = mysqli_query($db, "SELECT * FROM users WHERE `email` = '".$email."'") or exit(mysqli_error($connectionID));
    }else{
		$checkLogin  = FALSE;
	}
	$query = mysqli_query($db, "SELECT * FROM users WHERE `email` = '".$email."'");

    if (!$query){
        die('Error: ' . mysqli_error($con));
    }
	if(mysqli_num_rows($query) > 0){
		$checkLogin  = FALSE;
		$error = true;
		$_SESSION['message'] = "Der Benutzer ist bereits angelegt.<br>";
	}
    //Keine Fehler, wir können den Nutzer registrieren
    if(!$error) {
		//echo "kein error occured<br/>";
		$licensekey = generateLicenceKey();
		//echo $licensekey."<br/>";
		$passwort_hash = password_hash($passwort, PASSWORD_DEFAULT);
		//echo $passwort."<br/>";
		//echo $passwort_hash."<br/>";
		/*
		Insert new stable owner in users table
		*/
		$registerNewStableQuery = "INSERT INTO users (vorname, nachname, email, passwort, LicenseKey, NameDesPferdes, adminAllowed) VALUES ('$vorname', '$nachname', '$email', '$passwort_hash','$licensekey','$NameDesPferdes','1')";
		if ($db->query($registerNewStableQuery) === TRUE) {
			//echo "neuer user konnte angelegt werden.<br/>";
		}else{
			//echo "neuer user konnte NICHT angelegt werden.<br/>";
			$checkLogin  = FALSE;
			$_SESSION['message'] = "Der Nutzer konnte nicht angelegt werden. Bitte versuchen Sie es erneut.<br>";
		}
		/*
		Newly generated ID for stable owner
		*/
		$newIDForStableOwner = $db->insert_id;
		//echo "user id -> " . $newIDForStableOwner."<br/>";
		/*
		Insert new stable in stable table
		*/
		$insertNewStableQuery = "INSERT INTO stable (stable_name, stable_owner) VALUES ('$stableName', '$newIDForStableOwner')";
		if ($db->query($insertNewStableQuery) === TRUE) {
			//echo "neuer stall konnte angelegt werden.<br/>";
		}else{
			$checkLogin  = FALSE;
			$_SESSION['message'] = "Der Stall konnte nicht angelegt werden. Bitte versuchen Sie es erneut.<br>";
			//echo "neuer stall konnte nicht angelegt werden.<br/>";
		}
		/*
		Newly generated ID for stable
		*/
		$newIDForStable = $db->insert_id;
		//echo "stable id -> " . $newIDForStable."<br/>";
		/*
		Insert new stable object in stable_object table
		*/
		$insertNewStableObjectQuery = "INSERT INTO stable_object (stable_id, stable_object_name) VALUES ('$newIDForStable', 'Reithalle')";
		if ($db->query($insertNewStableObjectQuery) === TRUE) {
			//echo "neues stallobjekt konnte angelegt werden.<br/>";
		}else{
			$checkLogin  = FALSE;
			$_SESSION['message'] = "Das Stallobjekt konnte nicht angelegt werden. Bitte versuchen Sie es erneut.<br>";
			//echo "neues stallobjekt konnte nicht angelegt werden.<br/>";
		}
		
		/*
		Update user with stable id 
		*/
		$updateStableIdForNewStableOwner = "UPDATE users SET stable_id='$newIDForStable' WHERE email='$email'";
		if ($db->query($updateStableIdForNewStableOwner) === TRUE) {
			//echo "Record updated successfully";
			$php = $ini["php_path"];
			$checkLogin= true;
			$_SESSION['message'] = "Die Eingabe war erfolgreich<br>";
			exec("$php sendMailNewStable.php $email $licensekey $vorname $nachname $NameDesPferdes $stableName");
			header("Location: LoginWithKey.php");
            $showFormular = false;
		} else {
			$checkLogin= false;
			$_SESSION['message'] = "Bei der Eingabe ist leider einer unerwarteter Fehler aufgetreten. Bitte versuchen Sie es erneut.<br>";
		}	
    }else{
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
		<title>Registrieren Sie hier Ihren Stall - myStable</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../assets/css/main.css" />
		<link rel="shortcut icon" href="../pictures/favicon.ico" type="image/x-icon">
		<link rel="icon" href="../pictures/favicon.ico" type="image/x-icon">
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
										<li><a href="#">Preise</a></li>
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
				} else {
					?>
				<div class="container">
				
				</div>
				<?php
				}
				
				?>
					<div class="container">
						<h2 align="center" > Registrierung eines neuen Stalls</h2>
						<p align="center">Hier können sich Stallbesitzer für die Nutzung von myStable registrieren.</p>
						<p align="center"> Bitte füllen Sie als Stallbesitzer alle notwendigen Informationen aus.</p>

							<form method="post" accept-charset="utf-8">
								<div class="form-group">
									Vorname: *<br>
									<input type="text" size="40" maxlength="250" name="vorname" required><br><br>
									
									Nachname: *<br>
									<input type="text" size="40" maxlength="250" name="nachname" required><br><br>
									
									E-Mail: *<br>
									<input type="email" size="40" maxlength="250" name="email" required><br><br>
									
									Bitte geben Sie den Namen Ihres Stalls ein:*<br>
									<input id="NameDesStalls" type="text" name="NameDesStalls" required /><br><br>
									
									Bitte geben Sie den Namen Ihres Pferdes ein:*<br>
									<input id="NameDesPferdes" type="text" name="NameDesPferdes" required /><br><br>
									Passwort: *<br>
									<input type="password" size="40"  maxlength="250" name="passwort" required><br>
									 
									Passwort wiederholen: *<br>
									<input type="password" size="40" maxlength="250" name="passwort2" required><br>
									<br>
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
								<li>&copy; Technick Solutions - myStable Organizer. All rights reserved</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
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