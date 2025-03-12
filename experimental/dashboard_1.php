<?php session_start();
	$_SESSION['url'] = $_SERVER['REQUEST_URI'];
    include("include/dbconnect.php");
	include("include/functions.php");


	error_reporting(E_ALL);
ini_set('display_errors', '1');

/*
if(isset($_POST['search']))
{
    //echo $search = $_POST['search'];
}

	if(isset($_POST['add_new_task'])) {//quick add new task

		$cust_id=$_POST['customer']; //hodnota ziskana zo selectu, ak 0 tak tak je to task moj vlastny
		echo "cust_id=$cust_id";
		$task_text=addslashes($_POST['task_text']);
		$task_status="new";
		$task_priority="normal";
		$date_created=date('Y:m:d H:m:s');
		$date_expiration=date('Y:m:d H:m:s', strtotime($date_created.'+5 days'));
		//print_r ($_POST);

      	$sql="INSERT INTO tblcustomer_tasks (cust_id, task_text, task_priority, date_created, date_expiration, task_status) VALUES ($cust_id, '$task_text','$task_priority','$date_created','$date_expiration','$task_status')";
      	$result=mysqli_query($con,$sql) or die("MySQL ERROR: ".mysqli_error($db));
      	//echo "$sql";
      	header('location:serveris.php',true,303);
	}


	if(isset($_POST['add_new_note'])) { //quick add new note
		
		$cust_id=$_POST['customer_id']; //hodnota ziskana zo selectu, ak 0 tak tak je to task moj vlastny
		$note_text=addslashes($_POST['note_text']);
		$date_created=date('Y:m:d H:m:s');

		$sql="INSERT INTO tblcustomer_notes (cust_id, note, date_created) VALUES ($cust_id,'$note_text','$date_created')";
		//echo "$sql";
		$result=mysqli_query($con,$sql) or die("MySQL ERROR: ".mysqli_error($db));
		header('location:serveris.php',true,303);

	}

	if(isset($_POST['mark_as_complete'])){ //quick mark task as complete
		$task_id=$_POST['task_id'];
		$sql="UPDATE tblcustomer_tasks SET task_status='complete' where TaskID=$task_id";
		//echo "$sql";
		$result=mysqli_query($con,$sql) or die("MySQL ERROR: ".mysqli_error($db));
		header('location:serveris.php',true,303);

	}

if(isset($_POST['quick_edit_password'])) {
	$pass_id=$_POST['id'];
	echo "Password=".$_POST['id'];
	//header('location:passmgr/password_edit.php?id='.$_POST['id'].'');
}

if (isset($_POST['add_new_password'])) {
	header('location:passmgr/password_add.php',true,303);
}

if(isset($_POST['quick_view_projects'])) {
	$project_id=$_POST['project_id'];
	header('location:projects/project_details.php?project_id='.$_POST['project_id'].'',true,303);	
}


if(isset($_POST['new_event'])) {
	//$cust_id=$_POST['cust_id'];
	header('location:events/event.php?&action=new',true,303);	
} 

*/

?>


<!DOCTYPE html>

<head>
	<html lang="sk">
	<meta charset="utf-8" />
	<name="viewport" content="width=device-width; height=device-height; initial-scale=1.0">
	<link rel="stylesheet" href="css/style_new.css?<?php echo time()?>" type="text/css">
	<!--<link href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">-->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
	<link href="css/toastr.min.css" rel="stylesheet" type='text/css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="js/toastr.min.js"></script>

	<title>e.i.s. - dashboard</title>
	<link rel='shortcut icon' href='eis.ico'>


	 <script>
    document.onkeydown=function(evt){
        var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
        if(keyCode == 13)
        {
            //your function call here
            document.search.submit();
        }
    }
</script>	
	
	
	<!-- <script type="text/javascript">
		var auto_refresh = setInterval(
		function ()
		{
		$('#events').load('event_list.php'+'?r='+Math.random());
		}, 60000); // refresh every 10000 milliseconds
		
	</script>
	-->	
	<script type="text/javascript">
			function updateClock ( )
	    {
	    var currentTime = new Date ( );
	    var currentHours = currentTime.getHours ( );
	    var currentMinutes = currentTime.getMinutes ( );
	    var currentSeconds = currentTime.getSeconds ( );
	 
	    // Pad the minutes and seconds with leading zeros, if required
	    currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
	    currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;
	 
	    // Choose either "AM" or "PM" as appropriate
	    var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";
	 
	    // Convert the hours component to 12-hour format if needed
	    currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;
	 
	    // Convert an hours component of "0" to "12"
	    currentHours = ( currentHours == 0 ) ? 12 : currentHours;
	 
	    // Compose the string for display
	    var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;
	     
	     
	    $("#txt").html(currentTimeString);
	         
	 }
	 
		$(document).ready(function()
		{
		   setInterval('updateClock()', 1000);
		});

	</script>
	
	
</head>
<body>


	<?php // Change or ommit page-extension in URL
	 if(!isset($page_ext))
		 $page_ext = ".php";
		 $page_ext_qry = "$page_ext.?";

	?>
    <!-- header -->
	
	<?php include ("include/header.php") ?>
    
    <!--<div class="weather" style="height:60px; box-sizing: border-box; padding:5px;width:100%; background: #fff; float:left; color:#555; font-family:'Open Sans'"> -->
    		<?php
    			$url="http://apidev.accuweather.com/currentconditions/v1/297345.json?language=en&apikey=hoArfRosT1215";
    			$url_value=file_get_contents($url);
    			$data = json_decode($url_value);
    			$weather_icon=$data[0]->WeatherIcon;
    			if(intval($weather_icon)<10){
    				$weather_icon="0".$weather_icon;
    			}
    			//echo $data[0]->WeatherText;
    			//$pic_url="http://vortex.accuweather.com/adc2010/images/icons-numbered/06-xl.png";
    			//$pic_url="http://vortex.accuweather.com/adc2010/images/icons-numbered/";
    			$pic_url="weather_icons";
    		 $weather ="<img src='".$pic_url."/".$weather_icon."-s.png'>"."<span style='font-size:20px; font-weight:bold;'>".$data[0]->Temperature->Metric->Value." ".$data[0]->Temperature->Metric->Unit."</span>";
    			               

    		?>
    <!--</div> -->    	
	<!-- Body -->

	<div id="layout">
	 	<div id="new_session"> 
            <span>WELCOME TO THE INFORMATING SYSTEM <?php echo date('j.n.Y')."      ". $weather ?></span>
        </div> <!-- new session information --> 	
		
		<div id="panels">
				<div class="dash_panel"><div class="left-panel-side"><span><i class="far fa-calendar-alt"></i></span></div><div class="right-panel-side"><span><?php echo GetCountAllEvents();?></span></div></div>
				<div class="dash_panel"><div class="left-panel-side"><span><i class="fas fa-tasks"></i></span></div><div class="right-panel-side"><span><?php echo GetCountofActiveTasks (); ?></span></div></div>
				<div class="dash_panel"><div class="left-panel-side"><span><i class="far fa-edit"></i></span></div><div class="right-panel-side"><span><?php echo GetCountofAllnotes(); ?></span></div></div>
				<div class="dash_panel"><div class="left-panel-side"><span><i class="fas fa-key"></i></span></div><div class="right-panel-side"><span><?php echo GetCountofAllPasswords(); ?></span></div></div>
				<div class="dash_panel"><div class="left-panel-side"><span><i class="fas fa-users"></i></span></div><div class="right-panel-side"><span><?php echo GetCountofAllCustomers(); ?></span></div></div>
			
		</div>	

	 <div id="applications">
	 </div> 	
	 
   </div><!-- layout -->
	

</body>
</html>

