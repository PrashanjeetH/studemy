<!-- This file displays the main page wrapper in the header User file and act according to the group Id of each group i.e. institute, student and administrator
the group Id has to be changed manually whenever a new group is added to the data base.
for the current data base group Id's are: admin (1), institute(15), student(50). -->

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_studemy";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
$perm=getTablePermissions('courses');
if(!$perm[0]){
  echo error_message($Translation['tableAccessDenied'], false);
  echo '<script>setTimeout("window.location=\'index.php?signOut=1\'", 2000);</script>';
  exit;
}
?>


 <div class="row">

<!-- Institutes Widget Start -->
   <?php $currentuser=getLoggedGroupId();
   if($currentuser==15){
   echo'
   <div class="col-md-3 col-sm-12 col-xs-12">
     <div class="panel panel-primary text-center no-boder bg-color-red">
       <div class="panel-body">
          <a href="./membership_profile.php">
          <img src="./assets/img/profile.png" alt=""></a>
          <h3><?php echo getLoggedMemberID(); ?></h3>
       </div>
       <div class="panel-footer" style="background-color: #EC9D96;">
         <a style="color: black;" href="./membership_profile.php" style="text-decoration:none;color: white"><strong>Profile</strong></a>
       </div>
     </div>
   </div>

   <div class="col-md-3 col-sm-12 col-xs-12">
       <div class="panel panel-primary text-center no-boder bg-color-brown">
           <div class="panel-body">
             <a href="./courses_view.php">
               <img src="./assets/img/course.png" alt=""></a>            </div>
           <div class="panel-footer" style="background-color: #FFE36B;">
               <a style="color: black;" href="courses_view.php" style="text-decoration: none;color: white"><strong>Courses</strong></a>

           </div>
       </div>
   </div>



     <div class="col-md-3 col-sm-12 col-xs-12">
         <div class="panel panel-primary text-center no-boder">
             <div class="panel-body">
               <a href="./modules_view.php">
                 <img src="./assets/img/modules.png" alt=""></a>
                 <h3><h3>
             </div>
             <div class="panel-footer" style="background-color: #9EECBD;">
                <a style="color: black;" href="modules_view.php" style="text-decoration: none;color: white"> <strong>Modules</strong></a>
             </div>
         </div>
     </div>
     <div class="col-md-3 col-sm-12 col-xs-12">
         <div class="panel panel-primary text-center no-boder bg-color-blue">
             <div class="panel-body">
               <a href="./assessments_view.php">
                 <img src="./assets/img/assessment.png" alt=""></a>                      <h3></h3>
             </div>
             <div class="panel-footer "style="background-color: #E4E4F9;">
                 <a style="color: black;" href="assessments_view.php" style="text-decoration: none;color: white"><strong>Assessment</strong></a>

             </div>
         </div>
     </div>

     <div class="col-md-3 col-sm-12 col-xs-12">
       <div class="panel panel-primary text-center no-boder bg-color-green">
         <div class="panel-body">
           <a href="./students_view.php">
             <img src="./assets/img/student.png" alt=""></a>                <h3></h3>
         </div>
         <div class="panel-footer" style="background-color: #C0E5E4;">
           <a style="color: black;" href="students_view.php" style="text-decoration: none;color: white"><strong>Students</strong></a>
         </div>
       </div>
     </div>

     <div class="col-md-3 col-sm-12 col-xs-12">
         <div class="panel panel-primary text-center no-boder bg-color-red">
             <div class="panel-body">
               <a href="./analytics_view.php">
                 <img src="./assets/img/analytics.png" alt=""></a>                      <h3></h3>
             </div>
             <div class="panel-footer" style="background-color: #FCE0A2;">
                <a style="color: black;" href="analytics_view.php" style="text-decoration: none;color: white"><strong>Analytics</strong></a>

             </div>
         </div>
     </div>

     <div class="col-md-3 col-sm-12 col-xs-12">
         <div class="panel panel-primary text-center no-boder bg-color-brown">
             <div class="panel-body">
               <a href="./subjects_view.php">
                 <img src="./assets/img/subjects.png" alt=""></a>
                 <h3><h3>
             </div>
             <div class="panel-footer" style="background-color: #E6EDB7;">
                <a style="color: black;" href="subjects_view.php" style="text-decoration: none;color: white"> <strong>Subjects</strong></a>

             </div>
         </div>
     </div>

        <div class="col-md-3 col-sm-12 col-xs-12">
            <div class="panel panel-primary text-center no-boder bg-color-brown">
                <div class="panel-body">
                  <a href="./teachers_view.php">
                    <img src="./assets/img/teacher.png" alt=""></a>            </div>
                <div class="panel-footer" style="background-color: #FFBE8B;">
                    <a style="color: black;" href="courses_view.php" style="text-decoration: none;color: white"><strong>Teachers</strong></a>

                </div>
            </div>
        </div>
';
 }?>
<!-- Institutes Widget End -->

 <!-- Admin Widget Start-->
    <?php $currentuser=getLoggedMemberID();
    if($currentuser=="admin"){
    echo'
       <div class="col-md-4 col-sm-12 col-xs-12">
           <div class="panel panel-primary text-center no-boder bg-color-red">
               <div class="panel-body">
                   <a href="./institutes_view.php">
                     <img src="./assets/img/institute.png" alt=""></a>               </div>
               <div class="panel-footer" style="background-color: #f9dbd2;">
                   <a style="color: black;"href="institutes_view.php" style="text-decoration: none;color: white"><strong>Institutes</strong></a>

               </div>
           </div>
       </div>

       <div class="col-md-4 col-sm-12 col-xs-12">
           <div class="panel panel-primary text-center no-boder bg-color-brown">
               <div class="panel-body">
                 <a href="./courses_view.php">
                   <img src="./assets/img/course.png" alt=""></a>            </div>
               <div class="panel-footer" style="background-color: #FFE36B;">
                   <a style="color: black;" href="courses_view.php" style="text-decoration: none;color: white"><strong>Courses</strong></a>

               </div>
           </div>
       </div>


       <div class="col-md-4 col-sm-12 col-xs-12">
           <div class="panel panel-primary text-center no-boder bg-color-green">
               <div class="panel-body">
                 <a href="./admin/pageHome.php">
                   <img src="./assets/img/administrator.png" alt=""></a>            </div>
               <div class="panel-footer" style="background-color: #F48BAC;">
                 <a style="color: black;" href="./admin/pageHome.php" style="text-decoration: none;color: white"><strong>Admin Panel</strong></a>
               </div>
           </div>
       </div>

       <div class="col-md-4 col-sm-12 col-xs-12">
           <div class="panel panel-primary text-center no-boder">
               <div class="panel-body">
                 <a href="./modules_view.php">
                   <img src="./assets/img/modules.png" alt=""></a>
                   <h3><h3>
               </div>
               <div class="panel-footer" style="background-color: #9EECBD;">
                  <a style="color: black;" href="modules_view.php" style="text-decoration: none;color: white"> <strong>Modules</strong></a>
               </div>
           </div>
       </div>
       <div class="col-md-4 col-sm-12 col-xs-12">
           <div class="panel panel-primary text-center no-boder bg-color-blue">
               <div class="panel-body">
                 <a href="./assessments_view.php">
                   <img src="./assets/img/assessment.png" alt=""></a>                      <h3></h3>
               </div>
               <div class="panel-footer "style="background-color: #E4E4F9;">
                   <a style="color: black;" href="assessments_view.php" style="text-decoration: none;color: white"><strong>Assessment</strong></a>

               </div>
           </div>
       </div>

       <div class="col-md-4 col-sm-12 col-xs-12">
         <div class="panel panel-primary text-center no-boder bg-color-green">
           <div class="panel-body">
             <a href="./students_view.php">
               <img src="./assets/img/student.png" alt=""></a>                <h3></h3>
           </div>
           <div class="panel-footer" style="background-color: #C0E5E4;">
             <a style="color: black;" href="students_view.php" style="text-decoration: none;color: white"><strong>Students</strong></a>
           </div>
         </div>
       </div>

       <div class="col-md-4 col-sm-12 col-xs-12">
           <div class="panel panel-primary text-center no-boder bg-color-red">
               <div class="panel-body">
                 <a href="./analytics_view.php">
                   <img src="./assets/img/analytics.png" alt=""></a>                      <h3></h3>
               </div>
               <div class="panel-footer" style="background-color: #FCE0A2;">
                  <a style="color: black;" href="analytics_view.php" style="text-decoration: none;color: white"><strong>Analytics</strong></a>

               </div>
           </div>
       </div>

       <div class="col-md-4 col-sm-12 col-xs-12">
           <div class="panel panel-primary text-center no-boder bg-color-brown">
               <div class="panel-body">
                 <a href="./subjects_view.php">
                   <img src="./assets/img/subjects.png" alt=""></a>
                   <h3><h3>
               </div>
               <div class="panel-footer" style="background-color: #E6EDB7;">
                  <a style="color: black;" href="subjects_view.php" style="text-decoration: none;color: white"> <strong>Subjects</strong></a>

               </div>
           </div>
       </div>
       <div class="col-md-4 col-sm-12 col-xs-12">
           <div class="panel panel-primary text-center no-boder bg-color-brown">
               <div class="panel-body">
                 <a href="./teachers_view.php">
                   <img src="./assets/img/teacher.png" alt=""></a>            </div>
               <div class="panel-footer" style="background-color: #FFBE8B;">
                   <a style="color: black;" href="courses_view.php" style="text-decoration: none;color: white"><strong>Teachers</strong></a>

               </div>
           </div>
       </div>
       ';
     }?>
<!-- Admin Widget end -->


<?php
$currentuser=getLoggedGroupId();
if ($currentuser==50) {
  # code...
  include("main-student.php");
}
?>

</div>
  <!--row ends here-->
