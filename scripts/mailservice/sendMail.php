<?php

require '../../ThirdParty/phpmailer/PHPMailerAutoload.php';
//error_reporting(0);	

$emailreceiver = $argv[1];
$vorname = $argv[2];
$nachname = $argv[3];
$NameDesPferdes = $argv[4];


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
$mailAnforderer->Subject  = "Registrierung bei myStable";
$mailAnforderer->Body     = "<h2>Hallo " . $vorname . " " .$nachname . ",</h2>

<p>Vielen Dank für Ihre Registrierung bei myStable, Ihrem persönlichen Stallorganisierer.</p>
<p>Wir wollen Sie und Ihr Pferd <strong>" . $NameDesPferdes . " </strong> bei uns willkommen heißen.</p>

<p>Ab sofort haben Sie Zugriff auf den Online-Stallservice. Bitte Loggen Sie sich unter " . "https://www.my-stable.de/scripts/Login.php" . " ein.</p>
<p>Ihr Team von myStable by Technick Solutions.<br/>
 
myStable by Technick Solutions<br/>
Vertreten durch <br/>
Dominik Kupka und Alexander Freitag<br/>
München, Deutschland<br/>
Infos unter <a target='_blank' rel='noopener noreferrer' href='https://www.my-stable.de/index.html'>www.my-stable.de</a><br/>
oder einfach per Mail an <a href='mailto:mystableorganizer@gmail.com'>myStable</a>.</p>";



if(!$mailAnforderer->send()) {
	echo 'Message was not sent.';
	echo 'Mailer error: ' . $mailAnforderer->ErrorInfo;
} else {
	echo 'Message has been sent.';
}



?>