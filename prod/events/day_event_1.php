<?php include("../include/dbconnect.php"); ?>


<?php
  //get list of events for particular day

  if (isset($_GET['view_day'])) {
    $event_day=$_GET['view_day'];
   // $action=$_GET['action'];
    $i=0;
    $sql="SELECT * from tblcustomer_events where event_date='$event_day'";
    //echo $sql;
          
    $result=mysqli_query($con, $sql) or die("MySQLi ERROR: ".mysqli_error($con));
    $nr_of_events=mysqli_num_rows($result);
          
    if ($nr_of_events==0) {
        echo "<h2>No events for desired day</h2>";
    }
     $i=0;
    while ($row = mysqli_fetch_array($result)) {
        $event_id=$row['event_id'];
        $event_title=$row['event_title'];
        $i++;
              
        echo "<div class='event' id=$event_id>";
      // echo "<div class='event_header'>";
          echo "<div class='order_nr'>$i.</div><div class='event_title'><a href='event.php?id=$event_id&action=view'>$event_title</a></div>";
        echo "</div>"; //event
    }     
  }