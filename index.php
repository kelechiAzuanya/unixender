<?php
/*echo md5(sha1(""));*/
include('pages/functions.php');
session_start();

if($_SERVER['REQUEST_METHOD']=='POST'){
    //get their value
    $username=$_POST['username'];
    $password= md5(sha1($_POST['password']));
     $conn=new mysqli('localhost','root','','unixender');
   
     $qq = $conn->query("SELECT * FROM detail WHERE username = '$username' AND password = '$password'");
     if ($qq->num_rows>0) {
       $rowww = $qq->fetch_assoc();
       $_SESSION['username'] = $rowww['username'];
       $category = $rowww['category'];
       switch ($category) {
       case 'stf':
                 $_SESSION['username']=$username;
                 $_SESSION['category']='staff';
               header('location:pages/stf_portal.php');
                break;
            case 'mgt':
                 $_SESSION['username']=$username;
                 $_SESSION['category']='mgt';
                 header('location:pages/mgt_portal.php');

                break;
            case 'std':
                 $_SESSION['username']=$username;
                 $_SESSION['category']='student';
                header('location:pages/std_portal.php');
                break;
            default:
                echo "<script>alert('Login Detail is incorrect.')</script>";
                break;
    }
     }
}

?>




<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>unixender</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="assets/fonts/stylesheet.css">
         

            <style type="text/css">
                #button{
                        background-color: #99cc00;
                        font-size: 18px;
                        color: white;
                        font-family: Century Gothic;
                        border: 0;
                    }
                    #signuptext{
                            font-family: cursive;
                            font-size: 24px;
                            font-style: italic;
                            color: white;
                    }
                    div.whiseparator{
                        width: 200px;
                    }
                    .inputStyle{
                        font-family: Century Gothic;
                        color: #f44336;
                        background-color: #ffffcc;
                        border:0; 
                        text-align: center;
                    }
                
            </style>

        <!--For Plugins external css-->
        <link rel="stylesheet" href="styles/plugins.css" />

        <!--Theme custom css -->
        <link rel="stylesheet" href="styles/style.css">

        <!--Theme Responsive css-->
        <link rel="stylesheet" href="styles/responsive.css" />
        <link rel="stylesheet" href="styles/custom_style.css" />
        <link rel="stylesheet" href="styles/bootstrap.css">
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->



        <section id="home" class="home">
            <div class="overlay">
                <div class="container">
                    <div class="div-menu">
                     
                    </div>
                    <div class="row">
                        <div class="col-sm-12 ">
                            <div class="main_home_slider text-center">
                                <div class="single_home_slider">
                                    <div class="main_home wow fadeInUp" data-wow-duration="700ms">
                                        <h1>Unixender</h1>
                                        
                                        <p class="subtitle">...easing university communication mobility</p>

                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                 
                  <center>
                   <form style="color: white;" method="POST"> 
                    <h3>Login</h3>
                    <div class="whiseparator"></div>
                    <div>
                            <input type="text"  name="username" class="inputStyle" 
                            placeholder="Enter username" required="">
                          
                            <input type="Password"  name="password"
                            placeholder="Enter Password" class="inputStyle" required="">
                            <button type="submit" id="button">Submit</button>

                     </div>
                   </form>
                   <div class="whiseparator"></div>
                  <div id="signuptext"><h3>Do not have an account? <a href="#idmodal" data-toggle="modal" id="signuptext"><u>Sign up</u>  </a></h3></div>
                   </center>     
                   
             
            </div>
            </div>
        </section>

         <!-- Modal -->
            <div class="modal fade" id="idmodal" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
            
            <h4 class="modal-title" id="myModalLabel">
            SIGNUP
            </h4>
            <button type="button" class="close"
            data-dismiss="modal" aria-hidden="true">
            &times;
            </button>
            </div>
            <div class="modal-body">
              <!--Modal's body-->
                  <div class="mssg"></div>
                    <div class="form-group">
                      <input type="text" class="form-control" id="s_username"  placeholder="Enter Username">
                    </div>  <!--input form group--> <hr>
                    <div class="form-group">
                      <input type="password" class="form-control" id="s_password"  placeholder="Password">
                    </div>
                    <hr>
                     <div class="form-group">
                      <input type="password" class="form-control" id="msg_dspkey"  placeholder="Message Dispatch Key">
                      <small><i>This is used to encrypt your sent messages.</i></small>
                    </div>
                    <hr>
                    <div class="form-group">
                      <input type="password" class="form-control" id="msg_keydec_key"  placeholder="Message Key Decrypt Key">
                      <small><i>This is used to decrypt all messages sent to you.</i></small>
                    </div>
                    <hr>
                    <div class="radio">
                      <label>
                        <input type="radio" value="mgt" name="usercat" id="usercat"> &nbspManagement
                      </label>
                    </div> <!--management checkbox  --><hr>
                    <div class="radio">
                      <label>
                        <input type="radio" value="stf" name="usercat" id="usercat">&nbspStaff
                      </label>
                    </div> <!--Staff checkbox  --> <hr>

                    <div class="radio">
                      <label>
                        <input type="radio" value="std" name="usercat" id="usercat">Student
                      </label>
                      <hr>
                </div>
                <center><button class="btn btn-success" id='signupBtn' onclick=signUp("signupBtn")>SIGNUP</button></center>
                      <!--Use php to display search results in a selectable box-->
              
            </div> 
            </div><!-- /.modal-content -->
            </div><!-- /.modal -->


         <script src="scripts/jquery-3.3.1.min.js"></script>
        <script src="scripts/bootstrap.min.js"></script>
        <script src="scripts/plugins.js"></script>
        <script src="scripts/main.js"></script>
        <script src="scripts/custom.js"></script>

    </body>
</html>
