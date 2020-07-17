<?php
include "config.php";
if(isset($_GET['id'])){
    $id = $_GET['id'];
    echo "alert('success!2');";
    $query = "SELECT file " ."FROM courses WHERE courseId = '$id'";
    $result = mysqli_query($conn,$query)
           or die('Error, query failed');
    $row = mysqli_fetch_array($result);
    $file = $row['file'];
    header("Content-Disposition: attachment; filename=$file");
    ob_clean();
    flush();
    echo $content;
    mysqli_close($connection);
    exit;

    }
