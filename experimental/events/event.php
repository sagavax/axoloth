<?php session_start() ?> 
<?php include("../include/dbconnect.php"); ?>


<!DOCTYPE html>
 <head>
    
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="../css/style_new.css" rel="stylesheet" type="text/css" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
  	<link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
  	<link href="../css/datepickr.min.css" rel="stylesheet" type="text/css">
  	

    
        <!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>-->
        <title>EIS - Task manager</title>
</head>
  <body>
      <div id="header"> <!--header -->
         <div class="header-logo">e.i.s.</div> <!--logo -->
          <div class="header-menu"> <!--menu-->
            <?php include("../include/menu.php"); ?>
          </div><!--menu-->
      </div> <!--end of header -->
      


      <div id="layout">
          

          <div id="edit_event_wrap">
              <?php 
                     $event_id=$_GET['event_id'];

                      $sql= "SELECT a.event_id, a.cust_id, a.event_title,a.event_color,a.event_description, a.is_meeting,a.start_event_date,a.end_event_date,a.start_event_time,a.end_event_time,a.event_date, b.cust_id, b.customer_name FROM tblcustomer_events a, tblcustomers b WHERE a.event_id=$event_id and a.cust_id=b.cust_id;";                
                       //echo "$sql";
                       $result=mysqli_query($con, $sql) or die("MySQL ERROR: ".mysql_error());
                      $row = mysqli_fetch_array($result); {

                      $d1=$row['start_event_date'];  
                      $d2=$row['end_event_date'];

                      ?>


                    <h2>View the event id <?php echo $event_id; ?></h2>
                    
                    <table id="edit_event">
                      <input type="hidden" name="event_id" value="<?php echo $event_id ?>">
                      <tr>
                        <td>Title:</td><td><input type="text" name="event_title" value="<?php echo htmlspecialchars($row['event_title']); ?>"></td>
                      </tr>
                       <tr>
                        <td>Customer:</td><td><input type="text" name="event_title" value="<?php echo htmlspecialchars($row['customer_name']); ?>"></td>
                      </tr> 
                       <tr>
                        <td>Event date:</td><td><input type="text" name="event_date" value="<?php echo htmlspecialchars($row['event_date']); ?>"></td>
                      </tr> 
                       <tr>
                        <td>Event date (start):</td><td><input type="text" name="start_event_date" value="<?php echo htmlspecialchars($row['start_event_date']); ?>"></td>
                      </tr>
                      <tr>
                        <td>Event date (end):</td><td><input type="text" name="end_event_date" value="<?php echo htmlspecialchars($row['end_event_date']); ?>"></td>
                      </tr>

                      <tr>
                        <td>Duration (days):</td><td><input type="text" name="end_event_date" value="<?php echo dateDiff ($d2,$d1); ?>"></td>
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

                            $is_meeting=$row['is_meeting'];
                          if ($is_meeting==1) {$is_meeting='yes';} else {$is_meeting='no';}

                        ?>
                          
                        <td>Meeting:</td><td><input type="text" value="<?php echo $is_meeting; ?>" class="yesno-input"></td>
                      </tr>  
                      <tr>
                        <td>Event color:</td><td><div style="height:30px; width:30px; border:1px #999 solid;background:<?php echo trim($row['event_color']); ?>"></td>
                      </tr>  
                      <tr>  

                    </table>
                    
                    <div>
                      <a href="index.php" class="action_link"> &laquo; Back</a>
                    </div> 
              <?php
                      }
              ?>

  </body> 
