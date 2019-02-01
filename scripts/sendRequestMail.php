
<?php

	require '../ThirdParty/phpmailer/PHPMailerAutoload.php';
	//error_reporting(0);	

	$ini = parse_ini_file('../my_stable_config.ini');

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
		$mailAnforderer->Host = $ini["smtp_host"];
		$mailAnforderer->CharSet = $ini["smtp_charset"];   
		$mailAnforderer->Port= $ini["smtp_port"];
		$mailAnforderer->Username = $ini["smtp_user"];
		$mailAnforderer->Password = $ini["smtp_password"]; 
		$mailAnforderer->setFrom($ini["smtp_fromAdress"], $ini["smtp_fromName"]);
		$mailAnforderer->addAddress($ini["smtp_answerAdress"]); 
		$mailAnforderer->Subject  = "Nachricht von " . $email . " erhalten";
		$mailAnforderer->Body     = $message;


		if(!$mailAnforderer->send()) {
		  header("Location: ../index.html");
		} else {
		  header("Location: ../index.html");
		}
?>