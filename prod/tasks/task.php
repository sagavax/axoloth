<?php
session_start();
include("../include/dbconnect.php");
include("../include/functions.php");
?>

<?php

if (isset($_POST['remove_task_note'])) {
    $task_id = $_POST['task_id'];
    $note_id = $_POST['task_note_id'];
    $sql = "delete from tblcustomer_tasks_comments where id=$note_id";
    $result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
    echo "<script>alert('Poznamka zmazana');
    var url='task.php?task_id=$task_id';
    window.location.href=url;
    </script>";
}

//header('location:task.php?id='.$_POST['id'].'',true,303);


?>

<!DOCTYPE html>

<head>

    <meta charset="utf-8" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    <link href="../css/style_new.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href="../css/tasks.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel='shortcut icon' href='../eis.ico'>

    <!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>-->
    <title>Enterprise informating system - Task manager</title>
</head>

<body>
    <div id="header">
        <!--header -->
        <div class="header-logo">e.i.s.</div>
        <!--logo -->
        <div class="header-menu">
            <!--menu-->
            <?php include("../include/menu.php");
            ?>
        </div>
        <!--menu-->
    </div>
    <!--end of header -->

    <?php
    if (!isset($_GET['task_id'])) {
        $task_id = $_POST['task_id'];
    } else {
        $task_id = $_GET['task_id'];
        echo "<script type='text/javascript'>localStorage.setItem('task_id',$task_id)</script>";
    }
    ?>



    <div id="layout">
        <div class=" fab-container">
            <div class="fab fab-icon-holder">
                <a href="index.php"><i class="fas fa-backward"></i></a>
            </div>
        </div><!-- fab container -->

        <div class="task_view_container">
            <div class="task_wrap">
               <div class="task_details"> 
                    <div class="task_preview_header">
                        <h3>Detail of task id <?php echo $task_id ?></h3>
                    </div>

                    <div class="task_preview_body">
                        <?php
                        $sql = "SELECT a.id, a.cust_id, a.task_text, a.task_category, a.task_priority,a.start_task_date, a.task_status, a.date_created,a.date_expiration, b.cust_id, b.customer_name FROM tblcustomer_tasks a, tblcustomers b WHERE a.id=$task_id and a.cust_id=b.cust_id;";
                        //echo $sql;
                        $result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
                        $row = mysqli_fetch_array($result);

                        ?>

                        <?php
                        $task_category = $row['task_category'];
                        if ($task_category > 0) {
                            $task_category_name = GetTaskCategoryName($task_category);
                            // echo "Kategoria: ".GetTaskCategoryName($task_category);
                        } else {
                            $task_category_name = "";
                        }

                        ?>

                        <table>

                            <tr>
                                <th>Task text:</th>
                                <td><textarea name="task_text"><?php echo $row['task_text'] ?></textarea></td>
                            </tr>
                            <tr>
                                <th>Customer:</th>
                                <td><input type="text" name="task_customer" value="<?php echo $row['customer_name'] ?>"></td>
                            </tr>
                            <tr>
                                <th>Start task date:</th>
                                <td><input type="text" name="task_started" value="<?php echo $row['start_task_date'] ?>"></td>
                            </tr>
                            <tr>
                                <th>Date created:</th>
                                <td><input type="text" name="date_created" value="<?php echo $row['date_created'] ?>"></td>
                            </tr>
                            <tr>
                                <th>Task category:</th>
                                <td><input type="text" value="<?php echo $task_category_name ?>"></td>
                            </tr>
                            <tr>
                                <th>Task priority:</th>
                                <td><input name="task_priority" type="text" value="<?php echo  $task_priority = $row['task_priority']; ?>">
                                <td>
                            </tr>
                            <tr>
                                <th>Task status:</th>
                                <td><input type="text" name="task_status" value="<?php echo $task_status = $row['task_status']; ?>"></td>
                            </tr>
                            <tr>
                                <th> Task will expire:</th>
                                <td><input type="text" name="date_expiration" value="<?php echo $row['date_expiration'] ?>"></td>
                            </tr>


                        </table>
                    </div><!-- BODY -->

                    <div class="task_preview_footer"></div>
                </div><!-- task detail -->


                        
                <div class="task_notes_wrap">
                    <div class="task_notes_header">
                        <h3>Task Notes</h3> <button class='btn-small add_task_note'><i class='fa fa-plus'></i></button>
                    </div><!-- task notes header -->
                    
                    <div class="task_notes_body"><!-- task notes body -->

                        <?php
                        /*if (!isset($task_id)) {
                        $task_id = $_POST['task_id'];
                    }*/
                        $sql = "SELECT * from tblcustomer_tasks_comments where task_id=$task_id";
                        global $con;
                        $result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
                        if(mysqli_num_rows($result)==0){
                            echo "<p>No notes for this task</p>";
                        } else {
                        echo "<ul>";    
                        while ($row = mysqli_fetch_array($result)) {
                            $task_note_id = $row['id'];
                            $task_id = $row['task_id'];
                            $comment = $row['comment'];
                            $date_created = $row['date_created'];

                            echo "<li class='task_note' task_note_id=$task_note_id><div class='task_note_text'>" . nl2br($comment) . "</div><div class='task_note_action'><button class='btn-small remove_task_note'><i class='fa fa-times'></i></button></div></li>";
                            //echo nl2br($comment);
                        }                             
                        echo "</ul>";
                        }
                        
                        ?>
                    <div style="clear:both"></div>    
                </div><!-- task notes body -->     
                <div class="task_notes_footer"></div>   
            </div><!-- task_notes wrapper -->

            <div class="task_timeline_wrap">
                <h3>Task timeline timeline (experimental):</h3>
                <ul>
                    <?php
                    $sql = "SELECT * from tblcustomer_tasks_timeline where task_id=$task_id ORDER BY time_id ";
                    $result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());   
                    while ($row = mysqli_fetch_array($result)) {
                        $id = $row['task_id'];
                        $task_action = $row['task_action'];
                        $action_time = $row['action_time'];

                        echo "<li><div><div class='task_time'>$action_time</div><div class='task_action'>$task_action</div></div></li>";
                        //echo "<li><div class='pass_time'>$action_time</div><div class='pass_action'>$pass_action</div></li>";

                    }
                    ?>
                </ul>
            </div><!-- task timeline -->

            </div><!-- task_wrap -->
              <div style="clear:both;"></div><!-- clear -->          
          </div><!-- task_container -->
        <div style="clear:both;"></div><!-- clear -->    
    </div>
    <!-- end of layout -->
    <script type="text/javascript" src="../js/task.js"></script>                
  </body>