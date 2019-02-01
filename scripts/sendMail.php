
<?php

require '../ThirdParty/phpmailer/PHPMailerAutoload.php';
//error_reporting(0);	

$emailreceiver = $argv[1];
$licensekey = $argv[2];
$vorname = $argv[3];
$nachname = $argv[4];
$NameDesPferdes = $argv[5];

if(isset($_POST['submit'])){
	$name = filter_input(INPUT_POST, "name");
	$email = filter_input(INPUT_POST, "email");
	$message = filter_input(INPUT_POST, "message");
}		 

$mailAnforderer = new PHPMailer;
$mailAnforderer->IsSMTP();
$mailAnforderer->SMTPAuth = true;
$mailAnforderer->Host = 'smtp.gmail.com';
$mailAnforderer->CharSet = 'utf-8';   
$mailAnforderer->Port= 587;
$mailAnforderer->Username = "mystableorganizer@gmail.com";
$mailAnforderer->Password = "Nick&Alex2019"; 
$mailAnforderer->setFrom('mystableorganizer@gmail.com', 'My Stable Organizer');
$mailAnforderer->addAddress($emailreceiver);
$mailAnforderer->isHTML(true);                                  
$mailAnforderer->Subject  = "Nachricht von My Stable";
$mailAnforderer->Body     = "<h2>Hallo " . $vorname . " " .$nachname . ",</h2>

<p>Vielen Dank für Ihre Registrierung bei MyStable, Ihrem persönlichen Stallorganisierer.</p>
<p>Wir wollen Dich und dein Pferd <strong>" . $NameDesPferdes . " </strong> bei uns willkommen heißen.</p>

<p>Ihr persönlicher Lizenzschlüssel: <strong>$licensekey</strong></p>

<p>Bitte Loggen Sie sich mit Ihrem Lizenzschlüssel unter " . "https://www.my-stable.de/scripts/LoginWithKey.php" . " ein.</p>
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