<?php

	include ("include/dbconnect.php");
	
?>	
	<meta HTTP-EQUIV="REFRESH" content="3;url=.">

<link href="css/style.css" rel="stylesheet" type="text/css">

<?php	
	echo "<title>Delete</title>";
	//include ("include/header.inc.php");

    $sql="SELECT Server_name from tblserver_info where id='$id'";
    $result = mysql_query($sql);
    
    while ($row = mysql_fetch_array($result)) {  
    $server_name=$row['Server_name'];
    }
    
    echo "<br/><div class='msgbox'>";
    echo "<h1>Deleting the server <B>" .$server_name."</B> from database</h1>";

	      
      echo "<p><b>Deleting all information regarding the server...</p></b>";
      
         
      echo "<p><b>Deleting all issues ...</p></b>";
      $sql="DELETE FROM tblserver_issues WHERE Server_name='$server_name'";
      $result = mysql_query($sql);
      echo "Done...";
      
       echo "<p><b>Deleting instances...</p></b>";
       $sql="DELETE FROM tblinstances WHERE server_name='$server_name'";
       $result = mysql_query($sql);
       echo "Done...</div>";	
	
      echo "<p><b>Deleting all notes ...</p></b>";
      $sql="DELETE FROM tblnotepad WHERE colServer_Name='$server_name'";
      $result = mysql_query($sql);
      echo "Done...";
      
       echo "<p><b>Deleting from main table...</p></b>";
       $sql="DELETE FROM tblserver_info WHERE id='$id'";
       $result = mysql_query($sql);
       echo "Done...</div>"; 
      

      echo "<br /><b><div class='msgbox'>Server has been deleted from the database.</b><br /></div>";
	
?>
