
<?php
	require '../ThirdParty/phpmailer/PHPMailerAutoload.php';
	//error_reporting(0);	
	
	
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
		$mailAnforderer->addAddress("nick.kupka@gmail.com");
		$mailAnforderer->Subject  = "Nachricht von $name";
		$mailAnforderer->Body     = "$message Rückmeldung bitte an -> $email";


		if(!$mailAnforderer->send()) {
		  echo 'Message was not sent.';
		  echo 'Mailer error: ' . $mailAnforderer->ErrorInfo;
		} else {
		  echo 'Message has been sent.';
		}



header("location:../index.html");
?>