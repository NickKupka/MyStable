<?php 
session_start();
include ("dbconnect.php");
$ini = parse_ini_file('../my_stable_config.ini');
$host = $ini["db_servername"];
$dbPDO = $ini['db_name'];
$dsn = "mysql:host=$host;dbname=$dbPDO";
$pdo = new PDO($dsn, $ini['db_user'], $ini['db_password']);
$checkLogin=true;
$userTrue = false;
$email = "";
if(isset($_POST) & !empty($_POST)){

    $email = $_POST['email'];
    $statement = $pdo->prepare("SELECT * FROM `users` WHERE email = :email");
    $result = $statement->execute(array('email' => $email));
	$user = $statement->fetch();
    
    //Überprüfung des Passworts
    if ($user !== false && $user['active'] == "1") {
		$userTrue = true;    
		$randomPWD = randomPassword();
		$passwort_hash = password_hash($randomPWD, PASSWORD_DEFAULT);
		
		 $queryUpdate = "UPDATE `users` SET passwort= :passwort WHERE email= :email";
		 $statementUpdate = $pdo->prepare($queryUpdate);
		 $statementUpdate->execute(
		  array(
		   ':passwort'  => $passwort_hash,
		   ':email' => $email,
		  ));
		$count = $statementUpdate->rowCount();

		if($count > 0) {     
			$php = $ini["php_path"];
			$checkLogin= true;
			$_SESSION['message'] = "Die Eingabe war erfolgreich<br>";
			exec("$php sendMailNewPWD.php $email $randomPWD");
            $showFormular = false;
		} else {
			$checkLogin= false;
			$_SESSION['message'] = "Bei der Eingabe ist leider einer unerwarteter Fehler aufgetreten. Bitte versuchen Sie es erneut.<br>";
		}
	}else{
		$checkLogin = false;
		$_SESSION['message'] = "E-Mail Adresse ist im System nicht vorhanden oder der User ist bereits inaktiv.<br>";
	}
  
}




function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}
?>
<html>
	<head>
		<title>Passwort vergessen - myStable</title>
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >

		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" >

		<link rel="stylesheet" href="../assets/css/forgottpasswordstyle.css" >

		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
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
										<!--<li>
											<a href="#">Weitere Infos</a>
											<ul>
												<li><a href="#">Info1</a></li>
												<li><a href="#">Info2</a></li>
												<li><a href="#">Info3</a></li>
											</ul>
										</li>-->
									</ul>
								</li>
								<li><a href="registerpage.php">Registrierung</a></li>
								<li class="current"><a href="Login.php">Login</a></li>
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
				<?php } ?>
				
				<?php	
				if ($userTrue == true){
					?>
					<style type="text/css">#passwortVergessenHeader{ display:none;} #passwortVergessenForm{ display:none;</style>
						<div align="center" class="container">	
							<h2>Vielen Dank.</h2>
							<h2>Wir haben Ihnen eine E-Mail mit Ihrem neuen Passwort gesendet.</h2>
							<a  href="Login.php">Zurück zum Login</a>

						</div>
				<?php
				}
				?>

				
				<div id="passwortVergessenHeader" align="center">
				<h2 class="form-signin-heading">Passwort vergessen</h2>
				<p>Bitte geben Sie Ihre E-Mail Adresse an.</p>
					</div>
					<div id="passwortVergessenForm" class="container">
						<div id="content">
						<form class="form-signin" method="POST">	
							<div class="input-group">
								<span class="input-group-addon" id="basic-addon1">@</span>
								<input type="text" name="email" class="form-control" placeholder="E-Mail Adresse" required>
							</div>
							<br/>
							<button class="btn btn-lg btn-primary btn-block" type="submit">Neues Passwort anfordern</button>
							<a class="btn btn-lg btn-primary btn-block" href="Login.php">Zurück zum Login</a>
						</form>		
						</div>
					</div>
				</section>



			<!-- Footer -->
				<div id="footer">
				

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

		<!-- Scripts -->
			<script src="../assets/js/jquery.min.js"></script>
			<script src="../assets/js/jquery.dropotron.min.js"></script>
			<script src="../assets/js/browser.min.js"></script>
			<script src="../assets/js/breakpoints.min.js"></script>
			<script src="../assets/js/util.js"></script>
			<script src="../assets/js/main.js"></script>

	</body>
</html>