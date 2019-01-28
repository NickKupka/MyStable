<?php
$showFormular = true; //Variable ob das Registrierungsformular anezeigt werden soll
include ("dbconnect.php");
if(isset($_GET['register'])) {
    $error = false;
    $vorname = $_POST['vorname'];
	$nachname = $_POST['nachname'];
	$email = $_POST['email'];
    $passwort = $_POST['passwort'];
    $passwort2 = $_POST['passwort2'];
	
  
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
    
    if(!$error) { 
		$select = mysqli_query($db, "SELECT * FROM users WHERE `email` = '".$_POST['email']."'") or exit(mysqli_error($connectionID));
    }
    
    //Keine Fehler, wir können den Nutzer registrieren
    if(!$error) {    
        exec("java -jar licensekeygenerator/dist/LicenseKeyGenerator.jar 2>&1", $output);
		$licensekey = $output[0];
		$passwort_hash = password_hash($passwort, PASSWORD_DEFAULT);
		$eintragen = mysqli_query($db, "INSERT INTO users (vorname, nachname, email, passwort,LicenseKey) VALUES ('$vorname', '$nachname', '$email', '$passwort_hash','$licensekey')");
        
		if($eintragen) {        
			//echo 'Du wurdest erfolgreich registriert. <a href="loginpage.html">Zum Login</a>';
			exec("C:\\xampp\\php\\php.exe C:\\xampp\\htdocs\\mystable\\scripts\\sendMail.php $email $licensekey");
			header("Location: Login.php");

            $showFormular = false;
        } else {
            echo 'Beim Abspeichern ist leider ein Fehler aufgetreten<br>';
        }
    } 
}
?>
<html>
	<head>
		<title>Registriere dich bei MyStable</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../assets/css/main.css" />
	</head>
	<body class="is-preload">
	  
		<div id="page-wrapper">

			<!-- Header -->
				<div id="header">

					<!-- Logo -->
						<h1><a href="../index.html" id="logo">MyStable <em>by Technick Solutions</em></a></h1>

					<!-- Nav -->
						<nav id="nav">
							<ul>
								<li ><a href="../index.html">Home</a></li>
								<li>
									<a href="#">Infos</a>
									<ul>
										<li><a href="#">Was ist <em>MyStable</em></a></li>
										<li><a href="#">Über uns</a></li>
										<li><a href="#">Preise</a></li>
										<li>
											<a href="#">Weitere Infos</a>
											<ul>
												<li><a href="#">Info1</a></li>
												<li><a href="#">Info2</a></li>
												<li><a href="#">Info3</a></li>
											</ul>
										</li>
									</ul>
								</li>
								<li><a href="../left-sidebar.html">Features</a></li>
								<!--<li><a href="right-sidebar.html">Right Sidebar</a></li>-->
								<li class="current"><a href="registerpage.php">Registrierung</a></li>
								<li><a href="Login.php">Login</a></li>
							</ul>
						</nav>

				</div>

			<!-- Main -->
				<section class="wrapper style1">
					<div class="container">
						<div id="content">
							<form action="?register=1" method="post">
							Vorname: *<br>
							<input type="text" size="40" maxlength="250" name="vorname"><br><br>
							
							Nachname: *<br>
							<input type="text" size="40" maxlength="250" name="nachname"><br><br>
							
							E-Mail: *<br>
							<input type="email" size="40" maxlength="250" name="email"><br><br>
							 
							Dein Passwort: *<br>
							<input type="password" size="40"  maxlength="250" name="passwort"><br>
							 
							Passwort wiederholen: *<br>
							<input type="password" size="40" maxlength="250" name="passwort2"><br><br>
							 
							<input type="submit" value="Abschicken">
							</form>
							 


						</div>
					</div>
				</section>

			<!-- Footer -->
				<div id="footer">
					<div class="container">
						<div class="row">
							<section class="col-3 col-6-narrower col-12-mobilep">
								<h3>Links to Stuff</h3>
								<ul class="links">
									<li><a href="#">Mattis et quis rutrum</a></li>
									<li><a href="#">Suspendisse amet varius</a></li>
									<li><a href="#">Sed et dapibus quis</a></li>
									<li><a href="#">Rutrum accumsan dolor</a></li>
									<li><a href="#">Mattis rutrum accumsan</a></li>
									<li><a href="#">Suspendisse varius nibh</a></li>
									<li><a href="#">Sed et dapibus mattis</a></li>
								</ul>
							</section>
							<section class="col-3 col-6-narrower col-12-mobilep">
								<h3>More Links to Stuff</h3>
								<ul class="links">
									<li><a href="#">Duis neque nisi dapibus</a></li>
									<li><a href="#">Sed et dapibus quis</a></li>
									<li><a href="#">Rutrum accumsan sed</a></li>
									<li><a href="#">Mattis et sed accumsan</a></li>
									<li><a href="#">Duis neque nisi sed</a></li>
									<li><a href="#">Sed et dapibus quis</a></li>
									<li><a href="#">Rutrum amet varius</a></li>
								</ul>
							</section>
							<section class="col-6 col-12-narrower">
								<h3>Get In Touch</h3>
								<form>
									<div class="row gtr-50">
										<div class="col-6 col-12-mobilep">
											<input type="text" name="name" id="name" placeholder="Name" />
										</div>
										<div class="col-6 col-12-mobilep">
											<input type="email" name="email" id="email" placeholder="Email" />
										</div>
										<div class="col-12">
											<textarea name="message" id="message" placeholder="Message" rows="5"></textarea>
										</div>
										<div class="col-12">
											<ul class="actions">
												<li><input type="submit" class="button alt" value="Send Message" /></li>
											</ul>
										</div>
									</div>
								</form>
							</section>
						</div>
					</div>

					<!-- Icons -->
						<ul class="icons">
							<li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
							<li><a href="#" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
							<li><a href="#" class="icon fa-github"><span class="label">GitHub</span></a></li>
							<li><a href="#" class="icon fa-linkedin"><span class="label">LinkedIn</span></a></li>
							<li><a href="#" class="icon fa-google-plus"><span class="label">Google+</span></a></li>
						</ul>

					<!-- Copyright -->
						<div class="copyright">
							<ul class="menu">
								<li>&copy; Untitled. All rights reserved</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
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
