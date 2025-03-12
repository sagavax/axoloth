

<?php include("../include/dbconnect.php"); ?>


  <?php
if (isset($_POST['submit']))
{
          
        $customer_name=$_POST['customer_name'];
        //$title=$_POST['title_name'];
        $note=$_POST['note']; 	  

        
	$sql= "INSERT INTO tblcustomer_notes (customer_name,note, created_date) VALUES ('$customer_name','$note',now())";
	$result=mysql_query($sql) or die("MySQL ERROR: ".mysql_error());
	$customer=$_POST['customer_name'];
    echo "<br/><div class='msgbox'>New message has been added successfully<br/><i>return to <a href='index$page_ext'>home page</a></i></div>";
}		


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sk" lang="sk">
<head>
<meta charset="utf-8" />
    <link href="../css/style.css" rel="stylesheet" type="text/css">
	<link href="../css/style_new.css" rel="stylesheet" type="text/css">
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    <title>Add new note</title>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    
</head>
<body>



<div id="container">
  <div id="center" class="column">
 

<div id="note_detail" style="background-color: #D7E2F2; border: 0px #000 solid; width: 450px;padding:5px;  -moz-border-radius: 5px;  -webkit-border-radius: 5px; border-radius: 5px; padding: 10px; -moz-box-shadow: 3px 3px 3px 3px #ccc;
  -webkit-box-shadow: 3px 3px 3px 3px #ccc; box-shadow: 0 2px 5px rgba(2,2,2,.62); margin: 40px auto 30px auto; height: 500px">

  <h2>Add new note</h2>

  

  <form accept-charset="utf-8" method="post" action="cust_note_add.php">
  <input type="hidden" name="customer_name"/>
  <table id="new_password" border="0" cellspacing="0" bgcolor="#D7E2F2">
  
  <tr>
      <td style="width: 100px; color: #3B5998 ; font: Helvetica; font-weight: bold; text-align: right; font-size:12px">Customer:</td>
      <td>
         <input type="text" name="customer_name" size="35" style="width: 100%" value="<?php echo $customer ?>" />
      </td>
  </tr>
  <tr>
      <td style="width:100px; color: #3B5998 ; font: Helvetica; font-weight: bold; text-align: right; font-size:12px">Title:</td>
      <td>
         <input type="text" name="title_name" size="35" style="width: 100%" />
      </td>
  </tr>
  <tr>
      <td style="width:100px; color: #3B5998 ; font: Helvetica; font-weight: bold; text-align: right; font-size:12px">Note:
      </td>
      <td>
        <textarea id="note" name="note" style="width: 100%" rows="20"></textarea>
      </td>
  </tr>
  <tr>
    
    <td colspan="2"><input type="submit"  class="btn" name="submit" value="Add new" /></td>  
  
  </tr>  
</table>

<p>
  
</p>
</form>

</div>


</div>
</div>
</body>
</html>