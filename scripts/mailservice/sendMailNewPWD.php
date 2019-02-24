
<?php

require '../../ThirdParty/phpmailer/PHPMailerAutoload.php';
//error_reporting(0);	

$emailreceiver = $argv[1];
$newPWD = $argv[2];

$ini = parse_ini_file('../../my_stable_config.ini');
	 

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
$mailAnforderer->Subject  = "Passwort Reset von myStable";
$mailAnforderer->Body     = "<h2>Hallo.</h2>

<p>Sie haben soeben Ihr Passwort zurückgesetzt.</p>
<p>Sollten Sie die Zurücksetzung des Passwortes nicht veranlasst haben, so bitten wir Sie mit uns in Kontakt zu treten.</p>

<p>Dein neues Passwort lautet: <strong>$newPWD</strong></p>

<p>Bitte Loggen Sie sich mit Ihrem neuen Passwort unter " . "https://www.my-stable.de/scripts/Login.php" . " ein.</p>
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