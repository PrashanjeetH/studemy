<?php @include("{$currDir}/hooks/links-home.php"); ?>
<?php if(!defined('PREPEND_PATH')) define('PREPEND_PATH', ''); ?>
<?php if(!defined('datalist_db_encoding')) define('datalist_db_encoding', 'UTF-8'); ?>
<?php include("libs/redirect.php");?>
<?php include("libs/fetch_data.php");?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Studemy - <?php $currentuser=getLoggedMemberID();
    if($currentuser=="admin"){
    echo'Admin Panel';
  } else if (15==getLoggedGroupId()){
    echo'Institute Panel';
  } else {
    echo "Student Panel";
  }?>

</title>
	<!-- Bootstrap Styles-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FontAwesome Styles-->
     <link href="./assets/css/font-awesome.css" rel="stylesheet">
        <!-- Custom Styles-->
    <link href="assets/css/custom-styles.css" rel="stylesheet" />
     <!-- Google Fonts-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<body>
    <div id="wrapper" style="background: linear-gradient(to right, rgba(112,204,145,1) 0%,rgba(111,187,248,1) 100%);">
        <nav class="navbar navbar-default top-navbar" role="navigation" style="background: linear-gradient(to right, rgba(112,204,145,1) 0%,rgba(111,187,248,1) 100%);">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            <!--    <div class="navbar-header"> -->

            <a class="navbar-brand" href="index.html"> <b style="color:black;"> Studemy</b> </a>

            <ul class="nav navbar-top-links navbar-right">
                 <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false"style="color:black;">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu">
              <!--login/logout area starts-->
              <li>
               <?php if(getLoggedAdmin()){ ?>
               <a href="<?php echo PREPEND_PATH; ?>admin/pageHome.php" class="btn btn-danger navbar-btn btn-sm hidden-xs"><i class="fa fa-cog"></i> <strong><?php echo $Translation['admin area']; ?></strong></a>
               <a href="<?php echo PREPEND_PATH; ?>admin/pageHome.php" class="btn btn-danger navbar-btn btn-sm visible-xs btn-sm"><i class="fa fa-cog"></i> <strong><?php echo $Translation['admin area']; ?></strong></a>
               <?php } ?>
               <?php if(!$_GET['signIn'] && !$_GET['loginFailed']){ ?>
               <?php if(getLoggedMemberID() == $adminConfig['anonymousMember']){ ?>
               <p class="navbar-text navbar-right">&nbsp;</p>
               <a href="<?php echo PREPEND_PATH; ?>index.php?signIn=1" class="btn btn-success navbar-btn btn-sm navbar-right"><strong><?php echo $Translation['sign in']; ?></strong></a>
               <p class="navbar-text navbar-right">
                <?php echo $Translation['not signed in']; ?>
              </p>
              <?php }else{ ?>
              <ul class="nav navbar-nav navbar-right hidden-xs" style="min-width: 330px;">
              </ul>
              <ul class="nav navbar-nav visible-xs">
              </ul>
              <?php } ?>
              <?php } ?>
            </li>
            <!--login/logout area ends-->
            <li class="divider"></li>
            <li><a class="btn navbar-btn btn-primary" href="<?php echo PREPEND_PATH; ?>index.php?signOut=1"><i class="fa fa-power-off"></i> <strong style="color:white"><?php echo $Translation['sign out']; ?></strong> </a></li>
          </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
        </nav>
        <!--/. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav"  id="main-menu">

                    <li>
                        <a style=" color:black;"href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
                    </li>

                    <?php $currentuser=getLoggedGroupId();
                    if($currentuser==50){
                    echo' <li>
                        <a style=" color:black;" href="membership_profile.php"><i class="fa fa-home"></i> Profile</a>
                    </li>
                    <li>
                        <a style=" color:black;"href="#"><i class="fa fa-play-circle-o"></i>My Courses</a>
                    </li>

                    ';
                  }?>


                    <?php $currentuser=getLoggedMemberID();
                    if($currentuser=="admin"){
                    echo' <li>
                        <a style=" color:black;" href="institutes_view.php"><i class="fa fa-home"></i> Institutes</a>
                    </li>';
                }
                    ?>

                    <?php $currentuser=getLoggedGroupId();
                    if($currentuser==15){
                    echo' <li>
                        <a style=" color:black;" href="membership_profile.php"><i class="fa fa-home"></i> Profile</a>
                    </li>';
                }
                    ?>
                    <?php $currentuser=getLoggedGroupId();
                    if($currentuser!=50){
                    echo'
					          <li>
                        <a style=" color:black;"href="courses_view.php"><i class="fa fa-play-circle-o"></i>Course</a>
                    </li>

                    <li>
                        <a style=" color:black;"href="assessments_view.php"><i class="fa fa-check-circle"></i>Assessment</a>
                    </li>

                    <li>
                        <a style=" color:black;"href="students_view.php"><i class="fa fa-users"></i>Students</a>
                    </li>

                    <li>
                        <a style=" color:black;"href="modules_view.php"><i class="fa fa-clipboard"></i>Modules</a>
                    </li>

                    <li>
                        <a style=" color:black;" href="subjects_view.php"><i class="fa fa-book"></i>Subjects</a>
                    </li>

                    <li>
                        <a style=" color:black;" href="teachers_view.php"><i class="fa fa-users"></i>Teachers</a>
                    </li>
                    <li>
                      <a style=" color:black;"href="analytics_view.php"><i class="fa fa-bar-chart-o"></i>Analytics</a>
                    </li>
                     ';} ?>
                    <?php /* $currentuser=getLoggedMemberID();
                    if($currentuser=="admin"){
                      echo' <li>
                      <a href="hooks/summary-reports.php"><i class="fa fa-list"></i> Reports</a>
                      </li>';
                    }*/
                    ?>

                        </ul>
                    </li>
                </ul>

            </div>

        </nav>
        <!-- NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
			 <div class="row">
                    <!--<div class="col-md-12">
                        <h1 class="page-header">
                            Welcome:  <small> <?php# echo getLoggedMemberID();?></small>
                        </h1>
                        <?php alertcheck(); ?>
                    </div>-->
                  </div>

                <?php
                   include("main.php");
                ?>

                 <!-- /. ROW  -->
				</div>
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
         <footer><strong><p><center>&copy; Copyright 2019. All Rights Reserved. | Made With <i class="fa fa-heart" ></i> by <a href="https://www.innostud.com">INNOSTUD</a></p></center></strong></footer>

        </div>
     <!-- /. WRAPPER  -->
    <!-- JS Scripts-->
    <!-- jQuery Js -->
    <script src="assets/js/jquery-1.10.2.js"></script>
      <!-- Bootstrap Js -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Metis Menu Js -->
    <script src="assets/js/jquery.metisMenu.js"></script>
      <!-- Custom Js -->
    <script src="assets/js/custom-scripts.js"></script>
    </body>
</html>
