<?php session_start()?>
<?php include "../include/dbconnect.php";?>


<!DOCTYPE html>
 <head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="../css/style_new.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href="../css/event.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
  	<link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
    <?php include "../include/icon.php"?>
  	<title>Axoloth - view event <?php echo $_GET['event_id']; ?></title>
</head>
  <body>

  <?php
include '../include/header.php';
?>
    <div id="layout">
        <div class="events_container">
          <div id="new_event_wrap">
              <?php
$event_id = $_GET['event_id'];

$sql = "SELECT a.event_id, a.cust_id, a.event_title,a.event_color,a.event_description, a.is_meeting,a.start_event_date,a.end_event_date,a.start_event_time,a.end_event_time,a.event_date, b.cust_id, b.customer_name FROM tblcustomer_events a, tblcustomers b WHERE a.event_id=$event_id and a.cust_id=b.cust_id;";
// echo $sql;
$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
$row = mysqli_fetch_array($result);{

	$d1 = $row['start_event_date'];
	$d2 = $row['end_event_date'];

	?>


                    <h3>View the event</h3>

                    <table>
                      <input type="hidden" name="event_id" value="<?php echo $event_id ?>">
                      <tr>
                        <td>Title:</td><td><input type="text" name="event_title" value="<?php echo htmlspecialchars($row['event_title']); ?>"></td>
                      </tr>
                       <tr>
                        <td>Customer:</td><td><input type="text" name="event_title" value="<?php echo htmlspecialchars($row['customer_name']); ?>"></td>
                      </tr>
                       <tr>
                        <td>Event date:</td><td><input type="text" name="event_date" value="<?php echo $row['event_date']; ?>"></td>
                      </tr>
                       <tr>
                        <td>Event date (start):</td><td><input type="text" name="start_event_date" value="<?php echo htmlspecialchars($row['start_event_date']); ?>"></td>
                      </tr>
                      <tr>
                        <td>Event date (end):</td><td><input type="text" name="end_event_date" value="<?php echo htmlspecialchars($row['end_event_date']); ?>"></td>
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
                        <td>Event description:</td><td><div class="textarea"><?php echo nl2br($row['event_description']); ?></div></td>
                      </tr>
                      <tr>
                          <?php

	$is_meeting = $row['is_meeting'];
	//if ($is_meeting==1) {$is_meeting='yes';} else {$is_meeting='no';}

	?>

                        <td>Meeting:</td><td><input type="checkbox" value="<?php echo $is_meeting; ?>"

                        <?php
if ($is_meeting == 1) {
		echo "checked='checked'";
	}
	?>
                        "></td>
                      </tr>
                      <tr>
                        <td>Event color:</td><td><div class="event_color" style="background:<?php echo trim($row['event_color']); ?>"></div></td>
                      </tr>
                      <tr>
                          <td colspan="2"><div class="add_event_action"><a class="flat-btn" href="index.php">Back</a></div></td>
                      </tr>

                    </table>



              <?php
}
?>
        </div>
      </div>
    </div>
    <script>
            var checkbox = document.querySelector('input[type=checkbox]');
            checkbox.disabled = true;
    </script>
  </body>
