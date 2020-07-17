<?php if(!isset($Translation)){ @header('Location: index.php?signIn=1'); exit; } ?>

<?php if($_GET['loginFailed']){ ?>
	<div class="alert alert-danger"><?php echo $Translation['login failed']; ?></div>
<?php } ?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="" />
		<meta name="keywords" content="" />

		<title>Studemy-Beyond Classrooms</title>
		<link rel="stylesheet" href="./assets/css/font-awesome.min.css">
		<link type="text/css" rel="stylesheet" href="./assets/css/bootstrap.min.css"/>

		<link type="text/css" rel="stylesheet" href="./assets/css/style.css"/>
		<link type="text/css" rel="stylesheet" href="./assets/css/style1.css"/>
		<link href="https://fonts.googleapis.com/css?family=Lato:700%7CMontserrat:400,600" rel="stylesheet">
		<script type="text/javascript" src="./assets/js/jquery.min.js"></script>
		<style>
			.background-bg{

				width: auto;
				border-radius: 5px;
				padding: 10px;
				border: 3px solid #c6a530;
			}
			.padyy{
				padding: 5px;
			}
		</style>
	</head>
	<body data-spy="scroll" data-target=".mainmenu-area" data-offset="90">
<!--====== Navigation Bar =====-->
		<header id="header">
		  <div class="container" style="background-color:white ;position:fixed ;display: block; top: 0px;width: 100%; height: 80px">
		    <div class="navbar-header">
		      <div class="navbar-brand">
		        <a class="logo" href="index.html">
		        <img src="./assets/img/logo.png" alt="STUDEMY"> </a>
		      </div>

		      <button class="navbar-toggle">
		        <span></span>
		      </button>

		    </div>
				<nav id="nav">
					<ul class="main-menu nav navbar-nav navbar-right " style="padding: 15px">
						<li><a href="index.html" style="color: black;">HOME</a></li>
						<li ><a href="#" style="color: black;">features</a></li>
						<li><a href="#" style="color: black;">Contact</a></li>
						<li><a style="color: black;" href="#" onclick="document.getElementById('modal-wrapper').style.display='block'">
							<i class="fa fa-sign-in"></i>Sign In</a></li>
					</ul>
				</nav>
				<!-- Navigation Ends here -->
			</div>
		</header>
		<!--====== End Of Navigation Bar =====-->

				<!-- login pop up -->
						<div id="modal-wrapper" class="modal">
				  <form class="modal-content animate" method="post" action="./index.php">
				    <div class="imgcontainer ">
				      <span onclick="document.getElementById('modal-wrapper').style.display='none'" class="close" title="Close PopUp">&times;</span>
				      <img src="./assets/img/1.png" alt="Avatar" class="avatar">
				      <h1 style="text-align:center">Log In !</h1>
				    </div>
				    <div class="container">
							<div class="form-inline">
				      <!--<input type="text" placeholder="Enter Username" name="uname" style="width: 300px"><br>
							<input type="password" placeholder="Enter Password" name="psw" style="width: 300px"><br>-->
							<input class="form-control" name="username" style="max-width: 450px" id="username" type="text" placeholder="<?php echo $Translation['username']; ?>" required></br>
							<input class="form-control" name="password" style="max-width: 450px"id="password" type="password" placeholder="<?php echo $Translation['password']; ?>" required><br>
							<label class="control-label" for="rememberMe">
								<!--<input type="checkbox" name="rememberMe" id="rememberMe" value="1" style="margin:26px 30px;">Remember me</br>
							<a href="contactMe.php" class="help-block" style="text-decoration:none; margin-left: 25px;margin-top:26px;">Forgot Password ?</a><br>
						--><?php if(sqlValue("select count(1) from membership_groups where allowSignup=1")){ ?>
								<button class="form btn btn-success " onclick="window.location.href = '/membership_signup.php#?';"> <?php echo $Translation['sign up']; ?></button>
							<?php } ?>
							<button class="btn btn-primary " name="signIn" type="submit" id="submit" value="signIn" ><?php echo $Translation['sign in']; ?></button>
				    </div>
					</div>
				  </form>
				</div>
				<!-- end of login pop up -->


		<div id="home" class="hero-area">
			<div class="bg-image bg-parallax " style="background-image:url(./assets/img/logo-wrapper.png);max-height:120%; max-width:100%;">
			</div>
		</div>

		<div id="why-us" class="section">

			<div class="container-fluid" style="background-color: White">
				<div class="row ">
					<div class="section-header text-center">
						<h2>Get what you need...</h2>
					</div>

					<div class="col-md-3">
						<div class="form-inline response">
							<img src="./assets/img/subjects.png" alt="Icon" style="height:40px; width: 40px;">
								<select class="form-control form-control-sm">
									<option selected>Choose Course</option>
									<option value="c1">Course 1</option>
									<option value="c2">Course 2</option>
									<option value="c3">Course 3</option>
								</select>
							</div>
					</div>

					<div class="col-md-3">
						<div class="form-inline response">
							<img src="./assets/img/institute.png" alt="Icon" style="height:40px; width: 40px;">
								<select class="form-control form-control-sm">
									<option selected>Choose Institute</option>
									<option value="c1">Institute 1</option>
									<option value="c2">Institute 2</option>
									<option value="c3">Institute 3</option>
								</select>
							</div>
					</div>

					<div class="col-md-3">
						<div class="form-inline response">
							<img src="./assets/img/location.png" alt="Icon" style="height:40px; width: 40px;">
								<select class="form-control form-control-sm">
									<option selected>Choose Location</option>
									<option value="c1">Location 1</option>
									<option value="c2">Location 2</option>
									<option value="c3">Location 3</option>
								</select>
							</div>
					</div>

					<div class="col-md-3">
						<div class="form-inline ">
							<!--<a href="#"><img src="./assets/img/iconsearch.png" alt="Icon" style="height:40px; width: 90%;"></a>-->
							<a href="#"><img src="./assets/img/iconsearch.png" style="height:auto; max-width:100;border:none; display:block;" alt="" />
							</a></div>
					</div>
				</div>
			</div>
		</div>

<!-- Why studemy..? Section -->
			<div class="container-fluid" style="background-color: #f4f8ff">

				<div class="row">
					<div class="section-header text-center">
						<br><br><h2>Why Studemy ?</h2>
						<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor  nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo </p>
					</div>

					<div class="col-md-4">
						<div class="feature">
								<i class="feature-icon"><img class="feature-image"src="./assets/img/location.png" alt="marker"></i>
							<div class="feature-content">
								<h4>Learn From Anywhere</h4>
								<p class="text-truncate">STUDEMY provides you the power yoy to learn what you want and where you want, so you are not more bond to the location or iconomic constraints. </p>
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="feature">
							<i class="feature-icon"><img class="feature-image"src="./assets/img/teacher.png" alt="marker"></i>
							<div class="feature-content">
								<h4>Professional Teachers</h4>
								<p>Courses recorded and prepared by the professional teachers from across the India are shared over STUDEMY, for the betterment of your study.</p>
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<div class="feature">
							<i class="feature-icon"><img class="feature-image"src="./assets/img/institute.png" alt="marker"></i>
							<div class="feature-content">
								<h4>Reputed Institutes</h4>
								<p>We got numerous reputed Institutes and Coaching classes under onr roof ,so that you can get what you actually need</p>
							</div>
						</div>
					</div>
				</div><br><br>
			</div>
				<i class="section-hr"></i><br><br>
			<div class="container"   style="background-color: white;">
				<div class="row">
					<div class="col-md-6">
						<h3>Globalize your coaching skill.</h3>
						<p class="lead"> Lorem ipsum dolor sit amet, consectetur <p>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</p>
					</div>
					<!-- linking youtube video -->
					<div class="col-md-5 col-md-offset-1">
							<iframe  width="380" height="300" src="https://www.youtube.com/embed/ilNum35pqK4?controls=0">
							</iframe>
					</div>
					<!-- End of linking youtube video -->
				</div>
			</div>
		</div>
		<!--End of Why Studemy container -->

		<!--COURSE AREA-->
		<section class="course-area padding-top" id="courses">
				<div class="container-fluid" style="background-color: #f4f8ff">
						<div class="row">
								<div class="col-md-8 col-lg-8 col-md-offset-2 col-lg-offset-2 col-sm-12 col-xs-12">
										<div class="area-title text-center wow fadeIn">
											<br><br>
												<h2 class="xs-font26">Our Trending Courses</h2>
										</div>
								</div>
						</div>
						<div class="row">
							<div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">
									<div class=" course mb20">
										<i class="course-img"><img src="./assets/img/blog01.jpg" alt=""></i>
											<div class="course-details padding30">
													<h3 class="font18">Introduction to Resume Writing</h3>
													<p>MPs who are leaving the protection of for the campaign trail will render...</p>
													<p class="mt30"><a href="#" class="main-button">Enroll Now!</a> <span class="course-price"></span></p>
											</div>
									</div>
							</div>
							<div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">
									<div class="course mb20">
											<i class="course-img"><img src="./assets/img/blog02.jpg" alt=""></i>
											<div class="course-details padding30">
													<h3 class="font18">Practice of Leadership</h3>
													<p>MPs who are leaving the protection of for the campaign trail will render...</p>
													<p class="mt30"><a href="#" class="main-button">Enroll Now!</a> <span class="course-price"></span></p>
											</div>
									</div>
							</div>
								<div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">
										<div class="course mb20">
											<i class="course-img"><img src="./assets/img/blog03.jpg" alt=""></i>
												<div class="course-details padding30">
														<h3 class="font18">Introduction to Resume Writing</h3>
														<p>MPs who are leaving the protection of for the campaign trail will render...</p>
														<p class="mt30"><a href="#" class="main-button">Enroll Now!</a> <span class="course-price"></span></p>
												</div>
										</div>
								</div>
								<div class="col-md-3 col-lg-3 col-sm-6 col-xs-12">
										<div class="course mb20">
											<i class="course-img"><img src="./assets/img/blog04.jpg" alt=""></i>
												<div class="course-details padding30">
														<h3 class="font18">Practice of Leadership</h3>
														<p>MPs who are leaving the protection of for the campaign trail will render...</p>
														<p class="mt30"><a href="#" class="main-button">Enroll Now!</a> <span class="course-price"></span></p>
												</div>
										</div>
								</div>
						</div>
									<br><br>
				</div>
			</section>
		<!-- End of COURSE AREA -->



			<div class="container-fluid" style="background:white">
				<div class="row transbox"  >
					<div class="col-sm-6 col-sm-push-3">
						<div>
								<br><br><h3 class="text-center" style="font-size: 30px">Want to go Beyond Classrooms?</h3><br>
								</div>
									<div class="contact-form wow fadeIn">
										<form action="#" id="contact-form" method="post">
												<div class="row">
														<div class="">
																<div class="form-group" id="name-field">
																		<div class="form-input">
																				<input type="text" class="form-control" id="form-name" name="form-name" placeholder="Name.." required>
																		</div>
																</div>
														</div>
														<div class="">
																<div class="form-group" id="name-field">
																		<div class="form-input">
																				<input type="text" class="form-control" id="form-name" name="form-name" placeholder="E-mail.." required>
																		</div>
																</div>
														</div>
														<div class="">
																<div class="form-group" id="phone-field">
																		<div class="form-input">
																				<input type="text" class="form-control" id="form-phone" name="form-phone" placeholder="Subject..">
																		</div>
																</div>
														</div>
														<div class="">
																<div class="form-group" id="phone-field">
																		<div class="form-input">
																				<input type="text" class="form-control" id="form-phone" name="form-phone" placeholder="Contact">
																		</div>
																</div>
														</div>
														<!--<div class="">
																<div class="form-group" id="message-field">
																		<div class="form-input">
																				<textarea style="margin-left: 25px;width: 455px;" class="form-control" rows="6" id="form-message" name="form-message" placeholder="Your Message Here..." required></textarea>
																		</div>
																</div>
														</div>-->
												</div>
											</form>
											<div class="text-center">
													<button class="main-button icon-button" type="submit" style="width: 300px;">Send Message</button><br><br>
											</div>
										</div>
									</div>
								</div>
							</div>

		<div id="bottom-footer" class="row" style="background: linear-gradient(to right, rgba(112,204,145,1) 0%,rgba(111,187,248,1) 100%);">


			<!-- footer link ups -->
			<div class="">
				<ul class="footer-social">
					<li><a href="sociallPage.php" > <img src="./assets/img/facebook.png" style="max-width:90%; max-height:90%;" alt=""> </a></li>
					<li><a href="sociallPage.php" > <img src="./assets/img/facebook.png" style="max-width:90%; max-height:90%;" alt=""> </a></li>
					<li><a href="sociallPage.php" > <img src="./assets/img/facebook.png" style="max-width:90%; max-height:90%;" alt=""> </a></li>
					<li><a href="sociallPage.php" > <img src="./assets/img/facebook.png" style="max-width:90%; max-height:90%;" alt=""> </a></li>

				</ul>
			</div>
			<div class="col-md-12 col-sm-push-4">
				<div class="footer-copyright" >
					<span>&copy; Copyright 2019. All Rights Reserved. | Made With <i class="fa fa-heart" ></i> by <a href="https://www.innostud.com">INNOSTUD</a></span>
				</div></br></br>
			</div>
		</div>


			<!-- End of footer link ups -->
	</body>
</html>
