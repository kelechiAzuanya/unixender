<?php
  include('../pages/functions.php');
  $dbcon = new dbcon();
  $conn=new mysqli('localhost','root','','unixender');

    //Process Admin  Login

    $msg_id = $_POST['msg_id'];


    if ($msg_id  != '') {
      $result = $conn->query("SELECT * FROM messages WHERE msg_id = '$msg_id'") or die(mysqli_error($conn));
      if ($result->num_rows>0) {

        $row = $result->fetch_assoc();
        $key = $row['msg_key_dec_key'];
        $msg_uid = $row['msg_uid'];

        $msg_title = Custom_Crypto::decrypt($row['msg_title'], $key);
        $msg_body = Custom_Crypto::decrypt($row['msg_body'], $key);
        //GET ATTACHMENT
        $at_sql = $conn->query("SELECT file_name FROM attachment WHERE msg_uid = '$msg_uid'");
        while ($file_row = $at_sql->fetch_assoc()) {
            $file[] = $file_row['file_name'];
        }
        echo "<h4>Message Titile: ".$msg_title."</h4><b>Message Body:</b> ".$msg_body."<br>";
        if (isset($file)) {
          if (count($file)>0) {
          echo "Attachment:<br>";
          foreach ($file as $key => $value) {
              $part = explode("/", $file[$key]);
              $display_name = $part[1];
              $count = $key+1;
              echo "<b>".$count.".</b> <a href='".$file[$key]."' download > ".$display_name."</a><br>";
          }
        } 
        }
        echo '<a href="../ajax-pages/printMe.php">Print</a>';
        //update the message status
        $sql="UPDATE messages SET msg_status = :status WHERE msg_id = :msg_id";
        try {
          $conn=new PDO('mysql:host=localhost;dbname=unixender', 'root', "");
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $stmt=$conn->prepare($sql);
          $stmt->bindValue(":status", "read");
          $stmt->bindValue(":msg_id", $msg_id);
          $stmt->execute();
        } catch (PDOException $e) {
          
        }
      }else{
        echo "Error decrypting your Message. Please try again later.";
      }
    }
 
?>