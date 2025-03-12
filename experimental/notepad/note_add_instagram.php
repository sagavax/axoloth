<?php 

 include("../include/dbconnect.php");
 include("../include/functions.php");
if(isset($_POST['add_tweet'])){
 //global $con;
 //var_dump($_POST);
 $note_title="";
 $note_text=htmlentities($_POST['tweet_url']);
 //$note_text=htmlentities(mysqli_real_escape_string($con, $_POST['note_text']));
 $cust_id = 0; // if cust_id = moje vlastne poznamky
 $note_type="instagram";
 $date_created = date('Y-m-d H:m:s');
 $note_source="";
 
 //$sql="INSERT INTO tblcustomer_notes (cust_id, cat_id,title, note,note_source,date_created, note_type) VALUES ($cust_id,$cat_id,'$note_title','$note_text','$note_source','$date_created','$note_type')"; 
 $sql="INSERT INTO tblcustomer_notes (cust_id, title, note,note_source,date_created, note_type) VALUES ($cust_id,'$note_title','$note_text','$note_source','$date_created','$note_type')"; 
 //echo $sql;
 $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));

 $new_note_id=GetLatestNote();
  

if(isset($_POST['categories'])){
 
   $tags_ids=$_POST['categories']; 
   foreach($tags_ids as $tag_id){
      $sql="INSERT INTO tblcustomer_notes_tags (note_id,cat_id) VALUES($new_note_id,$tag_id)";
      //echo $sql;
      $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));
      }
}
 

 //zapis do wallu
  $curr_date=date('Y-m-d H:i:s');
    
  //$link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
  $link1=mysqli_connect('localhost','root',null,'brick_wall',null,null);
  $diary_text="EIS: Bola vytvorena nova poznamka s id $new_note_id a nazvom <strong>$note_title</strong>";
  
  $sql="INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
  $result = mysqli_query($link1, $sql) or die("MySQLi ERROR: ".mysqli_error($link1));
      mysqli_close($link1);

  //zapis to logu
 $curr_action="create_note";
 $curr_app="notepad";
 $text_logu="new note id $new_note_id has been created";
 
 $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
 $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));

 echo "<script>alert('new note has been created');
 window.location.href='index.php';   
 </script>";
}
?>:

 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image from clipboard</title>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
     <link href="../css/style_new.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css' rel='stylesheet' type='text/css'>
    <link rel='shortcut icon' href='../eis.ico'>
</head>
<body>
    <div id="header">
        <!--header -->
        <div class="header-logo">e.i.s.</div>
        <!--logo -->
         <div class="header-menu">
        <!--menu-->
            <?php include '../include/menu.php' ?>
        </div>
    </div>
    <div id="layout">
            <div id="add_new_tweet_wrap">
               <div id="add_new_tweet"> 
                    <form action="" method="post">
                        <input type="text" name="tweet_url" placeholder="insert a tweet ...">
                        <div class="add_tweet_act">    <button type="submit" name="add_tweet" class="flat-btn"><i class="fa fa-plus"></i></button></div>
                    </form>
                </div>
            </div> 
                
            </div>
    </div><!-- layout -->