<?php
  include('../pages/functions.php');
  $dbcon = new dbcon();
  $conn=new mysqli('localhost','root','','unixender');

    //Process Admin  Login

    $username = trim($_POST['username']);
    $msg_keydec_key = trim($_POST['msg_keydec_key']);
    $msg_dspkey = trim($_POST['msg_dspkey']);
    $password = md5(sha1(trim($_POST['password'])));
    $category = trim($_POST['category']);
    $id = strtolower($category."_".$username);

    $sql = "SELECT * FROM detail WHERE username = '$username'";
    $r = $conn->query($sql);
    if ($r->num_rows>0) {
      echo "Username already exist.";
    }else{
      //INSERT MESSAGE KEY
      $conn->query("INSERT INTO msg_keys (id,msg_dspkey,msg_keydec_key) VALUES ('$id','$msg_dspkey','$msg_keydec_key')") or die(mysqli_error($conn));

      $sqli_2 = "INSERT INTO detail (id,username,password,category) VALUES ('$id','$username','$password','$category')";
        if ($conn->query($sqli_2)) {
          session_start();
          $_SESSION['username'] = $username;   
          echo "Success";
        }else{
          echo "Error processing your registration. Please try again later.";
        }
    }    
  
?>