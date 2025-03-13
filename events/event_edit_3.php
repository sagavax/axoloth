<?php session_start();?>
<?php include "../include/dbconnect.php";?>
<?php include "../include/functions.php";?>

<?php if (isset($_POST['save_changes'])) {

	$cust_id = $_POST['customer'];
	$event_id = $_POST['event_id'];
	$event_title = mysqli_real_escape_string($con, $_POST['event_title']);
	$event_description = mysqli_real_escape_string($con, $_POST['event_description']);
	$start_event_time = mysqli_real_escape_string($con, $_POST['start_event_time']);
	$end_event_time = mysqli_real_escape_string($con, $_POST['end_event_time']);
	$event_date = mysqli_real_escape_string($con, $_POST['event_date']);
	$start_event_date = mysqli_real_escape_string($con, $_POST['start_event_date']);
	$end_event_date = mysqli_real_escape_string($con, $_POST['end_event_date']);
	$event_color = mysqli_real_escape_string($con, $_POST['event_color']);

	$sql = "UPDATE tblcustomer_events SET cust_id=$cust_id, event_title='$event_title',event_description='$event_description', start_event_date='$start_event_date',end_event_date='$end_event_date',start_event_time='$start_event_time',end_event_time='$end_event_time',event_date='$event_date' WHERE event_id=$event_id";
	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysql_error());

	$curr_date = date('Y-m-d H:i:s');
	$curr_action = "modify_event";
	$curr_app = "event_manager";
	$new_event_id = GetLatestEvent();
	$undo_action = "DELETE from tblevents WHERE id=$new_event_id";
	$text_logu = "new event with id $new_event_id has been created !";
	$sql = "INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read,undo_action) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0,'$undo_action')";

	$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error());

	$link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
	$diary_text = "EIS: Bol upraveny event s id $event_id";
	$sql = "INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
	$result = mysqli_query($link1, $sql) or die("MySQLi ERROR: " . mysqli_error($link1));
	mysqli_close($link1);

	$link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
	$diary_text = "EIS: event s id $event_id bol upraveny>";
	$sql = "INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
	$result = mysqli_query($link1, $sql) or die("MySQLi ERROR: " . mysqli_error($link1));
	mysqli_close($link1);

	echo "<script>alert('Event s id" . $event_id . " bol upraveny');
                      window.location.href='index.php';
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
  	<link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
    <?php include "../include/icon.php"?>
    <title>Axoloth - edit event <?php echo $_GET['event_id'] ?></title>

</head>
  <body>
      <div id="header"> <!--header -->
         <div class="header-logo">e.i.s.</div> <!--logo -->
          <div class="header-menu"> <!--menu-->
            <?php include "../include/menu.php";?>
          </div><!--menu-->
      </div> <!--end of header -->

      <div id="layout">
      <div class="events_container">
          <div id="new_event_wrap">
            <?php

$event_id = $_GET['event_id'];

$sql = "SELECT a.event_id, a.cust_id, a.event_title,a.event_color, a.event_description, a.is_meeting,a.start_event_date,a.end_event_date,a.start_event_time,a.end_event_time,a.event_date, b.cust_id, b.customer_name FROM tblcustomer_events a, tblcustomers b WHERE a.event_id=$event_id and a.cust_id=b.cust_id;";
//echo "$sql";
$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysql_error());
$row = mysqli_fetch_array($result);{

	$d1 = $row['start_event_date'];
	$d2 = $row['end_event_date'];

	?>


                    <h3>Modify the event id <?php echo $event_id; ?></h3>
                    <div id="edit_event_wrap">
                        <form action="" method="post">
                        <input type="hidden" name="event_id" value="<?php echo $event_id ?>">
                          <table id="edit_event">
                            
                            <tr>
                              <td>Title:</td><td><input type="text" name="event_title" value="<?php echo htmlspecialchars($row['event_title']); ?>"></td>
                            </tr>
                            <tr>
                              <td>Customer:</td><td><input type="text" name="event_title" value="<?php echo htmlspecialchars($row['customer_name']); ?>"></td>
                            </tr>
                            <tr>
                              <td>Event date:</td><td><input type="date name="event_date" value="<?php echo htmlspecialchars($row['event_date']); ?>"></td>
                            </tr>
                            <tr>
                              <td>Event date (start):</td><td><input type="date" name="start_event_date" value="<?php echo htmlspecialchars($row['start_event_date']); ?>"></td>
                            </tr>
                            <tr>
                              <td>Event date (end):</td><td><input type="date" name="end_event_date" value="<?php echo htmlspecialchars($row['end_event_date']); ?>"></td>
                            </tr>

                            <tr>
                              <td>Duration (days):</td><td><input type="text" name="end_event_date" value="<?php echo dateDiff($d2, $d1); ?>"></td>
                            </tr>
                            <tr>
                              <td>Event time (start):</td><td><input type="text" name="start_event_time" value="<?php echo htmlspecialchars($row['start_event_time']); ?>" class='time-input'></td>
                            </tr>
                            <tr>
                              <td>Event time (end):</td><td><input type="text" name="end_event_time" value="<?php echo htmlspecialchars($row['end_event_time']); ?>" class='time-input'></td>
                            </tr>

                            <tr>
                              <td>Event description:</td><td><textarea><?php echo nl2br($row['event_description']); ?></textarea></td>
                            </tr>
                            <tr>
                                <?php

	$is_meeting = $row['is_meeting'];
	if ($is_meeting == 1) {$is_meeting = 'yes';} else { $is_meeting = 'no';}

	?>

                              <td>Meeting:</td><td><input type="text" value="<?php echo $is_meeting; ?>" class="yesno-input"></td>
                            </tr>
                            <tr>
                              <td>Event color:</td><td><div class="event_color_wrapper"><div role="button" name="event_color" style="background-color:<?php echo $row['event_color']; ?>" class="event_color"></div><div id="color_palette"></div></div></td>
                            </tr>
                            <tr>
                              <td colspan="2"><div class="add_event_action"><a class="flat-btn" href="index.php?event_date=<?php echo $row['event_date']; ?>">Back</a><button name="save_changes" class="flat-btn">Save</button></div></td>
                            </tr>
                          </table>
                          </form>
                      </div>


            <?php
}
?>
         </div><!-- wrapper -->
         </div><!-- container -->
      </div><!-- layout -->



       <script>
          document.getElementById("color_palette").style.display=="none";
         window.onload = function (){

            var parent = document.getElementById("color_palette");
            var div = document.createElement('div');
            let colors=["#ede7f6","#9575cd","#b3e5fc","#4fc3f7","#b2dfdb","#4db6ac","#c5e1a5","#aed581","#ffcdd2","#ef9a9a","#fcdc29","#7ba8ef","#b3cff3","#fb819b","#8bc34a"];

            for ( i=0; i<colors.length;i++ ){
              parent.innerHTML+="<div class='palette_item'></div>";
              var palette_items = document.querySelectorAll(".palette_item");
              for (y=0;y<palette_items.length;y++){
                color = colors[y];
                palette_items[y].style.backgroundColor=color;
               } // set all divs background color from the list
            }  //get number of all colors
         }//onload

       </script>
      </body>