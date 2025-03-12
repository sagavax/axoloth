<?php 
      session_start();
      include("../include/dbconnect.php"); 
      include("../include/functions.php");
?>

<?php

  if (isset($_POST['add_note'])) {
    $task_id=$_POST['id'];
    $note_text=mysqli_real_escape_string($_POST['note_text']);
    $date_created=date('Y-m-d H:i:s');
    $sql="INSERT INTO tblcustomer_tasks_comments (task_id, comment, date_created) VALUES ($task_id,'$note_text', '$date_created')";
    global $con;
    $result=mysqli_query($con, $sql) or die("MySQL ERROR: ".i());

    header('location:task.php?id='.$_POST['id'].'',true,303);

  }

?>

<!DOCTYPE html>
 <head>

    <meta charset="utf-8" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    <link href="../css/style_new.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
     <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel='shortcut icon' href='../eis.ico'>

        <!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>-->
        <title>Enterprise informating system - Task manager</title>
</head>
  <body>
      <div id="header"> <!--header -->
                        <div class="header-logo">e.i.s.</div> <!--logo -->
                          <div class="header-menu"> <!--menu-->
                           <?php include("../include/menu.php");
                           ?>
                </div><!--menu-->
      </div> <!--end of header -->



      <div id="layout">


          <div id="task_preview_wrap">

              <?php


                if(!isset($_GET['task_id'])) {
                    $task_id=$_POST['task_id'];
                } else {$task_id=$_GET['task_id'];}

                $sql= "SELECT a.id, a.cust_id, a.task_text, a.task_category, a.task_priority,a.start_task_date, a.task_status, a.date_created,a.date_expiration, b.cust_id, b.customer_name FROM tblcustomer_tasks a, tblcustomers b WHERE a.id=$task_id and a.cust_id=b.cust_id;";
                //echo $sql;
                $result=mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error());
                $row = mysqli_fetch_array($result) ;

              ?>
                <h2>Detail of task id <?php echo $task_id ?></h2>
                <?php   
                 $task_category=$row['task_category'];
                  if($task_category>0){
                        $task_category_name=GetTaskCategoryName($task_category);
                       // echo "Kategoria: ".GetTaskCategoryName($task_category);
                       }
                      else {$task_category_name="";
                     }
                       
                  ?>
                   
                <table id="task_details">

                    <tr>
                       <th>Task text:</th><td><textarea name="task_text"><?php echo $row['task_text']?></textarea></td>
                    </tr>
                    <tr>
                      <th>Customer:</th><td><input type="text" name="task_customer" value="<?php echo $row['customer_name'] ?>"></td>
                    </tr>
                    <tr>
                      <th>Start task date:</th><td><input type="text" name="task_started" value="<?php echo $row['start_task_date'] ?>"></td>
                    </tr>
                    <tr>
                      <th>Date created:</th><td><input type="text" name="date_created" value="<?php echo $row['date_created'] ?>"></td>
                    </tr>
                    <tr>
                      <th>Task category:</th><td><input type="text" value="<?php echo $task_category_name ?>"></td>
                    </tr>
                    <tr>
                      <th>Task priority:</th><td><input name="task_priority" type="text" value="<?php echo  $task_priority=$row['task_priority']; ?>" ><td>
                    </tr>
                    <tr>
                      <th>Task status:</th><td><input type="text" name="task_status" value="<?php echo $task_status=$row['task_status']; ?>"></td>
                    </tr>
                    <tr>
                      <th> Task will expire:</th><td><input type="text" name="date_expiration" value="<?php echo $row['date_expiration'] ?>"></td>
                    </tr>
                    <tr>
                      <td colspan=2><a href="index.php?display_as=list" class="action_link">&lt;&lt; Back</a></td>
                    </tr>

                </table>

                <section class="add_button_wrap"><form action="task.php" method="post"><input type="hidden" name="id" value="<?php echo $task_id; ?>"><button name="add_task_note" class="btn-small" title="Add note to this task">+</button></form></section>

                    <?php
                      if (isset($_POST['add_task_note'])) {
                    ?>

                      <form action="task.php?task_id=<?php echo $_POST['id']; ?>" method="post">
                        <input type="hidden" name="id" value="<?php echo $_POST['id']; ?>">
                        <h2> Add the task note: </h2>
                        <table class="add_task_comment">
                          <tr>
                            <td><textarea name="note_text"></textarea></td>
                          </tr>
                          <tr>
                            <td><button type="submit" name="add_note" class="flat-btn">Add note</button></td>
                          </tr>

                        </table>
                      </form>


                    <?php
                      }
                    ?>

                   <table id="task_comments">
                    <?php
                      if (!isset($task_id)) {
                        $task_id=$_POST['task_id'];
                      }
                      $sql="SELECT * from tblcustomer_tasks_comments where task_id=$task_id";
                      global $con;
                       $result=mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error());
                       while ($row = mysqli_fetch_array($result)) {
                          $task_id=$row['task_id'];
                          $comment=$row['comment'];
                          $date_created=$row['date_created'];

                          echo "<div class='note_wrap'>";
                               echo "<div class='note_header'>
                                  $date_created
                               </div>";
                               echo "<div class='note_text'>
                                  ".nl2br($comment)."
                               </div>";
                               //echo nl2br("<div class='note_text'>$comment</div>");
                              //echo "<div class='note_action'><a href='note_edit.php?note_id=$note_id' class='action_link'>Edit</a> | <a href='note_delete.php?note_id=$note_id' class='action_link'>Delete</a></div>";
                              echo "<div style='clear:both'></div>";
                        echo "</div>";

                      /*    echo "<tr>";
                            echo "<td style='color:#333;font-size:12px;font-weight:bold'>$comment</td></td><td style='width:140px;font-size:12px;text-alight:right'>$date_created</td>";
                          echo "</tr>";*/
                       }


                    ?>
                   </table>
              
            <div class="task_timeline_wrap">
             <h3>Task timeline timeline (experimental):</h3>
             <ul>
                <?php
                  $sql="SELECT * from tblcustomer_tasks_timeline where task_id=$task_id ORDER BY time_id ";
                  $result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());   
                  while ($row = mysqli_fetch_array($result)) {
                    $id=$row['task_id'];
                    $task_action=$row['task_action'];
                    $action_time=$row['action_time'];

                    echo "<li><div><div class='task_time'>$action_time</div><div class='task_action'>$task_action</div></div></li>";
                    //echo "<li><div class='pass_time'>$action_time</div><div class='pass_action'>$pass_action</div></li>";

                  }
                ?>
         </ul>   
      </div>

           </div> <!-- edit task wrap -->

           <div>
         </div>
      </div><!-- end of layout -- >

  </body>
