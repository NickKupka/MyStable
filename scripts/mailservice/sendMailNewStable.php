
<?php

require '../../ThirdParty/phpmailer/PHPMailerAutoload.php';
//error_reporting(0);	

$emailreceiver = $argv[1];
$licensekey = $argv[2];
$vorname = $argv[3];
$nachname = $argv[4];
$NameDesPferdes = $argv[5];
$stableName = $argv[6];


$ini = parse_ini_file('../../my_stable_config.ini');

if(isset($_POST['submit'])){
	$name = filter_input(INPUT_POST, "name");
	$email = filter_input(INPUT_POST, "email");
	$message = filter_input(INPUT_POST, "message");
}		 

$mailAnforderer = new PHPMailer;
$mailAnforderer->IsSMTP();
$mailAnforderer->SMTPAuth = true;
$mailAnforderer->SMTPAuth = true;
$mailAnforderer->Host = $ini["smtp_host"];
$mailAnforderer->CharSet = $ini["smtp_charset"];   
$mailAnforderer->Port= $ini["smtp_port"];
$mailAnforderer->Username = $ini["smtp_user"];
$mailAnforderer->Password = $ini["smtp_password"]; 
$mailAnforderer->setFrom($ini["smtp_fromAdress"], $ini["smtp_fromName"]);
$mailAnforderer->addAddress($emailreceiver);
$mailAnforderer->isHTML(true);                                  
$mailAnforderer->Subject  = "Neue Stallregistrierung auf My Stable";
$mailAnforderer->Body     = "<h2>Hallo " . $vorname . " " .$nachname . ",</h2>

<p>Vielen Dank für Ihre Registrierung bei MyStable, Ihrem persönlichen Stallorganisierer.</p>
<p>Wir freuen uns Sie und Ihren Stall <strong>" . $stableName . "</strong> bei uns Willkommen zu heißen.</p>
<p>Sie wurden erfolgreich registriert.</p>
<p>Ab sofort haben Sie die Möglichkeit Ihre Stallgemeinschaft online zu verwalten.<br/>
Bevor andere Reiter aus Ihrem Stall den Planer nutzen können müssen Sie sich einmalig mit ihrem Lizenzschlüssel anmelden.</p>
<p>Ihr persönlicher Lizenzschlüssel: <strong>$licensekey</strong></p>

<p>Bitte Loggen Sie sich mit Ihrem Lizenzschlüssel unter " . "https://www.my-stable.de/scripts/LoginWithKey.php" . " ein.</p><br/>
<p>Anschließend können sich alle anderen Reiter Ihres Stalls online Registrieren</p>
<p>Ihr Team von MyStable by Technick Solutions.<br/>
 
MyStable by Technick Solutions<br/>
Vertreten durch <br/>
Dominik Kupka und Alexander Freitag<br/>
München, Deutschland<br/>
Infos unter <a target='_blank' rel='noopener noreferrer' href='https://www.my-stable.de/index.html'>www.my-stable.de</a><br/>
oder einfach per Mail an <a href='mailto:mystableorganizer@gmail.com'>MyStable</a>.</p>";



if(!$mailAnforderer->send()) {
	echo 'Message was not sent.';
	echo 'Mailer error: ' . $mailAnforderer->ErrorInfo;
} else {
	echo 'Message has been sent.';
}



?>