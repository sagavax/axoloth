<?php session_start();

include("include/dbconnect.php"); 


if(isset($_POST['login'])){
  $username=mysqli_real_escape_string($con, $_POST['username']);
  $password=mysqli_real_escape_string($con, $_POST['password']);
  
global $con;

$sql="select * from tblusers where login = '$username' and heslo = '$password'";
//$row = mysqli_fetch_array($con,$result);
$result = mysqli_query($con,$sql);
$overeni = mysqli_num_rows($result);
//echo "Pocet riadkov:".$overeni;

if($overeni == 1) {
    $row = mysqli_fetch_array($result);
    $_SESSION['login'] = stripslashes($row['login']);
    //$_SESSION['id'] = $row["id"];
    //echo "url:".$_SESSION['url'];
    $curr_date=date('Y-m-d H:i:s');
    $curr_action="successful_login";
    $curr_app="main_page";
    $text_logu="user has been logged in sucessfuly";
    $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
    $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error());
    if(!isset($_SESSION['url'])){
      echo "<script>alert('Nie je predchadzajuca stranka!!')</script>";
     header('location:dashboard.php');
    } else {
      echo $_SESSION['url'];
    header('location:'.$_SESSION['url']);
  }
    //header("Location: serveris.php");
    
} elseif ($overeni==0) {
   
   $curr_action="unsuccessful_login";
   $curr_app="main_page";
   $curr_date=date('Y-m-d H:i:s');
   $text_logu="unsuccess attepmt to login";
   $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
   $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error());
   echo "<script>alert('Bad username or password');
   location.href='index.php';</script>";

}

}

?>


<!DOCTYPE html>
<!-- saved from url=(0047)file:///C:/wamp/www/eis/materialize/index.html# -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
        <link rel='shortcut icon' href='eis.png'>
        <title>E.I.S. - Informating system</title>
        <style type="text/css" media="screen">
        

            .login-page {
              width: 360px;
              padding: 8% 0 0;
              margin: auto;
            }
            .form {
              position: relative;
              z-index: 1;
              background: #FFFFFF;
              max-width: 360px;
              margin: 0 auto 100px;
              padding: 45px;
              text-align: center;
              box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
            }
            .form input {
              font-family: "Open Sans", sans-serif;
              outline: 0;
              background: #f2f2f2;
              width: 100%;
              border: 0;
              margin: 0 0 15px;
              padding: 15px;
              box-sizing: border-box;
              font-size: 14px;
            }
            .form button {
              font-family: "Open Sans", sans-serif;
              text-transform: uppercase;
              outline: 0;
              background: #1b7fcc;
              width: 100%;
              border: 0;
              padding: 15px;
              color: #FFFFFF;
              font-size: 14px;
              -webkit-transition: all 0.3 ease;
              transition: all 0.3 ease;
              cursor: pointer;
            }
            .form button:hover,.form button:active,.form button:focus {
              background:#126db3;
            }
            .form .message {
              margin: 15px 0 0;
              color: #b3b3b3;
              font-size: 12px;
            }
            .form .message a {
              color: #4CAF50;
              text-decoration: none;
            }
            .form .register-form {
              display: none;
            }
            .container {
              position: relative;
              z-index: 1;
              max-width: 300px;
              margin: 0 auto;
            }
            .container:before, .container:after {
              content: "";
              display: block;
              clear: both;
            }
            .container .info {
              margin: 50px auto;
              text-align: center;
            }
            .container .info h1 {
              margin: 0 0 15px;
              padding: 0;
              font-size: 36px;
              font-weight: 300;
              color: #1a1a1a;
            }
            .container .info span {
              color: #4d4d4d;
              font-size: 12px;
            }
            .container .info span a {
              color: #000000;
              text-decoration: none;
            }
            .container .info span .fa {
              color: #EF3B3A;
            }
            body {
              background: #99ddff; /* fallback for old browsers */
              font-family: "Open Sans", sans-serif;
              
            }

              
               
        </style>
        
</head>
<body>

        <div class="login-page">
          <div class="form">
           <form class="login-form" action="" method="post">
              <input type="text" placeholder="username" name="username" autocomplete="off">
              <input type="password" placeholder="password" name="password" autocomplete="off" />
              <button name="login">login</button>
              </form>
          </div>
        </div> 

        </body>
  </html>