<?php session_start(); ?>
<?php include("../include/dbconnect.php"); ?>

 
            <?php  
          
                    $event_id=$_GET['event_id'];
                
                     $sql="DELETE from tblcustomer_events where event_id=$event_id";
                      
                     $result=mysqli_query($con, $sql) or die("MySQLi ERROR: ".mysqli_error($con));    

                     $curr_date=date('Y-m-d H:i:s');
                     $curr_action="delete_event";
                     $curr_app="event_manager";
                     $text_logu="The event with id $event_id has been deleted !";
                     $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read,undo_action) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0,'$undo_action')";
             
                     $result = mysqli_query($con, $sql)  or die("MySQLi ERROR: ".mysqli_error($con));   
                   
                   //$link1 = mysqli_connect("localhost","root","","brick_wall");  
                   $link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
                        $diary_text="EIS: event s id $event_id bol vymazany>";
                        $sql="INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
                        $result = mysqli_query($link1, $sql) or die("MySQLi ERROR: ".mysqli_error($link1));
                      mysqli_close($link1);
   
                     echo "<script>alert('Event s id".$event_id." bol vymazany');
                        window.location.href='index.php';
                     </script>";
        

                     