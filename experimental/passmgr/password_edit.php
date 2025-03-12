<?php session_start(); ?>
<?php include("../include/dbconnect.php"); ?>
<?php include("../include/functions.php"); ?>

<?php 
  if(isset($_POST['update_password'])) {
    global $con;
    $customer=$_POST['customer'];
    /*$group_name=mysqli_real_escape_string($con,$_POST['group_name']);*/
    $pass_id=intval($_POST['id']); //password id
    $system_name=mysqli_real_escape_string($con, $_POST['system_name']);
    $user_name=mysqli_real_escape_string($con, $_POST['user_name']);
    $password1=mysqli_real_escape_string($con, $_POST['password']); 
    $old_password=mysqli_real_escape_string($con, $_POST['old_password']); 
     $url=mysqli_real_escape_string($con, $_POST['url']);
     $category=$_POST['category'];
     $description=mysqli_real_escape_string($con, $_POST['description']);

   /* var_dump($_POST);*/

    /* if($old_password==$password1){
       //ziadna zmena
       header("location:index.php");
     } else {
   */ 
     $sql="UPDATE tblpasswords SET cust_id=$customer,system_name='$system_name',user_name='$user_name',password='$password1', url='$url',description='$description' WHERE PassID=$pass_id";

    /* echo $sql;*/
     
     $result=mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error());
    
     //zapis do logu
     $curr_date=date('Y-m-d H:i:s');
      $curr_action="update_password";
      $curr_app="passmgr";
      $text_logu="the password with id $pass_id has been updated";
    
     //zapis do historickych zaznamov

     $sql="INSERT INTO tblpasswords_history (PassID, old_password,created_date) VALUES ($pass_id,'$old_password','$curr_date')";
     //echo $sql;
     $result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());   

      //vlozenie to timeliny
     $sql="INSERT INTO tblpasswords_timeline (pass_id,pass_action, action_time) VALUES ($pass_id,'password has been modiefied','$curr_date')";
     $result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());   



     $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
    //echo $sql;
   
    $result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());
    echo "<script>alert('the password with id $pass_id has been updated');
      location.href='index.php'</script>";
  
}
?>

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
  <script>

      //Random password generator- by javascriptkit.com
      //Visit JavaScript Kit (http://javascriptkit.com) for script
      //Credit must stay intact for use

      var keylist="abcdefghijklmnopqrstuvwxyz123456789"
      var temp=''
     
      function generatepass(plength){
      temp=''
      for (i=0;i<plength;i++)
      temp+=keylist.charAt(Math.floor(Math.random()*keylist.length))
      return temp
      }

      function populateform(enterlength){
      document.edit_password.password.value=generatepass(enterlength)
      }
      </script>
    
       <div id="header">
      <!--header -->
      <div class="header-logo">e.i.s.</div>
      <!--logo -->
      <div class="header-menu">
         <!--menu-->
         <?php include("../include/menu.php") ?>
      </div>
      <div id="txt" style="float:right; margin-right:5px; color:#fff"></div>
   </div>
   </div><!-- header -->
  
  <div id="layout">
    <?php

      /* chosen server name */ 
      $id=$_GET['id'];
      $sql= "SELECT * FROM tblpasswords WHERE PassID='$id'";
      global $con;
      $result = mysqli_query($con,$sql);
      $row = mysqli_fetch_array($result) ;
    ?>
     
     <div id="password_view_wrap">    
      <form accept-charset="utf-8" method="post" action="" name="edit_password">  
      <input type="hidden" name="id" value="<?php echo $row['PassID']; ?>" />
      
        <table class="password">
        
          <tr>
            <td>
                <select name="customer">
                                          <?php 
                                            $cust_id=$row['cust_id'];
                                            $customer=CustomerName($cust_id);
                                            echo "<option value=$cust_id selected=\"selected\">$customer</option>";
                                            $sql1="SELECT * from tblcustomers where customer_status='active' ORDER BY customer_name ASC";
                                            global $con;
                                            $result1 = mysqli_query($con, $sql1);
                                            while ($row1 = mysqli_fetch_array($result1)) {
                                              $cust_id=$row1['cust_id'];
                                              $customer_name=$row1['customer_name'];
                                              echo "<option value='$cust_id'>$customer_name</option>";
                                            }  
                                          ?> 

                                      </select>

            <!--<input type="text" name="group_name" value="<?php echo $row['group_name']?>">--> </td>
           </tr>	
          <tr>
           <td><input type="text" name="system_name" value="<?php echo $row['system_name']?>"></td>    
          </tr>
          <tr>
           <td><input type="text" name="user_name" value="<?php echo $row['user_name']?>"></td>
          </tr>
          <tr>
           <td><div class="embed-button"><input type="text" name="password" id="password" value="<?php echo $row['password']?>"><button onClick="populateform(15)" class="flat-btn" type="button"  id="generuj_pass"><i class="fa fa-refresh"></i></button></div></td>
            <input type="hidden" name="old_password" value="<?php echo $row['password']?>">
          </tr>
          <tr>
            <!--<td> <input type="text" name="category" value="<?php echo $row['category']?>"></td>-->
            <td> <select name="category"> 
                                <option  value=<?php echo $row['category']?> selected><?php echo $row['category'] ?></option>
                                <option value="Private">Private</option> 
                                <option value="Business">Business</option> 
                              </select>
          </td>
          </tr>
          <tr>
            <td><input type="text" name="url" value="<?php echo $row['url']?>"></td>
          </tr>
          <tr> 
            <td><textarea name="description"><?php echo $row['description']?></textarea></td>
          </tr>
          <tr>
            <td style="text-align:right"><a href='index.php' class="flat-btn">&lt;&lt; Back</a> <button type="submit" name="update_password" class="flat-btn">Update</button></td>
           </tr> 
       </table>
      </form>
      
    </div><!-- wrap -->
  </div><!-- layout-->
</body>

