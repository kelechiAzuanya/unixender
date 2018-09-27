  <?php
   include('functions.php');
   session_start();

   if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
  }
  $dbcon= new dbcon();

  $mssg = null;
  GLOBAL $msg_uid;
  $msg_uid = uniqid();

  if ( isset( $_POST['homeSubmit'] ) ) {
  // cut the recipients id by a delimeter 
   $recipIdArray = explode( ';', $_POST['rcid']);

   if (count($recipIdArray)<=1) {
    if(sendMessage($recipIdArray[0]) == "Success"){
      $mssg =  '<div class="alert alert-success" role="alert">
      Successfully Uploaded
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
      </button>
      </div>';
    }else{
      $mssg =  '<div class="alert alert-danger" role="alert">
      Error sending your message.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
      </button>
      </div>';
    }
  }else{
    $arr_size=count($recipIdArray);

    $multiple_success = 0;
    $multiple_failure = 0;

    for ($i=0; $i < $arr_size ; $i++) { 
      if(sendMessage($recipIdArray[$i]) == "Success"){
        $mssg =  '<div class="alert alert-success" role="alert">
        Successfully Uploaded
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
        </div>';
      }else{
        $mssg =  '<div class="alert alert-danger" role="alert">
        Error sending your message.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
        </div>';
      }

  }
}
}
function getExtension($str) {

  $i = strrpos($str,".");
  if (!$i) { return ""; } 

  $l = strlen($str) - $i;
  $ext = substr($str,$i+1,$l);
  return $ext;
}

function sendMessage($recipId){
//get the values of each of the components
   //  $recipId=$_POST['rcid'];
  $mssg_title=$_POST['mssg_title'];
  $msg_body=$_POST['mbody'];
  $keyy= getMessageKey();
  // get attachments
  //Specify property image directory
  $file_dir = "files/";
        //Generate a file name with slug
  $file_name = uniqid()."-".date("Y-m-d-h-m-s");
  $file_upload = 0;
  GLOBAL $msg_uid;
  foreach ($_FILES['file']['name'] as $key => $value) {

    if (count($_FILES['file']) > 0) {
                //PROCEED TO PROCCESS UPLOAD
                //PROCESS FILES
      $filename = stripslashes($_FILES['file']['name'][$key]);
      $extension = getExtension($filename);
      $extension = strtolower($extension);
                    //Specify an image's unique name using the file $key
      $file_destination = $file_dir.$file_name."-".$key.".".$extension;
                    //Do upload
      if (move_uploaded_file($_FILES['file']['tmp_name'][$key], $file_destination)){
        $conn=new PDO('mysql:host=localhost;dbname=unixender', 'root','');
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql="INSERT INTO attachment (msg_uid, file_name) VALUES(:msg_uid, :file_name)";
        $stmt=$conn->prepare($sql);
        $stmt->bindParam(":msg_uid",$msg_uid, PDO::PARAM_STR);
        $stmt->bindValue(":file_name",$file_destination);
        $stmt->execute();
        $file_upload = +1;
      }
    }
  }

          //Submit Message
  $dbcon= new dbcon();

  $result = $dbcon->sendMesgDB($_SESSION['username'], $msg_uid, $recipId, $keyy,$mssg_title,$msg_body,"Message");
  if ($result == "Success") {
    return "Success";
  }else{
    return "Failure";
  }
}
function getMessageKey()
{
  if (!isset($_POST['default_key'])) {
    $custom_key=$_POST['custom_key'];
    return $custom_key;
  }
  try {
   $dbcon= new dbcon();
   $userId= $dbcon->getUserId($_SESSION['username']);
   return  $dbcon->getMsgKey($userId);
 } catch (Exception $e) {
  $e->getMessage();
}
}

/////////////////////////////////CHANGE PASSWORD
  if (isset($_POST['changePasswordBtn'])) {
      $oldpwd = md5(sha1($_POST['oldpwd']));
      $newpwd = md5(sha1($_POST['newpwd']));
      $vpwd = md5(sha1($_POST['vpwd']));
      $conn=new mysqli('localhost','root','','unixender');
      $veri = $conn->query("SELECT * FROM detail WHERE password = '$oldpwd'");
      if ($veri->num_rows==0) {
        //Wrong Old password
         $mssg =  '<div class="alert alert-danger" role="alert">
                    Old Password is Incorrect.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
      }else{
        //Check if password match
        if ($newpwd != $vpwd) {
          //Password Mismatch
          $mssg =  '<div class="alert alert-danger" role="alert">
                    Password Mismatch.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        }else{
          //Password correct
          //Change Password
          $username = $_SESSION['username'];
          $c_pass = $conn->query("UPDATE detail SET password = '$newpwd' WHERE username = '$username'");
          if ($c_pass == true) {
            $mssg =  '<div class="alert alert-success" role="alert">
                      Password Successfully changed.
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
          }
        }
      }

  }
/////////////////////////////////CHANGE PASSWORD ENDS HERE

/////////////////////////////////CHANGE MKDK
  if (isset($_POST['mkdkBtn'])) {
      $mkdk_oldpwd = $_POST['mkdk_oldkey'];
      $mkdk_newpwd = $_POST['mkdk_newkey'];
      $mkdk_vpwd = $_POST['mkdk_vnewkey'];
      $conn=new mysqli('localhost','root','','unixender');
      $veri = $conn->query("SELECT * FROM msg_keys WHERE msg_keydec_key = '$mkdk_oldpwd'");
      if ($veri->num_rows==0) {
        //Wrong Old key
         $mssg =  '<div class="alert alert-danger" role="alert">
                    Old Key is Incorrect.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
      }else{
        //Check if key match
        if ($mkdk_newpwd != $mkdk_vpwd) {
          //key Mismatch
          $mssg =  '<div class="alert alert-danger" role="alert">
                    Key Mismatch.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        }else{
          //key correct
          //Change key
          $uid = $dbcon->getUserIdByUsername($_SESSION['username']);
          $c_pass = $conn->query("UPDATE msg_keys SET msg_keydec_key = '$mkdk_newpwd' WHERE id = '$uid'") or die(mysqli_error($conn));
          if ($c_pass == true) {
            $mssg =  '<div class="alert alert-success" role="alert">
                      Key Successfully changed.
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
          }else{
              $mssg =  '<div class="alert alert-danger" role="alert">
                    Error changing your key.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
          }
        }
      }

  }
/////////////////////////////////CHANGE MKDK ENDS HERE

/////////////////////////////////CHANGE MDK
  if (isset($_POST['mdk_Btn'])) {
      $mdk_oldpwd = $_POST['mdk_oldkey'];
      $mdk_newpwd = $_POST['mdk_newkey'];
      $mdk_vpwd = $_POST['mdk_vnewkey'];
      $conn=new mysqli('localhost','root','','unixender');
      $veri = $conn->query("SELECT * FROM msg_keys WHERE msg_dspkey = '$mdk_oldpwd'");
      if ($veri->num_rows==0) {
        //Wrong Old key
         $mssg =  '<div class="alert alert-danger" role="alert">
                    Old Key is Incorrect.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
      }else{
        //Check if key match
        if ($mdk_newpwd != $mdk_vpwd) {
          //key Mismatch
          $mssg =  '<div class="alert alert-danger" role="alert">
                    Key Mismatch.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        }else{
          //key correct
          //Change key
          $uid = $dbcon->getUserIdByUsername($_SESSION['username']);
          $c_pass = $conn->query("UPDATE msg_keys SET msg_dspkey = '$mdk_newpwd' WHERE id = '$uid'") or die(mysqli_error($conn));
          if ($c_pass == true) {
            $mssg =  '<div class="alert alert-success" role="alert">
                      Key Successfully changed.
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                      </button>
                    </div>';
          }else{
              $mssg =  '<div class="alert alert-danger" role="alert">
                    Error changing your key.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
          }
        }
      }

  }
/////////////////////////////////CHANGE MDK ENDS HERE
   ?>
    <!DOCTYPE html>
    <html>
    <head>
    	<title></title>
    	  <link href="../styles/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" type="text/css" href= "../styles/custom.css">
      <link rel="stylesheet" href="assets/fonts/stylesheet.css">

    </head>
          <style type="text/css">
            .nav-right{
        float: right;
        padding-left: 600px;
        }
       .nav-right a{
         color: white; 
        font-family: Century Gothic; 
        font-size: 18px; 
        font-weight: bold;
       }
       .nav-right a:hover {
        color: black;
        }
       .nav-right a.active {
          background-color: #4CAF50;
          color: white;
        }
        .list-group-item{
          border-color: #99cc00;
          color: #99cc00;
        }

        .list-group a.active{
          background-color: #99cc00;
        }
        .navbar{
      background-color: #99cc00;
        }
        .btn-logout{
          color: white; 
        font-family: Century Gothic; 
        font-size: 14px; 
        font-weight: bold;
        border-color:white; 
        }

        #body-style{
          font-family: Century Gothic;
          color: #99cc00;
        }
        #tiptxt{
          font-family: Century Gothic;
          font-weight: bold;
        }
        #sub-t{
          font-weight: bold;
          color: #99cc00;
        }
        #btn-body{
          background-color:#99cc00;
          color: white;
          border-color: #99cc00;
        }
        #com-style{
          border-color: #99cc00;
        }

         .navbar-brand{
         color: white;
         font-family:Century Gothic; 
         font-size: 28px; 
         font-weight: bold;
         padding-left: 65px;
        }
          </style>
        
     <body id="body-style">


    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #99cc00;">
  <a class="navbar-brand" href="#" style="color: white;">Unixender</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  

  <div class="nav-right navbar-collapse" id="navbarSupportedContent">
     <ul class="nav navbar-nav navbar-right">
      <li class="nav-item active">
        <a class="nav-link" style="color: white" href="#">Welcome <?php echo $_SESSION['username']." (".$_SESSION['category'].")"; ?> <span class="glyphicon glyphicon-user"></span></a>
      </li>
     <a href="Logout.php"><button class="btn-logout btn btn-outline-success my-2 my-sm-0 " type="button" >Logout</button></a> 
    </ul>

  </div>
</nav>


 
<div class="container" id="accordion">

  <div class="row">
    <div class="col-xs-12 col-md-12" id="pagelay" >

      <div class="row" style="margin-bottom: 25px; padding-top: 25px;">
        <div class="col-sm-12 text-center">Your Message ID: <?php echo $dbcon->getUserIdByUsername($_SESSION['username'])?></div>
        <div class="col-4">

          <div class="list-group" id="myList" role="tablist">
            <a class="list-group-item list-group-item-action active" data-toggle="list" href="#home" role="tab">Home</a>
            <a class="list-group-item list-group-item-action" data-toggle="list" href="#inbox" role="tab">Inbox
              <?php 
              //check for new messages
               getInboxSum($dbcon->getUserIdByUsername($_SESSION['username']));
              ?>

            </a>
            <a class="list-group-item list-group-item-action" data-toggle="list" href="#memo" role="tab">Memo</a>
            <a class="list-group-item list-group-item-action" data-toggle="list" href="#settings" role="tab">Setting</a>
          </div>
        </div>

        <!-- Tab panes -->
        <div class="col-8">
          <?php echo $mssg ?>
          <div class="tab-content">
            <div class="tab-pane active" id="home" role="tabpanel">
              <!--Code for the contents of Home goes here-->
              <div style="color: #99cc00;"><center><h4><b>Send Secured Messages</b></h4></center><br></div>

              <form class="form-horizontal" method="post" role="form" enctype="multipart/form-data">

                <div class="row">

                  <div class="col-lg-8">
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Enter Recipient id" required="" name="rcid" id="com-style"> &nbsp;<b>Or</b>&nbsp;
                      <span class="input-group-btn">
                        <button class="btn btn-default" type="button" data-toggle="modal" data-target="#idmodal" id="btn-body">
                          Search recipient id
                        </button>
                      </span> 
                    </div><!-- /input-group -->
                  </div><!-- /.col-lg-6 --> <br><br>

                </div><!-- /.row -->

                <div class="row">
                  <div class="col-lg-8">
                    <div class="form-group" >
                      <label for="text" id="sub-t"> Message Title</label><br>
                      <input type="text" name="mssg_title" placeholder="Message title" required="" id="com-style">
                    </div></div></div>

                    <!--Text Area Container-->
                    <div class="form-group">
                     <label for="tarea" id="sub-t">Compose Message</label>
                     <textarea class="form-control" rows="3" placeholder="Message Body" required="" name="mbody" id="com-style"></textarea>
                   </div>

                   <!--Attachment Component-->
                   <div class="row">
                    <div class="col-lg-8">
                     <div class="form-group">
                       <label class="control-label">Attach (File/Media)</label>
                       <input type="file" name="file[]" multiple="">
                     </div> </div></div>  
<!--
     <div class="custom-file col-lg-8">
    <input type="file" name="attachment[]" class="custom-file-input" id="validatedCustomFile">
    <label class="custom-file-label" for="validatedCustomFile">Choose file...</label>
    <div class="invalid-feedback">Example invalid custom file feedback</div>
  </div>    -->

  <div class="form-group">
   <div class="checkbox">
     <label>
       <input type="checkbox" name="default_key"> Use Default Key &nbsp<i>OR</i>
     </label> 
     <input type="text"  id="com-style" class="form-control col-md-3" name="custom_key" placeholder="Enter Unique Key">
   </div></div>

   <!--Submit button-->
   <div class="row">
    <div class="form-group">
      <div class="col-md-8">
        <button type="Submit" class="btn btn-default" name="homeSubmit" id="btn-body"> Send</button>
      </div> 
    </div></div>

  </form>
</div> 

<div class="tab-pane" id="inbox" role="tabpanel">
  <!--=============================Code for inbox's content goes here ==============================-->
  <?php  
  
  $userId= $dbcon->getUserId($_SESSION['username']);
  try {
    $conn=new PDO('mysql:host=localhost;dbname=unixender', 'root','');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql="SELECT * FROM messages WHERE msg_receiver= :msg_receiver ORDER BY msg_id DESC";
    $stmt=$conn->prepare($sql);
    $stmt->bindValue(":msg_receiver", $userId);
    $stmt->execute();
    while ($row = $stmt-> fetch()) {

     echo '<a href="javascript:showMssgModal('.$row['msg_id'].')" class="list-group-item list-group-item-action  flex-column align-items-start">

     <div class="d-flex w-100 justify-content-between">
     <h5 class="mb-1"><b>Message Title: </b>'. $row['msg_title'].'</h5>
     <small>'.$row['date_sent'].'</small>
     </div>

         <p class="mb-1"><b>Message body: </b>'.$row['msg_body'].'</p>
     <small>'.$row['msg_type'].' sent from '.$row['msg_sender'] .'&nbsp&nbsp<i> '.statusColor($row['msg_status']). '</i></small>
     </a>';


   }
 } catch (Exception $e) {
  die($e->getMessage());
}
?>

</div>  <!--Tab-pane class ends here  -->



        <!--=========================================================================================================-->                        

        <div class="tab-pane" id="settings" role="tabpanel">
          <!--Content of Setting body  -->

          <!--Accordion to hold Functionalities-->
          <div id="accordion" >

            <div class="card">
              <div class="card-header" >
                <a class="card-link" aria-expanded="false" data-toggle="collapse" href="#collapseOne" >
                  <center>Set Password</center> 
                </a>
              </div>
              <div id="collapseOne"  class="collapse show" data-parent="#accordion">
                <div class="card-body" >
                 <form class="form-horizontal" role="form" method="post">
                  <div class="form-group">
                    <label for="oldpwd" class="col-sm-2 control-label">Old Password</label>
                    <div class="col-sm-10">
                      <input type="Password" class="form-control" id="oldpwd" name="oldpwd" placeholder="Old Password" value="" required="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="npwd" class="col-sm-2 control-label">New Password</label>
                    <div class="col-sm-10">
                      <input type="Password" class="form-control" id="newpass" name="newpwd" placeholder="Password" value="" required="">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="vpwd" class="col-sm-2 control-label">Verify Password</label>
                    <div class="col-sm-10">
                      <input type="Password" class="form-control" id="vpwd" name="vpwd" placeholder="Verify Password" value="" required="">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                      <input id="submit" name="changePasswordBtn" type="submit" value="Submit" class="btn btn-primary" style="background-color:#99cc00; border-color: #99cc00;">
                    </div>
                  </div>

                </form>

              </div>
            </div>
            <!--Set Message Decrypt key Accordion  Starts here-->
            <div class="card">
              <div class="card-header">
                <a class="collapsed card-link" data-toggle="collapse" href="#collapseTwo">
                 <center>Set Message Dispatch Key</center> 
               </a>
             </div>
             <div id="collapseTwo" class="collapse" data-parent="#accordion">
              <div class="card-body">
               <form class="form-horizontal" role="form" method="post" >
                <div class="form-group">
                  <label for="oldkey" class="col-sm-2 control-label">Old Key</label>
                  <div class="col-sm-10">
                    <input type="password" class="form-control" id="oldpass" name="mdk_oldkey" placeholder="Old Key" value="" required="">
                  </div>
                </div>
                <div class="form-group">
                  <label for="newkey" class="col-sm-2 control-label">New Key</label>
                  <div class="col-sm-10">
                    <input type="password" class="form-control" id="newkey" name="mdk_newkey" placeholder="New Key" value="" required="">
                  </div>
                </div>
                <div class="form-group">
                  <label for="vnewkey" class="col-sm-4 control-label">Verify New Key</label>
                  <div class="col-sm-10">
                    <input type="password" class="form-control" id="vnewkey" name="mdk_vnewkey" placeholder="Verify New Key" value="" required="">
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-10 col-sm-offset-2">
                    <input id="submit" name="mdk_Btn" type="submit" value="Submit" class="btn btn-primary"
                    style="background-color:#99cc00; border-color: #99cc00;">
                  </div>
                </div>

              </form>      

            </div>
          </div>  
        </div>  <!--collapse two  -->
        <!--Set Message Key Decrypt Key Accordion Starts here-->
        <div class="card">
          <div class="card-header">
            <a class="collapsed card-link" data-toggle="collapse" href="#collapseThree">
             <center>Set Message Key Decrypt Key</center> 
           </a>
         </div>
         <div id="collapseThree" class="collapse" data-parent="#accordion">
          <div class="card-body">
           <form class="form-horizontal" role="form" method="post">
            <div class="form-group">
              <label for="oldkey" class="col-sm-2 control-label">Old Key</label>
              <div class="col-sm-10">
                <input type="password" class="form-control" id="mkdk_oldkey" name="mkdk_oldkey" placeholder="Old Key" value="" required="">
              </div>
            </div>
            <div class="form-group">
              <label for="newkey" class="col-sm-2 control-label">New Key</label>
              <div class="col-sm-10">
                <input type="password" class="form-control" id="mkdk_newkey" name="mkdk_newkey" placeholder="New Key" value="" required="">
              </div>
            </div>
            <div class="form-group">
              <label for="vnewkey" class="col-sm-4 control-label">Verify New Key</label>
              <div class="col-sm-10">
                <input type="password" class="form-control" id="mkdk_vnewkey" name="mkdk_vnewkey" placeholder="Verify New Key" value="" required="">
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-10 col-sm-offset-2">
                <input id="submit" name="mkdkBtn" type="submit" value="Submit" class="btn btn-primary"
                style="background-color:#99cc00; border-color: #99cc00;">
              </div>
            </div>

          </form> 

        </div>
      </div>  
    </div>  <!--collapse Three  -->
  </div>
</div>

</div>
</div>

</div></div></div></div>

<!--===============================================================================================-->
<!-- Modal for searching id -->
<div class="modal fade" id="idmodal" tabindex="-1" role="dialog"
aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">

      <h4 class="modal-title" id="myModalLabel">
        Search Recipients id
      </h4>
      <button type="button" class="clrS close"
      data-dismiss="modal" aria-hidden="true">
      &times;
    </button>
  </div>
  <div class="modal-body">
    <!--Modal's body-->
    <form class="form-inline" method="post" style="padding-left: 50px;">
      <div class="form-group">
        <input type="text" name="susername"  placeholder="username to search" id="comstyle" class="inP">
      </div>  <!--input form group--> &nbsp&nbsp

      <label>
       <button type="button" class="btn_search btn btn-default" name="btns" id="btn-body">Search</button>
     </label>
     <!--Use php to display search results in a selectable box-->
     <div class="searchResult"></div>
   
  </form>
</div>
<div class="modal-footer">
  <button type="button" class="clrS btn btn-default" name="btn-search">
  Clr Result
</button>
  <button type="button" class="clrS btn btn-default"
  data-dismiss="modal">Close
</button>

</div></div>
</div><!-- /.modal-content -->
</div><!-- /.modal -->

<!--Modal for reading inbox message -->
<!-- Modal -->
<div class="modal fade" id="inboxmodal" tabindex="-1" role="dialog"
aria-labelledby="myModalLabe" aria-hidden="true" data-backdrop="static" data-keyboard="false">
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">

      <h4 class="modal-title" id="myModalLabe">
        Inbox Message
      </h4>
      <input type="hidden" id="msg_id_holder">
      <button type="button" class="close"
      data-dismiss="modal" aria-hidden="true">
      &times;
    </button>
  </div>
  <div class="modal-body">
    <!--Modal's body-->

            <!-- have an array that gets the message, msgid  & with the msg id we can get the msgkeydeckey     */
              Enter the msg_key_decrypt_key; you need it so as to view the decrypt key of the sent message   -->
              <form class="form-horizontal" role="form">
                <div class="row">
                 <div class="col-lg-12">
                   <div class="form-group" style="padding-left: 30px">
                    <label for="text" id="sub-t"> Enter Your Message Key Decrypt Key </label><br>
                    <input type="text" class='form-control dec_key' onkeyup="showDecryKey()" name="dec_key" placeholder="Key" required="" id="com-style" autocomplete="off">

                  </div>
                  <div class='decryptedMsg'></div>
                </div></div>

              </form>


            </div>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal -->

      <script src="../scripts/jquery-3.3.1.min.js"></script>
      <!-- Include all compiled plugins (below), or include individual files as needed -->
      <script src="../scripts/bootstrap.min.js"></script>
      <script src="../scripts/custom.js"></script>   

       <script>
      $(function(){
      $('.clr').click(function(event) {
       $('.decryptedMsg').empty();
      });

      $('.clrS').click(function(event) {
       $('.searchResult').empty();
      });

      $('.btn_search').click(function(event) {
        /* Act on the event */
        var searchValue = $("#comstyle").val();
      // alert(searchValue);
        var dataString = 'searchValue='+ searchValue;
        $.ajax({
          type:"POST",
          url:"../ajax-pages/searchUserId.php",
          data: dataString,
          cache: false,
          success: function (res) {
          
              $('.searchResult').html(res);
         
           
          }
        });
      });

    });
    
  </script>
    </body>
    </html>