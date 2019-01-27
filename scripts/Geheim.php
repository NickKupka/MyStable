<?php
session_start();
if(!isset($_SESSION['userid'])) {
    die('Bitte zuerst <a href="login.php">einloggen</a>');
} 
$userid = $_SESSION['userid'];
?>
<html>
	<head>
		<title>Verlwarte deinen Stall</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../assets/css/main.css" />
	</head>
	<body class="is-preload">
	  
		<div id="page-wrapper">

			<!-- Header -->
				<div id="header">

					<!-- Logo -->
						<h1><a href="../index.html" id="logo">MyStable <em>by Technick Solutions</em></a></h1>

					<!-- Nav -->
						<nav id="nav">
							<ul>
								<li ><a href="../index.html">Home</a></li>
								<li ><a href="../index.html">Irgendein Feature</a></li>
								<li><a href="Logout.php">Logout</a></li>
							</ul>
						</nav>
				</div>

			<!-- Main -->
				<section class="wrapper style1">
					<div class="container">
						<div id="content">
							<h2>Willkommen in deinem Bereich <?php echo "$userid";?></h2>
						</div>
					</div>
				</section>

			<!-- Footer -->
				<div id="footer">
					<div class="container">
						<div class="row">
							<section class="col-3 col-6-narrower col-12-mobilep">
								<h3>Links to Stuff</h3>
								<ul class="links">
									<li><a href="#">Mattis et quis rutrum</a></li>
									<li><a href="#">Suspendisse amet varius</a></li>
									<li><a href="#">Sed et dapibus quis</a></li>
									<li><a href="#">Rutrum accumsan dolor</a></li>
									<li><a href="#">Mattis rutrum accumsan</a></li>
									<li><a href="#">Suspendisse varius nibh</a></li>
									<li><a href="#">Sed et dapibus mattis</a></li>
								</ul>
							</section>
							<section class="col-3 col-6-narrower col-12-mobilep">
								<h3>More Links to Stuff</h3>
								<ul class="links">
									<li><a href="#">Duis neque nisi dapibus</a></li>
									<li><a href="#">Sed et dapibus quis</a></li>
									<li><a href="#">Rutrum accumsan sed</a></li>
									<li><a href="#">Mattis et sed accumsan</a></li>
									<li><a href="#">Duis neque nisi sed</a></li>
									<li><a href="#">Sed et dapibus quis</a></li>
									<li><a href="#">Rutrum amet varius</a></li>
								</ul>
							</section>
							<section class="col-6 col-12-narrower">
								<h3>Get In Touch</h3>
								<form>
									<div class="row gtr-50">
										<div class="col-6 col-12-mobilep">
											<input type="text" name="name" id="name" placeholder="Name" />
										</div>
										<div class="col-6 col-12-mobilep">
											<input type="email" name="email" id="email" placeholder="Email" />
										</div>
										<div class="col-12">
											<textarea name="message" id="message" placeholder="Message" rows="5"></textarea>
										</div>
										<div class="col-12">
											<ul class="actions">
												<li><input type="submit" class="button alt" value="Send Message" /></li>
											</ul>
										</div>
									</div>
								</form>
							</section>
						</div>
					</div>

					<!-- Icons -->
						<ul class="icons">
							<li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
							<li><a href="#" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
							<li><a href="#" class="icon fa-github"><span class="label">GitHub</span></a></li>
							<li><a href="#" class="icon fa-linkedin"><span class="label">LinkedIn</span></a></li>
							<li><a href="#" class="icon fa-google-plus"><span class="label">Google+</span></a></li>
						</ul>

					<!-- Copyright -->
						<div class="copyright">
							<ul class="menu">
								<li>&copy; Untitled. All rights reserved</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
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
