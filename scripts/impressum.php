<?php
session_start();

if(!isset($_SESSION['userid'])) {
    die('Bitte zuerst <a href="login.php">einloggen</a>');
}
include("dbconnect.php");
$ini = parse_ini_file('../my_stable_config.ini');
$host = $ini["db_servername"];
$db = $ini['db_name'];

$dsn = "mysql:host=$host;dbname=$db";
$pdo = new PDO($dsn, $ini['db_user'], $ini['db_password']);
$dbUser = $ini['db_user'];
$dbPWD = $ini['db_password'];

$expireDate = $_SESSION['expiryDate'];

$userid = $_SESSION['userid'];
$session_value=(isset($_SESSION['userid']))?$_SESSION['userid']:''; 
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
	
$con=mysqli_connect($host,$dbUser,$dbPWD,$db);
/*
Check if current user is admin
*/

$result = mysqli_query($con,"SELECT * FROM `users` WHERE `nachname` LIKE '%{$nachname}%' AND `vorname` LIKE '%{$vorname}%'");
$row = mysqli_fetch_array($result);


	
?>
<html>
	<head>
		<title>Impressum - myStable</title>
		<meta charset="utf-8" />
		<!--<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />-->
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
						<h1><a id="logo">myStable <em>by Technick Solutions</em></a></h1>
					<!-- Nav -->
						<nav id="nav" style="background: white;">
							<ul>
								<li title="Mein Kalendar" onmouseover="this.style.background=' #4db8ff';" onmouseout="this.style.background='white';" ><a href="calendarview.php"><img border="0" alt="calendar" src="../pictures/icons/myicons/png/005-calendar-1.png"  width="52" height="52"></a></li>
								<li title="Meine Daten" onmouseover="this.style.background=' #4db8ff';" onmouseout="this.style.background='white';"><a href="users/edituser.php"><img border="0" alt="myentires" src="../pictures/icons/myicons/png/008-settings.png"  width="52" height="52"></a></li>
								<?php 
									/*
										Admin only
									*/
									if ($row['adminAllowed'] == "1") {
										echo "<li title='Reiter Verwaltung' onmouseover=\"this.style.background=' #4db8ff';\" onmouseout=\"this.style.background='white'\";'><a href='users/alluser.php'><img border='0' alt='allusers' src='../pictures/icons/myicons/png/001-tasks.png'  width='52' height='52'></a></li>";
										$reservation_Time = 24;				
									}
								?>
								<li title="Meine Einträge" onmouseover="this.style.background=' #4db8ff';" onmouseout="this.style.background='white';"><a href="events/myentries.php"><img border="0" alt="myentries" src="../pictures/icons/myicons/png/012-clipboard.png"  width="52" height="52"></a></li>
								<li title="Impressum"  class="current" onmouseover="this.style.background=' #4db8ff';" onmouseout="this.style.background='white';"><a href="#"><img border="0" alt="imprint" src="../pictures/icons/myicons/png/013-advise.png"  width="52" height="52"></a></li>
								<li title="Datenschutz" onmouseover="this.style.background=' #4db8ff';" onmouseout="this.style.background='white';"><a href="datenschutz.php"><img border="0" alt="datasecurity" src="../pictures/icons/myicons/png/015-security.png"  width="52" height="52"></a></li>
								<li title="Logout" onmouseover="this.style.background=' #4db8ff';" onmouseout="this.style.background='white';"><a href="Logout.php"><img border="0" alt="logout" src="../pictures/icons/myicons/png/002-logout.png"  width="52" height="52"></a></li>
							</ul>
						</nav>
				</div>
		
			<!-- Posts -->
				<section align="center" class="wrapper style1">
					<div class="container">
						<div class="row">
							<section >
								<div >
								<h2>Impressum</h2>
								<h3>Angaben gemäß § 5 TMG:</h3>
								<p>
								

Technick Solutions - myStable<br/>
Vertreten durch Herrn Dominik Kupka und Herrn Alexander Freitag<br/>
Amerstorfferstraße 14<br/>
81549 München<br/><br/>

<strong>Kontakt:</strong><br/>
Herr Dominik Kupka<br/>
Herr Alexander Freitag<br/>
EMail: mystableorganizer@gmail.com<br/>
								</p>
								
								<h2>Haftung für Inhalte</h2>
								<p>Als Diensteanbieter sind wir gemäß § 7 Abs.1 TMG für eigene Inhalte auf diesen Seiten nach den allgemeinen Gesetzen verantwortlich. Nach §§ 8 bis 10 TMG sind wir als Diensteanbieter jedoch nicht verpflichtet, übermittelte oder gespeicherte fremde Informationen zu überwachen oder nach Umständen zu forschen, die auf eine rechtswidrige Tätigkeit hinweisen.

Verpflichtungen zur Entfernung oder Sperrung der Nutzung von Informationen nach den allgemeinen Gesetzen bleiben hiervon unberührt. Eine diesbezügliche Haftung ist jedoch erst ab dem Zeitpunkt der Kenntnis einer konkreten Rechtsverletzung möglich. Bei Bekanntwerden von entsprechenden Rechtsverletzungen werden wir diese Inhalte umgehend entfernen.

</p><h2>Haftung für Links</h2>
<p>Unser Angebot enthält Links zu externen Webseiten Dritter, auf deren Inhalte wir keinen Einfluss haben. Deshalb können wir für diese fremden Inhalte auch keine Gewähr übernehmen. Für die Inhalte der verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber der Seiten verantwortlich. Die verlinkten Seiten wurden zum Zeitpunkt der Verlinkung auf mögliche Rechtsverstöße überprüft. Rechtswidrige Inhalte waren zum Zeitpunkt der Verlinkung nicht erkennbar.

Eine permanente inhaltliche Kontrolle der verlinkten Seiten ist jedoch ohne konkrete Anhaltspunkte einer Rechtsverletzung nicht zumutbar. Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Links umgehend entfernen.
</p>
<h2>Urheberrecht</h2>
Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten unterliegen dem deutschen Urheberrecht. Die Vervielfältigung, Bearbeitung, Verbreitung und jede Art der Verwertung außerhalb der Grenzen des Urheberrechtes bedürfen der schriftlichen Zustimmung des jeweiligen Autors bzw. Erstellers. Downloads und Kopien dieser Seite sind nur für den privaten, nicht kommerziellen Gebrauch gestattet.

Soweit die Inhalte auf dieser Seite nicht vom Betreiber erstellt wurden, werden die Urheberrechte Dritter beachtet. Insbesondere werden Inhalte Dritter als solche gekennzeichnet. Sollten Sie trotzdem auf eine Urheberrechtsverletzung aufmerksam werden, bitten wir um einen entsprechenden Hinweis. Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Inhalte umgehend entfernen.</p>
							</section>
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
								<li>&copy; Technick Solutions - myStable. All rights reserved</li>
								<li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
								<li>Icons made by <a href="http://okodesign.ru/" title="Elias Bikbulatov">Elias Bikbulatov</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></li>
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