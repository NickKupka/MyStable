
<?php

	require '../ThirdParty/phpmailer/PHPMailerAutoload.php';
	//error_reporting(0);	

 	$emailreceiver = $argv[1];
	$licensekey = $argv[2];
/*	
	if(isset($_POST['submit'])){

		//read data from form
		//$name = mysqli_real_escape_string($_POST["name"]);
		//$email = mysqli_real_escape_string($_POST["email"]);
		//$message = mysqli_real_escape_string($_POST["message"]);
		
		$name = filter_input(INPUT_POST, "name");
		$email = filter_input(INPUT_POST, "email");
		$message = filter_input(INPUT_POST, "message");
	
	
}		 
	
		
     	#################### MailVersand an Softwareentwicklung ################
		//For Mail Function
		$mailAnforderer = new PHPMailer;
		$mailAnforderer->IsSMTP();
		$mailAnforderer->SMTPAuth = true;
		$mailAnforderer->Host = 'smtp.gmail.com';
		$mailAnforderer->CharSet = 'utf-8';   
		$mailAnforderer->Port= 587;
		$mailAnforderer->Username = "recompliceconnect@gmail.com";
		$mailAnforderer->Password = "re010417_EE61"; 
		$mailAnforderer->setFrom('recompliceconnect@gmail.com', 'HIKX');
		$mailAnforderer->addAddress($emailreceiver);
		$mailAnforderer->Subject  = "Nachricht von MS";
		$mailAnforderer->Body     = "Sie wurden erfolgreich registriert.
		Bitte Loggen Sie sich mit Ihrem Lizenzschlüssel -> " .$licensekey . " <- ein.";


		if(!$mailAnforderer->send()) {
		  echo 'Message was not sent.';
		  echo 'Mailer error: ' . $mailAnforderer->ErrorInfo;
		} else {
		  echo 'Message has been sent.';
		}
		*/
		
		if(isset($_POST['submit'])){

		//read data from form
		//$name = mysqli_real_escape_string($_POST["name"]);
		//$email = mysqli_real_escape_string($_POST["email"]);
		//$message = mysqli_real_escape_string($_POST["message"]);
		
		$name = filter_input(INPUT_POST, "name");
		$email = filter_input(INPUT_POST, "email");
		$message = filter_input(INPUT_POST, "message");
	
	
}		 
	
		
     	#################### MailVersand an Softwareentwicklung ################
		//For Mail Function
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
		$mailAnforderer->Subject  = "Nachricht von My Stable";
		$mailAnforderer->Body     = "Sie wurden erfolgreich registriert.
		Bitte Loggen Sie sich mit Ihrem Lizenzschlüssel -> " .$licensekey . " unter " . "http://localhost:8080/mystable/scripts/LoginWithKey.php" . " ein.";


		if(!$mailAnforderer->send()) {
		  echo 'Message was not sent.';
		  echo 'Mailer error: ' . $mailAnforderer->ErrorInfo;
		} else {
		  echo 'Message has been sent.';
		}
?>