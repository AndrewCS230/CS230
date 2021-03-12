<?php

require 'dbhandler.php';
session_start();

define('KB', 1024);
define('MB', 1048576);

if(isset($_POST['gallery-submit'])){

    $file = $_FILES['gallery-image'];
    $file_name = $file['name'];
    $file_temp_name = $file['tmp_name'];
    $file_error = $file['error'];
    $file_size = $file['size'];

    $title = $_POST['title'];
    $descript = $_POST['descript'];
    
    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    $allowed = array('jpg', 'jpeg', 'png', 'svg', 'webp');

    if($file_error != 0){
        header('location: ../admin.php?error=UploadError');
        exit();
    }

    if(!in_array($ext, $allowed)){
        header('location: ../admin.php?error=InvalidType');
        exit();
    }

    if($file_size > 4*MB){
        header('location: ../admin.php?error=FileSizeExceeded');
        exit();
    }

    else {
        $new_name = uniqid('', true).".".$ext;
        
        $destination = '../gallery/'.$new_name;

        $sql = "INSERT INTO gallery (title, descript, profpic) VALUES (?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("Location:../admin.php?error=SQLInjection");
            exit();
        }else{
        mysqli_stmt_bind_param($stmt, "sss", $title, $descript, $destination);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        move_uploaded_file($file_temp_name, $destination);
        header('location: ../admin.php?success=GalleryUpload');
        exit();
    }

       
    }

    echo print_r($file);

}else{
    header('location: ../admin.php');
    exit();
}