<?php

require 'dbhandler.php';
session_start();

define('KB', 1024);
define('MB', 1048576);

if(isset($_POST['prof-submit'])){

    $uname = $_SESSION['uname'];
    $file = $_FILES['prof-image'];
    $file_name = $file['name'];
    $file_temp_name = $file['tmp_name'];
    $file_error = $file['error'];
    $file_size = $file['size'];
    
    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    $allowed = array('jpg', 'jpeg', 'png', 'svg');

    if($file_error != 0){
        header('location: ../profile.php?error=UploadError');
        exit();
    }

    if(!in_array($ext, $allowed)){
        header('location: ../profile.php?error=InvalidType');
        exit();
    }

    if($file_size > 4*MB){
        header('location: ../profile.php?error=FileSizeExceeded');
        exit();
    }

    else {
        $new_name = uniqid('', true).".".$ext;
        
        $destination = '../profiles/'.$new_name;

        $sql = "UPDATE profiles SET profpic='$destination' WHERE uname='$uname'";
        mysqli_query($conn, $sql);

        move_uploaded_file($file_temp_name, $destination);
        header('location: ../profile.php?success=UploadComplete');
        exit();
    }

    echo print_r($file);

}else{
    header('location: ../profile.php');
    exit();
}