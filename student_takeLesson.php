<?php
	$currDir=dirname(__FILE__);
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");
	@include("$currDir/hooks/students.php");
	include("$currDir/students_dml.php");
  include("$currDir/config.php");

	// mm: can the current member access this page?
	$perm=getTablePermissions('courses');
	if(!$perm[0]){
		echo error_message($Translation['tableAccessDenied'], false);
		echo '<script>setTimeout("window.location=\'index.php?signOut=1\'", 3500);</script>';
		exit;
	}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Courses</title>
    <link type="text/css" href="assets/css/app.css" rel="stylesheet">
		<link rel="stylesheet" href="assets/css/font-awesome.css">

</head>

<body class="layout-navbar-mini-fixed-bottom">


<?php
if(isset($_GET['id'])){
    $id = $_GET['id'];
}
	$sql = "SELECT * ,
					modules.description as description1,
					modules.link as modulelink,
					modules.file as modulefile,
					courses.subjects as courseSubject
					FROM modules, teachers, courses
					LEFT JOIN  institutes
					ON courses.instituteNumber = institutes.instituteNumber"."
					WHERE modules.courseId = '$id' AND courses.courseId = '$id' AND teachers.id = courses.teacher";
  $result=mysqli_query($conn,$sql);
  if (mysqli_connect_errno())
  {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  $row=mysqli_fetch_assoc($result);
  if ($result->num_rows > 0) {
  } else {
      echo "0 results";
    }
  ?> <!--user widgets-->






    <div class="mdk-header-layout js-mdk-header-layout">
        <!-- Header Layout Content -->
        <div class="mdk-header-layout__content page-content ">
            <div class="navbar navbar-list navbar-submenu navbar-light border-0 navbar-expand-sm" style="white-space: nowrap;">
                <div class="container flex-column flex-sm-row">
                    <nav class="nav navbar-nav navbar-list__item">
                        <div class="nav-item">
                            <div class="media flex-nowrap">
                                <div class="media-left mr-16pt">
                                    <img src="assets/img/course.png" width="40" alt="Angular" class="rounded"></img>
                                </div>
                                <div class="media-body">
                                    <a  class="card-title text-body mb-0"><?php echo $row['courseName']; ?></a>
                                    <p class="lh-1 d-flex align-items-center mb-0">
                                        <span class="text-50 small font-weight-bold mr-8pt"> <?php echo $row['firstname'];?> <?php echo $row['lastname']; ?></span>
                                        <span class="text-50 small"><?php echo $row['instituteName']; ?></span>
                                    </p>
                                </div>

                            </div>
														<div class="" style="">
															<a class="btn navbar-btn btn-default" href="index.php?signOut=1"><i class="glyphicon glyphicon-log-out"></i> <?php echo $Translation['sign out']; ?></a>
														</div>
                        </div>
                    </nav>
                </div>
            </div>
            <div class=" pb-lg-64pt py-32pt">
                <div class="container">
                    <div class="js-player embed-responsive embed-responsive-16by9 mb-32pt">
                        <div class="player embed-responsive-item">
                            <div class="player__content">
																<iframe class="embed-responsive-item" src="<?php echo $row['link']; ?>"></iframe>
                            </div>

                        </div>
                    </div>

                    <div class="d-flex flex-wrap align-items-end mb-16pt">
                        <h1 class=" flex m-0"><?php echo $row['courseName']; ?></h1>
                        <!-- <p class="h1 font-weight-light m-0">Time</p> -->
                    </div>

                    <p class="hero__lead measure-hero-lead  mb-24pt"><?php echo $row['description']; ?></p>

										<a href="download.php?id=<?php echo $row['courseId']; ?>"  class="btn btn-primary"> Download File</a>

                </div>
            </div>
            <div class="navbar navbar-expand-sm navbar-submenu navbar-light navbar-list p-0 m-0 align-items-center">
                <div class="container page__container">
                    <ul class="nav navbar-nav flex align-items-sm-center">
                        <li class="nav-item navbar-list__item">
                            <div class="media align-items-center">
                                <span class="media-left mr-16pt">
                                    <img src="assets/img/teacher.png" width="40" alt="Teacher" class="rounded-circle">
                                </span>
                                <div class="media-body">
                                    <a class="card-title m-0" href="instructor-profile.html"><?php echo $row['firstname'],$row['lastname'];  ?></a>
                                    <p class="text-50 lh-1 mb-0">Instructor</p>
                                </div>
                            </div>
                        </li>
												<li class="nav-item navbar-list__item">
														<div class="media align-items-center">
																<span class="media-left mr-16pt">
																		<img src="assets/img/subjects.png" width="40" alt="" class="rounded-circle">
																</span>
																<div class="media-body">
																		<a class="card-title m-0" href="instructor-profile.html"><?php echo $row['courseSubject'] ?></a>
																		<p class="text-50 lh-1 mb-0">Subject</p>
																</div>
														</div>
												</li>
<!--
												<li class="nav-item navbar-list__item">
														<div class="media align-items-center">
																<span class="media-left mr-16pt">
																		<img src="assets/img/modules.png" width="40" alt="" class="rounded-circle">
																</span>
																<div class="media-body">
																		<a class="card-title m-0" href="instructor-profile.html"><?php #echo $row['moduleName']  ?></a>
																		<p class="text-50 lh-1 mb-0">Module</p>
																</div>
														</div>
												</li> -->

                    </ul>
                </div>
            </div>


<!-- Module view -->
            <div class="page-section bg-white">
                <div class="container page__container">

                    <div class="d-flex align-items-center mb-heading">
                        <h4 class="m-0">Modules</h4>
                        <!-- <a href="student-discussions-ask.html" class="text-underline ml-auto">Ask a Question</a> -->
                    </div>
										<!-- dynamic module representation according to the courses -->
                    <div class="border-top">

                        <div class="list-group list-group-flush">
													<?php
													foreach($result as $row12) {
													$values = array('module' => $row12['moduleName'], 'mid' => $row12['moduleId'], 'cid' => $row12['courseId']);
													// echo $values['module'].'<br>';
													?>
														 <div class="list-group-item p-3">
	                                <div class="row align-items-center">
	                                    <div class="col-md-3 mb-8pt mb-md-0">
	                                        <div class="media">
	                                            <div class="media-left mr-20pt">
	                                                <a href="student-profile.html"><img src="assets/img/modules.png" width="40" alt="avatar" class="rounded-circle"></a>
	                                            </div>
	                                            <div class="media-body media-middle">
	                                                <!-- <p class="text-muted m-0">2 days ago</p>
	                                                <p class="m-0"><a href="student-profile.html" class="text-body">Laza Bogdan</a></p> -->

																									<a href="student_takeModules.php?id=<?php echo $values['cid']; ?>&mid=<?php echo $values['mid']; ?>" class="chip chip-outline-secondary"><?php echo $values['module']; ?></a>
	                                            </div>
	                                        </div>
	                                    </div>
	                                    <div class="col mb-8pt mb-md-0">
	                                        <!-- <p class="mb-8pt"><a href="student-discussion.html" class="text-body"><strong>Using Angular HttpClientModule instead of HttpModule</strong></a></p> -->


	                                        <!-- <a href="student-discussion.html" class="chip chip-outline-secondary"> TEXT</a> -->


	                                    </div>
	                                    <div class="col-auto d-flex flex-column align-items-center justify-content-center">

	                                        <h5 class="m-0"><?php echo $rowcount=mysqli_num_rows($result); ?></h5>
	                                        <p class="lh-1 mb-0"><small class="text-70">answers</small></p>
	                                    </div>
	                                </div>
	                            </div> <?php ;}?>
													</div>
                    </div>
                </div>
            </div>
<!-- End Of Module View -->
    	</div>
    </div>
    <!-- drawer -->

    <script src="assets/vendor/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap.min.js"></script>
    <script src="assets/vendor/dom-factory.js"></script>
    <script src="assets/js/app.js"></script>

</body>

<?php include('footer.php') ?>

</html>
