<?php include("include/dbconnect.php"); ?>
<!DOCTYPE html>
    <head>
        <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sk" lang="sk">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta charset="utf-8" />
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <meta name="author" content="" />
        <link href="../css/style_new.css" rel="stylesheet" type="text/css" />
        <link href="../css/style.css" rel="stylesheet" type="text/css" />
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
        <link rel='shortcut icon' href='../eis.ico'>
        <title>ServerIS - servers</title>
            
    </head>

<div id="header"></div>


<div id="container">
  <div id="center" class="column">

  <table id="ViewInstanceDetails" border="0" border-spacing="0">
  
	<?php    
       
        echo "<tr>"; 
        echo "<th>General note</th>";
        echo "<th>Instance name</th>";
        echo "<th>Version</th>";
        echo "</tr>";
     
       
        $sql="SELECT * FROM tblinstances WHERE server_name='$server_name'";
        //echo "$sql";   
        
        
     $result = mysql_query($sql);
     $alternate = "2";
     while ($row = mysql_fetch_array($result)) {   
        
        
        $id=$row['id'];
        $general_note=$row['instance_info'];
        $sql_instance=$row['instance_name'];
        $sql_version=$row['instance_version'];
        
       if ($sql_version=="") {
           $sql_version="-";
        } elseif ($general_note=="") {
           $general_note="-";
        }  
        
        if ($alternate == "1") { 
			$color = "even"; 
			$alternate = "2"; 
		} 
		else { 
			$color = "odd"; 
			$alternate = "1";
		}         

        echo "<tr class='$color'>";
        echo "<td>$general_note</td>";
        echo "<td>$sql_instance</td>";
        echo "<td>$sql_version</td>";
        echo "</tr>"; 
        
     }       
        
?>
</table>
<a href="javascript:window.close();">Close</a>

</div>
</div> 
