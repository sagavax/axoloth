<?php session_start();
$_SESSION['url'] = $_SERVER['REQUEST_URI'];
?>

<?php include("../include/dbconnect.php"); ?>
<?php include_once("../include/config.php"); ?>

<!DOCTYPE html>
 <head>
     <meta charset="utf-8" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    <link href="../css/style_new.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href="../css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel='shortcut icon' href='../eis.png'>
      <title>E.I.S. - Passwords</title>
</head>


<body>
<div id="header">
<!--header -->
<div class="header-logo">e.i.s.</div>
<!--logo -->
<div class="header-menu">
   <!--menu-->
      <?php
      include("../include/menu.php");
      ?>
</div>
<!--menu-->
</div>

  <div id="layout">




    <?php
      $id=$_GET['id'];
      /* chosen server name */
      $sql= "SELECT * FROM tblpasswords WHERE PassID='$id'";
      global $con;
      $result = mysqli_query($con, $sql);
      $row = mysqli_fetch_array($result) ;
    ?>

     <div id="password_view_wrap">
      <form accept-charset="utf-8" method="post" action="password_view_details.php">
      <input type="hidden" name="id" value="<?php echo $row['PassID']?>" >

        <table class="password">
          
            <td><input type="text" value="<?php echo $row['group_name']?>"> </td>
           </tr>

          <tr>
            <td><input type="text" value="<?php echo $row['system_name']?>"></td>
          </tr>
          <tr>
            <td><input id="username_id" type="text" value="<?php echo $row['user_name']?>"></td>
          </tr>
          <tr>
            <td><input id="password_id" type="text" value="<?php echo $row['password']?>"></td>
          </tr>
           <tr>
             <td> <input type="text" value="<?php echo $row['category']?>"></td><td></td>
          </tr>
          <tr>
             <td><input type="text" placeholder="Url" value="<?php echo $row['url']?>"></td>
          </tr>
          <tr>
          <td><input type="text" value="<?php echo $row['date_added']?>"></td>
          </tr>
          
          <tr>
          <td><div class="pass_description" placeholder="description"><?php echo nl2br(htmlspecialchars($row['description'])) ?></div></td>            
          </tr>
       </table>
      </form>



      <?php
        $actual_date=date('Y-m-d H:i:s');
        global $con;
        $sql="UPDATE tblpasswords SET hits=hits+1, date_hit='$actual_date' WHERE PassID=".$row['PassID'];
        $result = mysqli_query($con, $sql);
        
        //logovanie visitora
          if(!empty($_SERVER['HTTP_CLIENT_IP'])){
              //check ip from share internet
              $ip_address = $_SERVER['HTTP_CLIENT_IP'];
          }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
              //to check ip is pass from proxy
              $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
          }else{
              $ip_address = $_SERVER['REMOTE_ADDR'];
          }
        
        $sql="INSERT INTO tblpasswords_visitors (pass_id, visitor_ip_address,visited_date) VALUES (".$row['PassID'].",'$ip_address','$actual_date')";    
        $result = mysqli_query($con, $sql);
        
        //vlozenie to time liny
       $sql="INSERT INTO tblpasswords_timeline (pass_id,pass_action, action_time) VALUES (".$row['PassID'].",'password has been visited','$actual_date')";
       $result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());   
       

        //echo $sql;
      ?>
      
      <!-- additional password information hidden in accordion -->
      <div class="additional_info">
        <button class="accordion">Timeline</button>
          <div class="panel">
            <div class="pass_timeline_tabs">
            <ul><li><a href="password.php?id=<?php echo $id; ?>&view=last_3_visits">Last 3 times</a></li><li><a href="password.php?id=<?php echo $id; ?>&view=all_visits">All visits</a></li></ul>
            </div><!-- pass_timeline_tabs -->
            <div class="pass_timeline_wrap">
              <ul>
            <?php
              if(isset($_GET['view'])){
                $view=$_GET['view'];
                if($view=="last_3_visits"){
                   $sql="SELECT * from tblpasswords_timeline where pass_id=$id ORDER by time_id DESC LIMIT 3"; 
                } else {
                  $sql="SELECT * from tblpasswords_timeline where pass_id=$id ORDER BY time_id ";    
                }
              } else {
                $sql="SELECT * from tblpasswords_timeline where pass_id=$id ORDER by time_id DESC LIMIT 3"; 
              }
              
              
              $result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());   
              while ($row = mysqli_fetch_array($result)) {
                 $id=$row['pass_id'];
                 $pass_action=$row['pass_action'];
                 $action_time=$row['action_time'];

                 echo "<li><div><div class='pass_time'>$action_time</div><div class='pass_action'>$pass_action</div></div></li>";
                 //echo "<li><div class='pass_time'>$action_time</div><div class='pass_action'>$pass_action</div></li>";

              }
              ?>
           </ul>   
            </div><!-- timeline wrap -->
 
          </div><!--panel --timeline -->
        
        <button class="accordion">History</button>
          <div class="panel">
            <div class="pass_history_wrap">
             <?php 
                    $sql="SELECT * from tblpasswords_history where PassID=$id ORDER BY id DESC";
                    $result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());   
                    $num_rows=mysqli_num_rows($result);
                    if($num_rows==0){
                      echo "<strong>No history records.</strong>";
                    } 
                    else  {
                      echo "<table><th class='history_pass'>Password</th><th class='history_change'>Date</th>";
                      while ($row = mysqli_fetch_array($result)) {
                        $id=$row['id'];
                        $password=$row['old_password'];
                        $date_of_change=$row['created_date'];
                        echo "<tr><td>$password</td><td>$date_of_change</td></tr>";
                    }
                    echo "</table>";
                    }  
            
                ?>
            
              </div><!-- history wrap -->
          </div>
        <button class="accordion">Charts</button>
          <div class="panel">
              
            <div class="pass_history_chart_wrap">
            
            </div>
          </div>
      </div><!--class additional info wrapper -->

       <div class="action">
        <a href='index.php' class="btn-small">&lt;&lt; Back</a>
       </div>
       <div style="clear:both"></div>
    </div><!-- password view wrap -->
    
    
  </div><!-- layout-->

  <script>
            var acc = document.getElementsByClassName("accordion");
            var i;

            for (i = 0; i < acc.length; i++) {
                acc[i].addEventListener("click", function() {
                    this.classList.toggle("active");
                    var panel = this.nextElementSibling;
                    if (panel.style.display === "block") {
                        panel.style.display = "none";
                    } else {
                        panel.style.display = "block";
                    }
                });
          }
      </script>
  </body>
