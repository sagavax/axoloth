<?php session_start(); ?>
<?php include("../include/dbconnect.php"); ?>
<?php include("../include/functions.php"); ?>

<?php

  
if (isset($_POST['add_event'])) {
       
    if (!isset($_POST['cust_id'])) { //novy event bol vytvoreny nie priamo od zakaznika
      
        $cust_id=$_POST['customer'];
    } else {
          $cust_id=$_POST['cust_id'];
    }
        global $con;
        $event_title=mysqli_real_escape_string($con, $_POST['event_title']);
        $event_color=mysqli_real_escape_string($con, $_POST['event_color']);
        $event_description=mysqli_real_escape_string($con, $_POST['event_description']);
        $event_date=$_POST['event_date'];
        $start_event_date=mysqli_real_escape_string($con, $_POST['start_event_date']);
        

        if(isset($_POST['is_whole_day'])){
          $start_event_time="8:00";
          $end_event_time="23:00";
          $end_event_date = $start_event_date;
        } else {
          $start_event_time=($_POST['event_start_time']);
          $end_event_time=mysqli_real_escape_string($con, $_POST['event_end_time']);
          $end_event_date=mysqli_real_escape_string($con, $_POST['end_event_date']);
        }
       
        //var_dump($_POST);
      
        $event_created=date('Y-m-d H:i:s');
      

        $d1=$start_event_date;
        $d2=$end_event_date;

     

        $days = dateDiff ($d2, $d1);
     // echo $days."</br>";
      
        $date=$d1; // zoberie zaciatok eventu


    if (isset($_POST['is_meeting'])) {
        $is_meeting=1;
    } else {
        $is_meeting=0;
    }


       // print_r(createDateRangeArray($d1, $d2));
      

      $sql="INSERT INTO tblcustomer_events (cust_id, event_title, event_color, event_description, is_meeting,days,event_date, start_event_date, end_event_date,start_event_time, end_event_time,event_created ) VALUES ($cust_id,'$event_title', '$event_color','$event_description',$days, $is_meeting,'$date','$start_event_date','$end_event_date','$start_event_time','$end_event_time','$event_created')";
      //echo "$sql";
    $result=mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error($con));
        $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
      
      $new_event_id=GetLatestEvent(); //ziskam najnovsi event */
     
     //zistit dni medzi dvoma datumami
     
     //ulozit do pola ako $event_days
     
     //iterovat dni a zapisat do tabulky:
     /* for each $event_days as $event_day {
        $sql="INSERT INTO tblcustomer_event_day (custi_id, event_id, event_day) VALUE ($cust_id, $event_id, '$event_day')";
        $result=mysqli_query($con, $sql) or die("MySQL ERROR: ".mysql_error());
      }
      */
    //zapisat do logu novy event day   
  $curr_date=date('Y-m-d H:i:s');
   	 $curr_action="create_event";
   	 $curr_app="event_manager";
   	 $new_event_id=GetLatestEvent();
   	 $undo_action="DELETE from tblevents WHERE id=$new_event_id";
   	 $text_logu="new event with id $new_event_id has been created !";
    $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read,undo_action) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0,'$undo_action')";
    
    $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));

    /*$link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
                        $diary_text="EIS: Bol vytvoreny novy ent s id $new_event_id";
                        $sql="INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
                        $result = mysqli_query($link1, $sql) or die("MySQLi ERROR: ".mysqli_error($link1));
                      mysqli_close($link1);
*/


     echo "<script>alert('New event with id $new_event_id has been created');
      window.location='index.php';   
      </script>";
  
}
 
?>
 
<!DOCTYPE html>
 <head>
    
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1">
                  
    <link href="../css/style_new.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href="../css/event.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="../css/dcalendar.picker.css" rel="stylesheet" type="text/css">
    <link rel='shortcut icon' href='../eis.png'>
    <title>Axoloth - Add new event</title>
  
</head>
  <body>
       </script>
        <div id="header"> <!--header -->
         <div class="header-logo">axoloth</div> <!--logo -->
          <div class="header-menu"> <!--menu-->
            <?php include("../include/menu.php"); ?>
          </div><!--menu-->
      </div> <!--end of header -->
    
      <div id="layout">
         <div class="events_container"> 
          <div id="new_event_wrap">
                <?php
         
                if (isset($_GET['cust_id'])) {
                    $cust_id=$_GET['cust_id'];
                } else {
                    $cust_id=0;
                }

                ?>

                  <form action="" method="post">

                    <h2>New event</h2>
                    
                    <table>
                      <input type="hidden" name="cust_id" value="<?php echo $cust_id ?>">

                      <tr>
                        <td>Title:</td><td><input type="text" name="event_title" autocomplete="off" placeholder="Enter event title..."></td>
                      </tr>

                      <tr>
                        <td>Customer:</td>
                        <td><select name="cust_id">
                            <?php
                            if (isset($_GET['cust_id'])) {
                                $cust_id=$_GET['cust_id'];
                                $sql="SELECT customer_name from tblcustomers where cust_id=$cust_id";
                                $result=mysqli_query($con, $sql) or die("MySQL ERROR: ".mysql_error());
                                while ($row = mysqli_fetch_array($result)) {
                                    $customer_name=$row['customer_name'];
                                    echo "<option value='$cust_id' selected=\"selected\">$customer_name</option>";
                                }
                            }
                            ?>
                          <option value="0">-- Choose the customer -- </option>
                            <?php
                              $sql="SELECT cust_id, customer_name from tblcustomers WHERE customer_status='active'";
                              $result=mysqli_query($con, $sql) or die("MySQL ERROR: ".mysql_error());
                            while ($row = mysqli_fetch_array($result)) {
                                $cust_id=$row['cust_id'];
                                $customer_name=$row['customer_name'];
                              
                                echo "<option value='$cust_id'>$customer_name</option>";
                            }
                            ?>
                          </select>
                        </td>
                      </tr> 
                      <tr>
                        <td>Event date (start):</td><td><input type="date"  name="start_event_date" id="start_date" value="<?php
                            
                        if (isset($_GET['day'])) {
                            $day=$_GET['day'];
                            echo $day;
                        } 
                        else 
                        
                        {
                            $day=date('Y-m-d');
                            echo $day;
                        }
                            ?>"></td>

                         </td>
                      </tr>
                      <tr>
                        <td>Event date (end):</td><td><input type="date" id="end_date" data-toggle="datepicker" name="end_event_date" value=

                           "<?php
                            if (isset($_GET['day'])) {
                                $day=$_GET['day'];
                                echo $day;
                            } else {
                                $day=date('Y-m-d');
                                echo $day;
                            }
                            ?>">
                          </td>
                          
                      <tr>
                        <td>Start time:</td>
                        <td><input type="text" value="<?php echo date('H').':00' ?>" name="event_start_time" class="time-input"></td>    
                      </tr>
                      <tr>
                        <td>End time:</td>
                        <td><input type="text" value="<?php

                          $act_hour=date('H:00');


                           $timestamp = strtotime($act_hour) + 60*60;

                          $time = date('H:i', $timestamp);


                         echo $time ?>" name="event_end_time" class="time-input"></td>    
                      </tr>
                      <tr>
                        <td>Whole day:</td><td><input type="checkbox" name="is_whole_day" value="1" style="width:20px"></td>
                      </tr>  
                      <tr>
                        <td>Event date:</td><td><input type="date" name="event_date" id="event_date" value="<?php
                            
                        if (isset($_GET['day'])) {
                            $day=$_GET['day'];
                        } else {
                            $day=date('Y-m-d');
                            echo $day;
                        }
                            ?>"></td>
                      </tr>
                      <tr>
                        <td>Event description:</td><td><textarea name="event_description"></textarea></td>
                      </tr>
                       <tr>
                        <td>Meeting:</td><td><input type="checkbox" name="is_meeting" value="1" style="width:20px"></td>
                      </tr>  
                      <tr>
                        <td>Event color:</td><td><input type="text" name="event_color" value="<?php   $colors=array("#ede7f6","#9575cd","#b3e5fc","#4fc3f7","#b2dfdb","#4db6ac","#c5e1a5","#aed581","#ffcdd2","#ef9a9a","#fcdc29","#7ba8ef","#b3cff3","#fb819b","#8bc34a"); //zoznam farieb          
                                  $random_color=array_rand($colors,1);
                                  echo $colors[$random_color]; 
                              ?>">
                        </td>
                      </tr>  
                      <tr>
                        <td colspan="2"><div class="add_event_action"><a class="flat-btn" href="index.php">Back</a><button name="add_event" class="flat-btn">New event</button></div></td>
                      </tr>
                    </table>
                  </form>  
                  </div><!-- wrapper -->
                  </div><!-- container -->
                  </div><!-- layout -->
                                                   
                </body>            
