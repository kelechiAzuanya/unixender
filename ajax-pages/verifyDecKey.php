<?php
  include('../pages/functions.php');
  $dbcon = new dbcon();
  $conn=new mysqli('localhost','root','','unixender');

    //Process Admin  Login

    $dec_key = trim($_POST['dec_key']);
    session_start();
    $username = $_SESSION['username'];

    $user_id = $dbcon->getUserIdByUsername($username);

    if ($dec_key  != '') {
      $result = $conn->query("SELECT * FROM msg_keys WHERE id = '$user_id' AND msg_keydec_key = '$dec_key'") or die(mysqli_error($conn));
      if ($result->num_rows>0) {
        echo "Success";
      }else{
        echo "Wrong";
      }
    }
    
  
?>