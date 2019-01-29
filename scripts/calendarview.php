<?php
session_start();
if(!isset($_SESSION['userid'])) {
    die('Bitte zuerst <a href="login.php">einloggen</a>');
} 
$userid = $_SESSION['userid'];
?>
<html>
	<head>
		<title>Verwalte deinen Stall</title>
		<link rel="stylesheet" href="../assets/css/main.css" />
		
		
		
		
	</head>
	<body >


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
							<h2 align="center">Willkommen in deinem Bereich <?php echo "$userid";?></h2>
						</div>
						</br>
							<div class="container">
								<div id="calendar" style="height: 800px;"></div>
							</div>
					</div>
					</br>
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
		  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" />
		  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
		  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
			<script>
				
   
	  $(document).ready(function() {
	   var calendar = $('#calendar').fullCalendar({
		editable:true,
		header:{
		 left:'prev,next today',
		 center:'title',
		 right:'month,agendaWeek,agendaDay'
		},
		events: 'load.php',
		selectable:true,
		selectHelper:true,
		select: function(start, end, allDay)
		{
		 var title = prompt("Enter Event Title");
		 if(title)
		 {
		  var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
		  var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
		  $.ajax({
		   url:"insert.php",
		   type:"POST",
		   data:{title:title, start:start, end:end},
		   success:function()
		   {
			calendar.fullCalendar('refetchEvents');
			alert("Added Successfully");
		   }
		  })
		 }
		},
		editable:true,
		eventResize:function(event)
		{
		 var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
		 var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
		 var title = event.title;
		 var id = event.id;
		 $.ajax({
		  url:"update.php",
		  type:"POST",
		  data:{title:title, start:start, end:end, id:id},
		  success:function(){
		   calendar.fullCalendar('refetchEvents');
		   alert('Event Update');
		  }
		 })
		},

		eventDrop:function(event)
		{
		 var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
		 var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
		 var title = event.title;
		 var id = event.id;
		 $.ajax({
		  url:"update.php",
		  type:"POST",
		  data:{title:title, start:start, end:end, id:id},
		  success:function()
		  {
		   calendar.fullCalendar('refetchEvents');
		   alert("Event Updated");
		  }
		 });
		},

		eventClick:function(event)
		{
		 if(confirm("Are you sure you want to remove it?"))
		 {
		  var id = event.id;
		  $.ajax({
		   url:"delete.php",
		   type:"POST",
		   data:{id:id},
		   success:function()
		   {
			calendar.fullCalendar('refetchEvents');
			alert("Event Removed");
		   }
		  })
		 }
		},

	   });
	  });
	   
	  </script>
	</body>
</html>
