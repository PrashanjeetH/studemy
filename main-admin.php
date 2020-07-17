
<!--  Student Widget Start -->
     <?php
     $sql = "SELECT * FROM courses";
     $result = $conn->query($sql);
     $input = array("#EC9D96", "#f9dbd2", "#E6EDB7", "#F48BAC", "#9EECBD","#E4E4F9");
     if ($result->num_rows > 0) {
         // output data of each row
         while($row = $result->fetch_assoc()) {
             $rand_color = $input[mt_rand(0, count($input) - 1)];
             $currentuser = getLoggedGroupId();
             $studentId = 50; # The group of the student Group
             if($currentuser == $studentId){
                echo '<div class="col-md-5 col-sm-12 col-xs-12">
                     <div class="panel panel-primary text-center no-boder bg-color-red">
                       <div class="panel-body">

                          <iframe src="'. $row["link"].'">
            							</iframe>
                          <h3></h3>
                       </div>
                       <div class="panel-footer" style="background-color: '. $rand_color.';">
                         <a style="color: black;" href="./student_takeLesson.php?id='.$row['courseId'].'" style="text-decoration:none;color: white"><strong>'. $row['courseName'].'</strong></a>
                       </div>
                     </div>
                   </div>';

              }
            }
          }
          $conn->close();
          ?> <!--Student widgets end-->
