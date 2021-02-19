<?php

if(isset($_POST['Login-submit'])){
    require 'dbhandler.php';

    $uname = $_POST['uname-email'];
    $pswd = $_POST['pwd'];

    if(empty($uname)||empty($pswd)){
        header("Location:../login.php?error=EmptyField");
        exit();
    }
    $sql = "SELECT * FROM users WHERE uname=? OR email=?";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("Location:../login.php?error=SQLInjection");
        exit();
    }else{
        mysqli_stmt_bind_param($stmt, "ss", $uname, $uname);
        mysqli_stmt_execute($stmt);
        $result = msqli_stmt_get_result($stmt);
        $data = mysqli_fetch_assoc($result);

        if(empty($data)){
            header("Location:../login.php?error=UserDNE");
        }
        else{
            $pass_check = password_verify($pswd, $data['password']);
            if($pass_check == true){
                session_start();
                $_SESSION['uid'] = $data['uid'];
                $_SESSION['fname'] = $data['fname'];
                $_SESSION['uname'] = $data['uname'];

                echo "<h1>It Worked!</h1><p>$uname</p>";
            }else{
                header("Location:../login.php?error=WrongPass");
            }
        }
    }

}else{
    header("Location:../login.php");
    exit();
}