<?php 
session_start();
$ini = parse_ini_file('../my_stable_config.ini');
$host = $ini["db_servername"];
$db = $ini['db_name'];
$dsn = "mysql:host=$host;dbname=$db";
$pdo = new PDO($dsn, $ini['db_user'], $ini['db_password']);
$checkLogin=true;
if(isset($_GET['login'])) {
    $email = $_POST['email'];
    $passwort = $_POST['passwort'];
	
    $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $result = $statement->execute(array('email' => $email));
	$user = $statement->fetch();
    
    //Überprüfung des Passworts
    if ($user !== false && password_verify($passwort, $user['passwort']) && $user['active'] == "1") {
		$_SESSION['userid'] = $user['vorname'] ." ". $user['nachname']." ". $user['id']." ". $user['stable_id'] ;
		$_SESSION['expiryDate'] = $user['ExpiryDate'];
		$checkLogin= true;
		$_SESSION['message'] = "Die Eingabe war erfolgreich<br>";
		
		$statementGetStableObject = $pdo->prepare("SELECT stable_object.id AS objectId FROM stable_object INNER JOIN users ON stable_object.stable_id = users.stable_id WHERE users.id = :id");
		$resultGetStableObject = $statementGetStableObject->execute(array('id' => $user['id']));
		$StableObject = $statementGetStableObject->fetch();
		if ($StableObject != false){
			$StableObject['id'];
			header ("Location: calendarview.php?id=".$StableObject[0]);
		}
		
    } else {
			$checkLogin = false;
			$_SESSION['message'] = "E-Mail oder Passwort ist ungültig<br>";
    }
}
?>
<html>
	<head>
		<title>Login - myStable</title>
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
										<li><a href="../preise.html">Preise</a></li>
									</ul>
								</li>
								<li>
									<a href="#">Registrierung</a>
									<ul>
										<li><a href="newstable.php">Neuer Stall</a></li>
										<li><a href="registerpage.php">Mitglieder</a></li>
									</ul>
										
								</li>
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
				<?php	
				} else {
					?>
						<div class="container">
						
						</div>
		<?php
				}
				
				?>
					<div class="container">
						<div id="content">

							<!-- Content -->
							<form action="?login=1" method="post">
								E-Mail:<br>
								<input type="email" size="40" maxlength="250" name="email"><br><br>
								 
								Passwort:<br>
								<input type="password" size="40"  maxlength="250" name="passwort"><br>
								
								<input type="submit" value="Abschicken"><br/><br/>
								<a  href="forgottpassword.php">Passwort vergessen?</a>

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