<?php 

include "../include/dbconnect.php"; 
include "../include/functions.php";

global $con;

$search_string = $_GET['text'];

$search_task = "SELECT a.*, b.customer_name from tblcustomer_tasks a,tblcustomers b where a.task_text LIKE '%".$search_string."%' AND a.cust_id = b.cust_id";
//echo $search_task;
$result = mysqli_query($con, $search_task) or die("MySQLi ERROR: " . mysqli_error($con));

while ($row = mysqli_fetch_array($result)) {
    //SELECT `id`, `task_text`, `date_created`, `date_expiration`, `task_relation`

    $id = $row["id"];
    $task_text = $row['task_text'];
    $date_created = $row['date_created'];
    //$date_expiration=$row['date_expiration'];
    $task_relation = $row['customer_name'];
    $task_status = $row['task_status'];
    $task_priority = $row['task_priority'];
    $task_category = $row['task_category'];
    $percent_done = $row['percent_done'];

    $current_day = date("Y-m-d");

    if ($task_category != 0) {
        $task_category_name = GetTaskCategoryName($task_category);
    } else {
        $task_category_name = "";
    }

    echo "<li><div class='task'>";

    $task_text = preg_replace("~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~", "<a href=\"\\0\">\\0</a>", $task_text);
    $notes_count = GetTaskNotesCount($id); //pocet poznamok pre task

    echo "<div class='task_text'>$task_text</div>";
    //echo "<div class='task_note_count'><a href='view_notes.php?task_id=$id'><span class='nr_of_items'>$notes_count</span></a></div>";
    echo "<div class='task_date'><span>$date_created</span></div>";
    //echo "<div class='task_category'>$task_category_name</div>";
    //echo "<td><span><b>$percent_done%</b></span></td>";
    echo "<div class='task_progress'><span><progress max='100' value=$percent_done></progress></span></div>";
    echo "<div class='task_priority'>";
    echo "<select choose_task_priority onchange='ChangePriority($id);' id='task_priority-$id'>";
    echo "<option value='$task_priority' selected='selected'>$task_priority</option>";
    echo "<option value='low'>low</option>
        <option value='normal'>normal</option>
        <option value='high'>high</option>
        </select></div>";
    echo "<div class='task_status'>";
    echo "<select name='choose_task_status' onchange='ChangeStatus($id);' id='task_status-$id'>";
    echo "<option value='$task_status' selected='selected'>$task_status</option>";
    echo "<option value='new'>new</option>
        <option value='in progress'>in progress</option>
        <option value='pending'>pending</option>
       </select></div>";

    echo "<div class='task_actions'>";
    echo "<form action='' method='post'><ul>";

    $task_status_alive = array('new', 'in progress', 'pending');
    $task_status_not_alive = array('complete', 'void', 'cancelled');

    if (in_array($task_status, $task_status_alive)) { // ak je status stale aktivny

        echo "<input type='hidden' name=task_id value=$id><li><button type='submit' name='view_task[$id]' class='btn-small title='view task'><i class='fa fa-eye'></i></button></li><li><button type='submit' name='mark_as_complete[$id]' class='btn-small' title='mark as complete'><i class='fa fa-check'></i></button></li><li><button type='submit' name='edit_task[$id]' class='btn-small' title='Edit Task'><i class='fa fa-pencil-square-o'></i></button></li><li><button type='submit' name='add_task_note[$id]' title='add note to task' class='btn-small'><i class='fa fa-plus'></i></button></li><li><button type='submit' name='remove_task[$id]' title='Remove task' class='btn-small'> <i class='fa fa-times'></i></button></li>";
    } elseif (in_array($task_status, $task_status_not_alive)) {
        echo "<input type='hidden' name=task_id value=$id><li><button type='submit' name='view_task[$id]' class='btn-small title='view task'><i class='fa fa-eye'></i></button></li>";
    }

    echo "</ul></form></div>";
    echo "</div></li>";
}