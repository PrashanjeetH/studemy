<?php
function alertcheck(){
	include("db_connect.php");
	$currentuser=getLoggedMemberID();
	if ($currentuser<>"admin") {
	# code...
		$sql="SELECT * FROM membership_userrecords WHERE tableName='institutes' AND memberID='$currentuser'";
		$result=mysqli_query($con,$sql);
		$rowcount=mysqli_num_rows($result);
		if ($rowcount==0) {
		# code...
			echo '<div class="alert alert-info">
			<strong>Hello '.$currentuser.'</strong> You have no data in our institutes records,kindly submit your data so that you can enjoy our services!!.
			</div>';
		}
	}
}
function countrecords($table){
	include("db_connect.php");
	$currentuser=getLoggedMemberID();
	if ($currentuser=="admin") {
	# code...
		$sql="SELECT * FROM $table ORDER BY id";
		$result=mysqli_query($con,$sql);
		$rowcount=mysqli_num_rows($result);
		echo $rowcount;
	}
	else {
	# code...
		if ($table=="institutes") {
		# code...
			$sql="SELECT * FROM membership_userrecords WHERE tableName='$table' AND memberID='$currentuser'";
			$result=mysqli_query($con,$sql);
			$rowcount=mysqli_num_rows($result);
			echo $rowcount;
		}
		elseif ($table=="courses") {
		# code...
			$sql="SELECT * FROM membership_userrecords WHERE tableName='$table' AND memberID='$currentuser'";
			$result=mysqli_query($con,$sql);
			$rowcount=mysqli_num_rows($result);
			echo $rowcount;
		}
		else{
		# code...
			$sql="SELECT * FROM $table ORDER BY id";
			$result=mysqli_query($con,$sql);
			$rowcount=mysqli_num_rows($result);
			echo $rowcount;
		}
	}

}
function duetoday($table){
	include("db_connect.php");
	$todaydate=date('Y-m-d');
	#select records with current date
	$sql="SELECT id AS dateid FROM analytics WHERE date='$todaydate' ORDER BY id";
	$result=mysqli_query($con,$sql);
	foreach ($result as $currdate => $cdate) {
		# code...current date id
		$cdid=$cdate['dateid'];
	}
	$sql="SELECT * FROM $table WHERE date='$cdid'";
	$result=mysqli_query($con,$sql);
	$rowcount=mysqli_num_rows($result);
	if ($rowcount==0) {
		# code...if no courses due today show alert
		echo '<div class="alert alert-success">
		<strong>No Bookings Due Today</strong>.
		</div>';
	}
	foreach ($result as $allcourses => $course) {
		#store ids for use in retrieving records
		$instituteid=$course['institute_no'];
		$assessmentid=$course['assessment'];
		$studentid=$course['student'];
		$amtid=$course['amount'];
		$dateid=$course['date'];
		#code..get institute details
		$sql="SELECT * FROM institutes WHERE id='$instituteid'";
		$result=mysqli_query($con,$sql);
		foreach ($result as $allinstitutes => $cdetails) {
			# code...store institute details
			$institutename=$cdetails['institutename'];
			$phone=$cdetails['phone'];
			$institute_no=$cdetails['institute_no'];
		}
		#code..get student details
		$sql="SELECT * FROM students WHERE id='$studentid'";
		$result=mysqli_query($con,$sql);
		foreach ($result as $alstudents => $studentdetails) {
			# code...
			$student=$studentdetails['firstname'];
		}
		#code..assessment details
		$sql="SELECT * FROM assessments WHERE id='$assessmentid'";
		$result=mysqli_query($con,$sql);
		foreach ($result as $allassessments => $assessmentdetails) {
			# code...
			$assessment=$assessmentdetails['name'];
		}
		#code..get analytics details
		$sql="SELECT * FROM analytics WHERE id='$dateid'";
		$result=mysqli_query($con,$sql);
		foreach ($result as $allanalytics => $analyticsdetails) {
			# code...
			$date=$analyticsdetails['date'];
			$time=$analyticsdetails['time'];
		}
		#get amount details
		$sql="SELECT * FROM routes WHERE id='$amtid'";
		$result=mysqli_query($con,$sql);
		foreach ($result as $allamounts => $amountdetails) {
			# code...
			$amount=$amountdetails['amount'];
		}
		# code...display the records
		echo '<tr>
		<td>'.$institute_no.'</td>
		<td>'.$institutename.'</td>
		<td>'.$phone.'</td>
		<td>'.$student.'</td>
		<td>'.$assessment.'</td>
		<td>'.$date.'</td>
		<td>'.$time.'</td>
		<td>'.$amount.'</td>
		<td>'.$course['date_booked'].'</td>
		</tr>';
	}
}


?>
