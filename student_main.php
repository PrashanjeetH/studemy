<?php

	$currDir=dirname(__FILE__);
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");
	@include("$currDir/hooks/courses.php");
	include("$currDir/courses_dml.php");
	include("$currDir/config.php")

	// mm: can the current member access this page?

?>
<?php
if(isset($_GET['id'])){
    $id = $_GET['id'];
		$mid = $_GET['mid'];
$sql = "SELECT * , modules.description as description1, modules.link as link2
				FROM modules, teachers, courses
				LEFT JOIN  institutes
				ON courses.instituteNumber = institutes.instituteNumber"."
				WHERE modules.courseId = '$id' AND courses.courseId = '$id' AND teachers.id = courses.teacher AND modules.moduleId  = '$mid'";

$result=mysqli_query($conn,$sql);
if (mysqli_connect_errno())
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
}
if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
				 echo $row['courseId'].'</br>courseId<br><br>';
				 echo $row['courseName'].'<br>course name<br><br>';
				 echo $row['moduleName'].'<br>module name<br><br>';
				 echo $row['instituteName'].'<br>institute name<br><br>';
				 echo $row['firstname'] , $row['lastname'].'<br>firstname and last name<br><br>';
				 echo "<a href=".$row['link2'].">Module Link<br></br></a>";
				 echo "<a href=".$row['link'].">Course Link<br></br></a>";


				 echo '<a href="download.php?id='.$row['courseId'].'"> Download </a></br>';
				 //
				 // $res = array();
				 // $res[][] = array('courseCode' => $row['courseCode'], 'moduleName' => $row['moduleName']);
				 echo "================<br>";
				 foreach($result as $row12) {
					  // $courseId = $row12['courseId'];
					  // $moduleId = $row12['moduleId'];
					  // // $res[$courseId][$moduleId]['moduleName'][] = array('moduleName' => $row12['moduleName'], 'courseCode' => $row12['courseCode']);
						$values = array('module' => $row12['moduleName']);
						echo $values['module'].'<br>';
					}
				 echo "================<br></br><br>";

				 echo $row['description1'].'<br>';
				 echo $row['instituteCode'].'<br>';
				 echo $row['courseCode'].'<br>';
				 echo $row[''].'<br>';
				 echo $row[''].'<br>--------------------------------------------------------------------------------------<br>';
			 }
} else {
		echo "0 results";
	}
$conn->close();
?> <!--user widgets-->
