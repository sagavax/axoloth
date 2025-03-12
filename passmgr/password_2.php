<?php session_start();
$_SESSION['url'] = $_SERVER['REQUEST_URI'];
?>

<?php include("../include/dbconnect.php"); ?>
<?php include_once("../include/config.php"); ?>

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
          <td><textarea><?php echo nl2br(htmlspecialchars($row['description'])) ?></textarea></td>            
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
