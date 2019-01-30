
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
		$mailAnforderer->Username = "mystableorganizer@gmail.com";
		$mailAnforderer->Password = "Nick&Alex2019"; 
		$mailAnforderer->setFrom('mystableorganizer@gmail.com', 'My Stable Organizer');
		$mailAnforderer->addAddress('mystableorganizer@gmail.com');
		$mailAnforderer->Subject  = "Nachricht von " . $email . " erhalten";
		$mailAnforderer->Body     = $message;


		if(!$mailAnforderer->send()) {
		  header("Location: ../index.html");
		} else {
		  header("Location: ../index.html");
		}
?>