<?php
session_start();

if(!isset($_SESSION['userid'])) {
    header("Location:bittezuersteinloggen.php");
	exit;
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
		<title>Dagtenschutz - myStable</title>
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
								<li title="Impressum"  onmouseover="this.style.background=' #4db8ff';" onmouseout="this.style.background='white';"><a href="impressum.php"><img border="0" alt="imprint" src="../pictures/icons/myicons/png/013-advise.png"  width="52" height="52"></a></li>
								<li title="Datenschutz" class="current" onmouseover="this.style.background=' #4db8ff';" onmouseout="this.style.background='white';"><a href="#"><img border="0" alt="datasecurity" src="../pictures/icons/myicons/png/015-security.png"  width="52" height="52"></a></li>
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
								<h1 >Datenschutzerklärung</h1>
							
								<div >
								<p >Verantwortliche Stelle im Sinne der Datenschutzgesetze, insbesondere der EU-Datenschutzgrundverordnung (DSGVO), ist:</p>
								<p>Herr Dominik Kupka
								<br>Herr Alexander Freitag
								<br>EMail: mystableorganizer@gmail.com
								<br>
								<br>Technick Solutions - myStable
								<br>Amerstorfferstraße 14
								<br>81549 München</p>
								</div>
								<h4 >Ihre Betroffenenrechte</h4>
								<p>Unter den angegebenen Kontaktdaten unseres Datenschutzbeauftragten können Sie jederzeit folgende Rechte ausüben:</p>
								<ul align="left">
								<li>Auskunft über Ihre bei uns gespeicherten Daten und deren Verarbeitung (Art. 15 DSGVO),</li>
								<li>Berichtigung unrichtiger personenbezogener Daten (Art. 16 DSGVO),</li>
								<li>Löschung Ihrer bei uns gespeicherten Daten (Art. 17 DSGVO),</li>
								<li>Einschränkung der Datenverarbeitung, sofern wir Ihre Daten aufgrund gesetzlicher Pflichten noch nicht löschen dürfen (Art. 18 DSGVO),</li>
								<li>Widerspruch gegen die Verarbeitung Ihrer Daten bei uns (Art. 21 DSGVO) und</li>
								<li>Datenübertragbarkeit, sofern Sie in die Datenverarbeitung eingewilligt haben oder einen Vertrag mit uns abgeschlossen haben (Art. 20 DSGVO).</li>
								</ul>
								<p>Sofern Sie uns eine Einwilligung erteilt haben, können Sie diese jederzeit mit Wirkung für die Zukunft widerrufen.</p>
								<p>Sie können sich jederzeit mit einer Beschwerde an eine Aufsichtsbehörde wenden, z. B. an die zuständige Aufsichtsbehörde des Bundeslands Ihres Wohnsitzes oder an die für uns als verantwortliche Stelle zuständige Behörde.</p>
								<p>Eine Liste der Aufsichtsbehörden (für den nichtöffentlichen Bereich) mit Anschrift finden Sie unter: <a href="https://www.bfdi.bund.de/DE/Infothek/Anschriften_Links/anschriften_links-node.html" target="_blank" rel="nofollow noopener">https://www.bfdi.bund.de/DE/Infothek/Anschriften_Links/anschriften_links-node.html</a>.</p>
								<p></p>
								<h3 >Erfassung allgemeiner Informationen beim Besuch unserer Website</h3>
								<h4 align="left" >Art und Zweck der Verarbeitung</h4>
								<p>Wenn Sie auf unsere Website zugreifen, d.h., wenn Sie sich nicht registrieren oder anderweitig Informationen übermitteln, werden automatisch Informationen allgemeiner Natur erfasst. Diese Informationen (Server-Logfiles) beinhalten etwa die Art des Webbrowsers, das verwendete Betriebssystem, den Domainnamen Ihres Internet-Service-Providers, Ihre IP-Adresse und ähnliches. </p>
								<p  align="left">Sie werden insbesondere zu folgenden Zwecken verarbeitet:</p>
								<ul align="left">
								<li>Sicherstellung eines problemlosen Verbindungsaufbaus der Website,</li>
								<li>Sicherstellung einer reibungslosen Nutzung unserer Website,</li>
								<li>Auswertung der Systemsicherheit und -stabilität sowie</li>
								<li>zu weiteren administrativen Zwecken.</li>
								</ul>
								<p>Wir verwenden Ihre Daten nicht, um Rückschlüsse auf Ihre Person zu ziehen. Informationen dieser Art werden von uns ggfs. statistisch ausgewertet, um unseren Internetauftritt und die dahinterstehende Technik zu optimieren.</p>
								<h4>Rechtsgrundlage</h4>
								<p>Die Verarbeitung erfolgt gemäß Art. 6 Abs. 1 lit. f DSGVO auf Basis unseres berechtigten Interesses an der Verbesserung der Stabilität und Funktionalität unserer Website.</p>
								<h4 >Empfänger</h4>
								<p>Empfänger der Daten sind ggf. technische Dienstleister, die für den Betrieb und die Wartung unserer Webseite als Auftragsverarbeiter tätig werden.</p>
								<h4>Speicherdauer</h4>
								<p>Die Daten werden gelöscht, sobald diese für den Zweck der Erhebung nicht mehr erforderlich sind. Dies ist für die Daten, die der Bereitstellung der Webseite dienen, grundsätzlich der Fall, wenn die jeweilige Sitzung beendet ist.</p>
								<h4 >Bereitstellung vorgeschrieben oder erforderlich</h4>
								<p>Die Bereitstellung der vorgenannten personenbezogenen Daten ist weder gesetzlich noch vertraglich vorgeschrieben. Ohne die IP-Adresse ist jedoch der Dienst und die Funktionsfähigkeit unserer Website nicht gewährleistet. Zudem können einzelne Dienste und Services nicht verfügbar oder eingeschränkt sein. Aus diesem Grund ist ein Widerspruch ausgeschlossen. </p>
								<p></p>
								<h3 >Registrierung auf unserer Website</h3>
								<h4>Art und Zweck der Verarbeitung</h4>
								<p>Bei der Registrierung für die Nutzung unserer personalisierten Leistungen werden einige personenbezogene Daten erhoben, wie Name, Anschrift, Kontakt- und Kommunikationsdaten (z. B. Telefonnummer und E-Mail-Adresse). Sind Sie bei uns registriert, können Sie auf Inhalte und Leistungen zugreifen, die wir nur registrierten Nutzern anbieten. Angemeldete Nutzer haben zudem die Möglichkeit, bei Bedarf die bei Registrierung angegebenen Daten jederzeit zu ändern oder zu löschen. Selbstverständlich erteilen wir Ihnen darüber hinaus jederzeit Auskunft über die von uns über Sie gespeicherten personenbezogenen Daten.</p>
								<h4>Rechtsgrundlage</h4>
								<p>Die Verarbeitung der bei der Registrierung eingegebenen Daten erfolgt auf Grundlage einer Einwilligung des Nutzers (Art. 6 Abs. 1 lit. a DSGVO).</p>
								<p>Dient die Registrierung der Erfüllung eines Vertrages, dessen Vertragspartei die betroffene Person ist oder der Durchführung vorvertraglicher Maßnahmen, so ist zusätzliche Rechtsgrundlage für die Verarbeitung der Daten Art. 6 Abs. 1 lit. b DSGVO.</p>
								<h4>Empfänger</h4>
								<p>Empfänger der Daten sind ggf. technische Dienstleister, die für den Betrieb und die Wartung unserer Website als Auftragsverarbeiter tätig werden.</p>
								<h4>Speicherdauer</h4>
								<p>Daten werden in diesem Zusammenhang nur verarbeitet, solange die entsprechende Einwilligung vorliegt. Danach werden sie gelöscht, soweit keine gesetzlichen Aufbewahrungspflichten entgegenstehen. Zur Kontaktaufnahme in diesem Zusammenhang nutzen Sie bitte die am Ende dieser Datenschutzerklärung angegebenen Kontaktdaten.</p>
								<h4>Bereitstellung vorgeschrieben oder erforderlich</h4>
								<p>Die Bereitstellung Ihrer personenbezogenen Daten erfolgt freiwillig, allein auf Basis Ihrer Einwilligung. Ohne die Bereitstellung Ihrer personenbezogenen Daten können wir Ihnen keinen Zugang auf unsere angebotenen Inhalte und Leistungen gewähren.</p>
								<p></p><h3 >Erbringung kostenpflichtiger Leistungen</h3>
								<h4>Art und Zweck der Verarbeitung</h4>
								<p>Zur Erbringung kostenpflichtiger Leistungen werden von uns zusätzliche Daten erfragt, wie z.B. Zahlungsangaben, um Ihre Bestellung ausführen zu können.</p>
								<h4>Rechtsgrundlage</h4>
								<p>Die Verarbeitung der Daten, die für den Abschluss des Vertrages erforderlich ist, basiert auf Art. 6 Abs. 1 lit. b DSGVO.</p>
								<h4>Empfänger</h4>
								<p>Empfänger der Daten sind ggf. Auftragsverarbeiter.</p>
								<h4>Speicherdauer</h4>
								<p>Wir speichern diese Daten in unseren Systemen bis die gesetzlichen Aufbewahrungsfristen abgelaufen sind. Diese betragen grundsätzlich 6 oder 10 Jahre aus Gründen der ordnungsmäßigen Buchführung und steuerrechtlichen Anforderungen.</p>
								<h4>Bereitstellung vorgeschrieben oder erforderlich</h4>
								<p>Die Bereitstellung Ihrer personenbezogenen Daten erfolgt freiwillig. Ohne die Bereitstellung Ihrer personenbezogenen Daten können wir Ihnen keinen Zugang auf unsere angebotenen Inhalte und Leistungen gewähren.</p>
								<p></p><h3 >Kontaktformular</h3>
								<h4>Art und Zweck der Verarbeitung</h4>
								<p>Die von Ihnen eingegebenen Daten werden zum Zweck der individuellen Kommunikation mit Ihnen gespeichert. Hierfür ist die Angabe einer validen E-Mail-Adresse sowie Ihres Namens erforderlich. Diese dient der Zuordnung der Anfrage und der anschließenden Beantwortung derselben. Die Angabe weiterer Daten ist optional.</p>
								<h4>Rechtsgrundlage</h4>
								<p>Die Verarbeitung der in das Kontaktformular eingegebenen Daten erfolgt auf der Grundlage eines berechtigten Interesses (Art. 6 Abs. 1 lit. f DSGVO).</p>
								<p>Durch Bereitstellung des Kontaktformulars möchten wir Ihnen eine unkomplizierte Kontaktaufnahme ermöglichen. Ihre gemachten Angaben werden zum Zwecke der Bearbeitung der Anfrage sowie für mögliche Anschlussfragen gespeichert.</p>
								<p>Sofern Sie mit uns Kontakt aufnehmen, um ein Angebot zu erfragen, erfolgt die Verarbeitung der in das Kontaktformular eingegebenen Daten zur Durchführung vorvertraglicher Maßnahmen (Art. 6 Abs. 1 lit. b DSGVO).</p>
								<h4>Empfänger</h4>
								<p>Empfänger der Daten sind ggf. Auftragsverarbeiter.</p>
								<h4>Speicherdauer</h4>
								<p>Daten werden spätestens 6 Monate nach Bearbeitung der Anfrage gelöscht.</p>
								<p>Sofern es zu einem Vertragsverhältnis kommt, unterliegen wir den gesetzlichen Aufbewahrungsfristen nach HGB und löschen Ihre Daten nach Ablauf dieser Fristen. </p>
								<h4>Bereitstellung vorgeschrieben oder erforderlich</h4>
								<p>Die Bereitstellung Ihrer personenbezogenen Daten erfolgt freiwillig. Wir können Ihre Anfrage jedoch nur bearbeiten, sofern Sie uns Ihren Namen, Ihre E-Mail-Adresse und den Grund der Anfrage mitteilen.</p>
								<p></p><h3 >SSL-Verschlüsselung</h3>
								<p>Um die Sicherheit Ihrer Daten bei der Übertragung zu schützen, verwenden wir dem aktuellen Stand der Technik entsprechende Verschlüsselungsverfahren (z. B. SSL) über HTTPS.</p>
								<p></p><h3 >Änderung unserer Datenschutzbestimmungen</h3>
								<p>Wir behalten uns vor, diese Datenschutzerklärung anzupassen, damit sie stets den aktuellen rechtlichen Anforderungen entspricht oder um Änderungen unserer Leistungen in der Datenschutzerklärung umzusetzen, z.B. bei der Einführung neuer Services. Für Ihren erneuten Besuch gilt dann die neue Datenschutzerklärung.</p>
								<h3 >Fragen an den Datenschutzbeauftragten</h3>
								<p>Wenn Sie Fragen zum Datenschutz haben, schreiben Sie uns bitte eine E-Mail oder wenden Sie sich direkt an die für den Datenschutz verantwortliche Person in unserer Organisation:</p>
								<p>mystableorganizer@gmail.com</p>
								<p><em>Die Datenschutzerklärung wurde mit dem </em><em><a href="https://www.activemind.de/datenschutz/datenschutzhinweis-generator/" target="_blank" rel="noopener">Datenschutzerklärungs-Generator der activeMind AG erstellt</a> (Version 2018-09-24).</em></p>
								
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