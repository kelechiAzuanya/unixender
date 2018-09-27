<?php
  include('../functions.php');
  $personnel = new personnel();
  $personnel->db_connect();

    //Process Admin  Login

    $username = $personnel->purify($_POST['userName']);
    $password = md5(sha1($personnel->purify($_POST['StaffPwd'])));

    if ($username == '') {
      echo "<div class='alert alert-warning'>Username cannot  be empty.</div>";
    }else if($password == ''){
     echo  "<div class='alert alert-warning'>Password cannot  be empty.</div>";
    }else{
        $result = $con->query("SELECT * FROM employee WHERE username = '$username' AND password = '$password'") or die(mysqli_error($con));
        if ($result->num_rows > 0) {
          session_start();
          $r = $result->fetch_assoc();
          $StaffId = $r['id'];
          $_SESSION['employee'] = $StaffId;
          echo "Success";
        }else{
          echo "Login Detail is incorrect";
        }
    
  }
?>