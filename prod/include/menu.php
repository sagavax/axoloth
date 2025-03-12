		
			<?php include_once("config.php"); ?>
			<?php include_once("functions.php"); ?>
			<ul>
            	<li><a href="<?php echo APP_PATH; ?>dashboard.php" target="_self">Home</a></li>
				<li><a href="<?php echo APP_PATH; ?>passmgr/index.php" target="_self">Password manager</a></li>
				<li><a href="<?php echo APP_PATH; ?>notepad/index.php" target="_self">Notepad</a></li>
				<li><a href="<?php echo APP_PATH; ?>events/index.php" target="_self">Events</a></li>
				<li><a href="<?php echo APP_PATH; ?>tasks/index.php?display_as=list" target="_self">Tasks</a></li>
				<li><a href="<?php echo APP_PATH; ?>customers/index.php" target="_self">Customers</a></li>
										
				<!-- <li class="nav-item d-md-down-none">
        			<a class="nav-link" href="#"><i class="icon-bell"></i><span class="badge badge-pill badge-danger">5</span></a>
      			</li> -->

				<li><a href="<?php echo APP_PATH; ?>inbox.php" target="_self"><i class="fa fa-envelope" aria-hidden="true"></i>
				<li>
					<?php
				
					$unread=GetUnreadMessages();
					if($unread>0) {
						echo "<span class='circle_number'>".GetUnreadMessages()."</span></a></li>";
						} else { echo "</a></li>"; }
					 ?>
				</li>
				<!--<li><a href="<?php echo APP_PATH; ?>reminders/index.php" target="_self"><i class="fa fa-bell" aria-hidden="true"></i></li> -->
				<li>
                    <?php 
                        if (!isset($_SESSION["login"])){
							  $_SESSION['redirectURL'] = $_SERVER['REQUEST_URI']; 
							  //echo "<script>window.location.href='index.php'</script>";
							  //header('location:../index.php');} //zobrz ikonu prihlaseneho
						}else  { echo "<a href=".APP_PATH."logout.php><i class='fas fa-sign-out-alt'></i></a>"; } // inac zobrz ikonu na prihlasenie
                    ?>
               </li>
            </ul>    