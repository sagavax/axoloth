<?php session_start();
$_SESSION['url'] = $_SERVER['REQUEST_URI'];
include "include/dbconnect.php";
include "include/functions.php";

?>

<!DOCTYPE html>

<head>
	<html lang="sk">
	<meta charset="utf-8" />
	<name="viewport" content="width=device-width; height=device-height; initial-scale=1.0">
		<link rel="stylesheet" href="css/style_new.css?<?php echo time() ?>" type="text/css">
		<link rel="stylesheet" href="css/dashboard.css?<?php echo time() ?>" type="text/css">
		<!--<link href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">-->
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" rel="stylesheet">
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
		<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
		<title>axoloth - dashboard</title>
		<link rel='shortcut icon' href='eis.ico'>
		<script async src="js/index.js"></script>
</head>

<body>


	<?php // Change or ommit page-extension in URL
if (!isset($page_ext)) {
	$page_ext = ".php";
}

$page_ext_qry = "$page_ext.?";

?>
	<!-- header -->

	<?php include "include/header.php"?>


	<!-- Body -->

	<div id="layout">
		<div id="panels">
			<div class="dash_panel" onclick="show_module('events')">
				<div class="dash_body">
                    <div class="left-panel-side"><span><i class="far fa-calendar-alt"></i></span></div>
                    <div class="right-panel-side"><span><?php echo GetCountAllEvents(); ?></span></div>
                </div>
                <div class="dash_footer">
                    <span>Newest Events: <?php echo GetCountNewestEvents(); ?></span>
	            </div>
			</div>

            <div class="dash_panel" onclick="show_module('tasks')">
        	    <div class="dash_body">
                    <div class="left-panel-side"><span><i class="fas fa-tasks"></i></span></div>
                    <div class="right-panel-side"><span><?php echo GetCountofActiveTasks(); ?></span></div>
	    		</div>
                <div class="dash_footer">
                    <span>Newest tasks: <?php echo GetCountNewestTasks(); ?></span>
                    </div>
                </div>

            <div class="dash_panel" onclick="show_module('notes')">
            	<div class="dash_body">
                    <div class="left-panel-side"><span><i class="far fa-edit"></i></span></div>
                    <div class="right-panel-side"><span><?php echo GetCountofAllnotes(); ?></span></div>
    			</div>
                <div class="dash_footer">
                    <span>Newest notes: <?php echo GetCountNewestNotes(); ?></span>
                </div>
            </div>

            <div class="dash_panel" onclick="show_module('passwords')">
                <div class="dash_body">
				   <div class="left-panel-side"><span><i class="fas fa-key"></i></span></div>
				   <div class="right-panel-side"><span><?php echo GetCountofAllPasswords(); ?></span></div>
			    </div>
                <div class="dash_footer">
                    <span>Newest passwords: <?php echo GetCountNewestPasswords(); ?></span>
	            </div>
            </div>

			<div class="dash_panel" onclick="show_module('customers')">
        	    <div class="dash_body">
				    <div class="left-panel-side"><span><i class="fas fa-users"></i></span></div>
				    <div class="right-panel-side"><span><?php echo GetCountofAllCustomers(); ?></span></div>
			    </div>
                <div class="dash_footer">
                    <span>Newest customers: 0</span>
	            </div>
		    </div>

            <div class="dash_panel" onclick="show_module('trading')">
        	    <div class="dash_body">
				    <div class="left-panel-side"><span><i class="fas fa-coins"></i></span></div>
				    <div class="right-panel-side"><span><?php echo GetCountofAllTradingPlatforms(); ?></span></div>
			    </div>
                <div class="dash_footer">
                    <span>Newest platforms: <?php echo GetCountNewestPlatforms() ?></span>
	            </div>
		    </div>

		<div id="applications">
		</div>

	</div><!-- layout -->

    <script>

			function show_module(module){
				if(module=='tasks'){
					window.location.href="tasks/index.php"
				}else if(module=='passwords'){
					window.location.href="passmgr/index.php"
				} else if(module=='notes'){
					window.location.href="notepad/index.php"
				} else if(module=='events'){
					window.location.href="events/index.php"
				}else if(module=='customers'){
					window.location.href="customers/index.php"
				}else if(module=='trading'){
                    window.location.href="trading/index.php"
                }
			}
        </script>
</body>

</html>