<?php 
//Bitte ein- oder auskommentieren, falls Wartungsmodus nötig
/*
    header("Location: maintenance/Maintenance.php"); 
    exit; 
*/
?>
<?php
	require __DIR__ . '/vendor/autoload.php';
	use Zend\Authentication\AuthenticationService;
	use Zend\Authentication\Adapter\Ldap as LdapAdapter;
	use Zend\Ldap\Ldap;
	use Zend\Authentication\Storage\StorageInterface;
	include ("dbconnect.php");
	if(!session_id()) session_start();
	include ("session_timeout.php");
	//This is a quick and dirty hack to remove the
	//Zend\Session dependency. This is done by storing everything in
	//the $_SESSION['auth'] subkey. It works and is a *TEMPORARY* fix.
	class PlainSessionStorage implements StorageInterface{
		/**
		 * Returns true if and only if storage is empty.
		 *
		 * @return boolean
		 * @throws \Zend\Authentication\Exception\ExceptionInterface If it is
		 *     impossible to determine whether storage is empty.
		 */
		public function isEmpty() {
			return empty($_SESSION['auth']);
		}
	
		/**
		 * Returns the contents of storage.
		 *
		 * Behavior is undefined when storage is empty.
		 *
		 * @return mixed
		 * @throws \Zend\Authentication\Exception\ExceptionInterface If reading
		 *     contents from storage is impossible
		 */
	
		public function read() : mixed {
			return $_SESSION['auth'];
		}
	
		/**
		 * Writes $contents to storage.
		 *
		 * @param  mixed $contents
		 * @return void
		 * @throws \Zend\Authentication\Exception\ExceptionInterface If writing
		 *     $contents to storage is impossible
		 */
		public function write($contents) : void {
			//var_dump($contents);
			$_SESSION['auth'] = $contents;
		}
	
		/**
		 * Clears contents from storage.
		 *
		 * @return void
		 * @throws \Zend\Authentication\Exception\ExceptionInterface If clearing
		 *     contents from storage is impossible.
		 */
	
		public function clear() : void {
			return;
		}
	}

	$usererror = "";
	$detailerror = "";

	if($_SERVER["REQUEST_METHOD"] == "POST") {
		//Inititalize zend's auth module
		$auth = new AuthenticationService();
		$auth->setStorage(new PlainSessionStorage());

		//Provide a config for our ldap adapter and try to authenticate with $_POST. 
		$username = $_POST['username'];
		$password = $_POST['password'];

		$ldapServerOptions = [
			'host'                   => 'remusrv001.recompli.de', //'10.30.30.1',
			'useStartTls'            => false,
			//'username'               => 'CN=tu-softwareentwicklung,OU=3000-Users,OU=Muenchen,OU=RECOMPLI,DC=recompli,DC=de', --> FUNKTIONIERT SEIT DEM 18.12.2018 nicht mehr
			'username'               => 'tu-softwareentwicklu', // FIX - WORKAROUND FUER DEN FEHLER VOM 18.12.2018
			'password'               => '!PsDCF2kVfXeut$',
			'accountDomainName'      => 'recompli.de',
			'accountDomainNameShort' => 'RECOMPLI',
			'baseDn'                 => 'OU=3000-Users,OU=Muenchen,OU=RECOMPLI,DC=recompli,DC=de',
		];

		$config = [
			'server1' => $ldapServerOptions,
		];

		//TODO: Figure out if LDAP injection is possible here. It probably doesn't make a difference
		//since the user only has read access, but still. 
		$adapter = new LdapAdapter($config, $username, $password); 
		$result = $auth->authenticate($adapter);
		// var_dump($result);
		// var_dump($_SESSION);

		if(!$result->isValid()) {
			$usererror = "<p><strong>Fehler bei der Authentifizierung:</strong> <span class=\"highlight\">" . $result->getMessages()[0]
				. "</span>.</p>";
				if (!empty($result->getMessages()[1])) {
					$detailerror = "<small><p class=\"details\"><em>Informationen für Programmierer</em>: <span class=\"highlight\">" . $result->getMessages()[1] . "</span>.</p></small>";
				}
			
			// Messages from position 2 and up are informational messages from the LDAP server:
			foreach ($result->getMessages() as $i => $message) {
				if ($i < 2) continue;

				// Potentially log the $message
				echo "<!-- DEBUG: " . $message . " -->\n";
			}
		} else {
			$hm = $adapter->getAccountObject();
			// var_dump($hm);
			// die();

			$admins = [
				"Dominik Kupka",
				"Alexander Freitag",
				"Frederic Schönberger",
				"Martin Gellner",
				"Marius Bertele",
			];
			
			
			if(!session_id()) session_start();
			$_SESSION["first"] = $hm->givenname;
			$_SESSION["last"] = $hm->sn;
			$_SESSION['role_id'] = 0;
			$isAllowedToAccess = NULL;	
			
			$checkIfUserDBAllowed = "Select IsDBAllowed FROM users WHERE sAMAccountName = '$username'";
			$resultDBAllowed = mysqli_query($db, $checkIfUserDBAllowed);
			if (!$resultDBAllowed){
				die("Ungültige Abfrage: " .mysqli_error($db));
			}
			while ( $row = mysqli_fetch_row ( $resultDBAllowed ) ) {
							
				foreach ( $row as $data ) {
					$isAllowedToAccess = $data;
				}
			}
			if ($isAllowedToAccess == 1){			
				if(in_array(($hm->givenname . " " . $hm->sn), $admins)) $_SESSION["role_id"] = 8;
				
				$_SESSION["username"] = $username;
				$_SESSION['filename'] = "loggedIn";
				header("Location:index.php");
			}else{
				$usererror = "<p><span><strong>User ist nicht berechtigt das System zu nutzen.</br>Bitte wenden Sie sich an die <a target='_blank' href='http://hikx.aen/index.html#anfragesoftwareentwicklung'>Softwareentwicklung</a></strong></span></p>";
			}
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<link href="https://fonts.googleapis.com/css?family=Oswald|Raleway|Source+Code+Pro" rel="stylesheet"> 
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>TMS - Login</title>
	<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico" />
	<style>
		#loginDiv {
			border-radius: 10px;
			border: 1px solid #ddd;
			background: linear-gradient(to right bottom, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.9));
			box-shadow: 0px 0px 40px 0px #fff;
			padding: 2em;
			text-align: center;
		}
		
		.notice {
			border-radius: 10px;
			background: #000;
			padding: 1em;
			text-align: center;
			color: white;
			box-shadow: 0px 0px 40px 0px #999;
			font-size: 12pt;
		}
		
		.notice.strong {
			background: red;
		}

		h1, h2 {
			font-family: 'Oswald', 'Arial', sans-serif;
		}

		input[type="text"], input[type="password"] {
			font-family: inherit;
			font-size: inherit;
			border: none;
			border-bottom: 2px solid black;
			background: rgba(255, 255, 255, 0);
			padding: 0em;
		}

		input[type="submit"] {
			background: inherit;
			padding: .2em;
			border: none;
			border-bottom: 2px solid black;
		}

		body {
			font-size: 14pt;
			font-family: 'Raleway', sans-serif; 
			background: url("./images/background.jpg");
			background-repeat: no-repeat;
			background-size: cover;
			display: flex;
			justify-content: center;
			align-items: center;
			height: 99vh;
			overflow: hidden;
		}

		.wrapper {
			background-color: inherit;
			list-style-type: none;
			padding: 0;
			border-radius: 3px;
		}
		.form-row {
			display: flex;
			justify-content: flex-end;
			padding: .5em;
		}
		.form-row > label {
			padding: .5em 1em .5em 0;
			flex: 1;
		}
		.form-row > input {
			flex: 2;
		}
		.form-row > input,
		.form-row > button {
			padding: .5em;
		}
		.form-row > button {
			background: gray;
			color: white;
			border: 0;
		}

		.error {
			font-size: 10pt;
			text-align: left;
		}

		.error .highlight {
			background: rgba(255, 0,0, 0.8);
			color: white;
			padding: 0em .2em;
			font-family: "Source Code Pro", monospace;
			letter-spacing: 0.2px;
		}

		.error .details .highlight {
			background: rgba(0,0,0,0);
			color: grey;
		}
		
		#maintenance_time {
			font-family: "Source Code Pro", monospace;
		}
	</style>
</head>
<body>
<style>
	.test_warning {
		background: linear-gradient(-45deg, #EE7752, #E73C7E, #23A6D5, #23D5AB);
		background-size: 400% 400%;
		animation: Gradient 15s ease infinite;
		position: fixed;
		width: 15em;
		z-index: 90000000000;
		margin: 0;
		padding: .2em;
		color: black;
		transform: rotateZ(45deg);
		right: -3em;
		top: 3em;
		box-shadow: 0 0 15px #ccc;
	}

	@keyframes Gradient {
		0% {
			background-position: 0% 50%
		}
		50% {
			background-position: 100% 50%
		}
		100% {
			background-position: 0% 50%
		}
	}

	.test_warning p {
		font-family: "Verdana", sans-serif !important;
		color: white;
		margin: 0;
		padding: 0;
		text-transform: uppercase;
		font-weight: 700;
		text-align: center;
	}
</style>
<?php if ($_SERVER[HTTP_HOST] == "test_tms.aen" || $_SERVER[HTTP_HOST] == "localhost"): ?>
<div class="test_warning">
	<p><i class="fas fa-flask"></i>	Testumgebung</p>
</div>
<?php endif; ?>
	<div class="iuuiewh">
		<!--<div class="notice strong">
			Das TMS geht in <span id="maintenance_time">2d 05h 25m 02s</span> in den Wartungsmodus!
		</div>
		<script>
		// Set the date we're counting down to
		var countDownDate = new Date("Sep 27, 2018 15:00:00").getTime();

		// Update the count down every 1 second
		var x = setInterval(function() {

		  // Get todays date and time
		  var now = new Date().getTime();

		  // Find the distance between now an the count down date
		  var distance = countDownDate - now;

		  // Time calculations for days, hours, minutes and seconds
		  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
		  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
		  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

		  var string = days + "d ";
		  if (hours < 10) string += "0";
		  string += hours + "h ";
		  if (minutes < 10) string += "0";
		  string += minutes + "m ";
		  if(seconds < 10) string += "0";
		  string += seconds + "s";
		  
		  // Display the result in the element with id="demo"
		  document.getElementById("maintenance_time").innerHTML = string;

		  // If the count down is finished, write some text 
		  if (distance < 0) {
			clearInterval(x);
			document.getElementById("maintenance_time").innerHTML = "JETZT";
		  }
		}, 1000);
		</script> -->
		<div id="loginDiv">
			<h1>TMS Login</h1>
			<div class="error"><?= $usererror; ?></div>
			<form action="./login.php" method="post">
				<ul class="wrapper">
					<li class="form-row">
						<label for="username">Username: </label>
						<input type="text" id="username" name="username" />
					</li>
					<li class="form-row">
						<label for="password">Password: </label>
						<input type="password" id="password" name="password" />
					</li>
					<li class="form-row">
						<button type="submit">Anmelden</button>
					</li>
				</ul>
			</form>
			<div class="error"><?php echo $detailerror; ?></div>
		</div>
	<!-- 	<div class="notice">
			<span class="highlight">Neu!</span> Bitte verwende Deine <strong>Windowsanmeldung</strong> zum Anmelden.
		</div> -->
	</div>
	
	<?php include("footer.php") ?>	
	<style>
		footer { display: none; }
	</style>
</body>
</html>