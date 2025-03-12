<?php
  include ("../include/dbconnect.php");
  include ("../include/functions.php");
 
  echo "<table id='task_maintable'>";
            
    echo "<tr>";
      echo "<th style='text-align:left; text-indent: 5px'>Task text</th><th align='left'>Date created</th><th align='left'>Category</th><th>%</th><th>Priority</th><th align='left'>Status</th><th colspan='5' style='text-align:center'>Action</th>";
    echo "</tr>";
    
  global $con;
  
  $category=$_GET['category'];
  
  if($category<>""){
    $sql="SELECT * from tblcustomer_tasks WHERE task_category=$category";
  } else {
    $sql="SELECT * from tblcustomer_tasks WHERE task_status<>'completed'";
  }
   // $sql="SELECT * from tblcustomer_tasks WHERE task_priority='$priority' and task_status<>'completed'";
  $result = mysqli_query($con, $sql)  or die("MySQLi ERROR: ".mysqli_error());
    while ($row = mysqli_fetch_array($result)) {

      $id            = $row["id"];
      $task_text     = $row['task_text'];
      $date_created  = $row['date_created'];
      //$date_expiration=$row['date_expiration'];
      $task_status   = $row['task_status'];
      $task_priority      = $row['task_priority'];
      $task_category = $row['task_category'];
      $percent_done  = $row['percent_done'];
      
      
      $current_day = date("Y-m-d");
      
                                      
      if ($task_category <> 0) {
          $task_category_name = GetTaskCategoryName($task_category);
          //echo $task_category_name;
      } else {
          $task_category_name = "";
      }
    
      
      echo "<tr>";
      //echo "<td class=$priority></td>";
      
      $task_text = preg_replace("~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~", "<a href=\"\\0\">\\0</a>", $task_text);
      
      echo "<td style='width:60%;padding-top:5px;padding-bottom:5px'><b>" . nl2br($task_text) . "</b></td>";
      
      
      //echo "<td style='width:85px'>$date_created</td>";
      echo "<td><b>$date_created</b></td>";
      echo "<td>$task_category_name</td>";
      echo "<td><span><b>$percent_done%</b></span></td>";
      //echo "<td style='text-align:center'><div class='small_box $priority' title=$priority></div></td>";
      echo "<td style='text-align:center'>";
      echo "<select choose_task_priority onchange='ChangePriority($id);' id='task_priority-$id'>";
      echo "<option value='$task_priority' selected='selected'>$task_priority</option>";
      echo "<option value='low'>low</option>
        <option value='normal'>normal</option>
        <option value='high'>high</option>
      </select></td>";
      echo "<td style='width:80px;padding:5px'>";
      echo "<select name='choose_task_status' onchange='ChangeStatus($id);' id='task_status-$id'>";
      echo "<option value='$task_status' selected='selected'>$task_status</option>";   
      echo "<option value='new'>new</option>
        <option value='in progress'>in progress</option>
        <option value='pending'>pending</option>
        <option value='completed'>completed</option>
        <option value='cancelled'>cancelled</option>
      </select></td>";
      
      echo "<td>";
      echo "<form action='' method='post'><ul id='task_actions'>";
      
      $task_status_alive = array('new','in progress','pending');
      $task_status_not_alive = array('completed','void','cancelled' );

      if (in_array($task_status, $task_status_alive)) { // ak je status stale aktivny
        
          echo "<form method='post' action=''><input type='hidden' name=task_id value=$id><li><button type='submit' name='view_task[$id]' class='btn-small title='view task'><i class='fa fa-eye'></i></button></li><li><button type='submit' name='mark_as_complete[$id]' class='btn-small' title='mark as completed'><i class='fa fa-check'></i></button></li><li><button type='submit' name='edit_task[$id]' class='btn-small' title='Edit Task'><i class='fa fa-pencil-square-o'></i></button></li><li><button type='submit' name='add_task_note[$id]' title='add note to task' class='btn-small'><i class='fa fa-plus'></i></button></li><li><button type='submit' name='remove_task[$id]' title='Remove task' class='btn-small'> <i class='fa fa-times'></i></button></li></form>";
      } elseif (in_array($task_status, $task_status_not_alive)) {
          echo "<input type='hidden' name=task_id value=$id><li><button type='submit' name='view_task[$id]' class='btn-small title='view task'><i class='fa fa-eye'></i></button><li>";
      }

      echo "<ul></form></td>";
      echo "</tr>";
    }

    echo "</table>";
