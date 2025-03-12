<?php session_start();
$_SESSION['url'] = $_SERVER['REQUEST_URI'];
?>

<?php include("../include/dbconnect.php"); ?>
<?php include("../include/functions.php"); ?>

<?php
   if (isset($_POST['add_video']))
    {
        global $con;
        //var_dump($_POST);
        $note_source=trim(mysqli_real_escape_string($con, $_POST['video_source']));
        
        //zisti duplicitu
        $sql="SELECT * from tblcustomer_notes where note_source='$note_source'";
        $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));
        $nr_of_videos=mysqli_num_rows($result);

        if($nr_of_videos>0){
          echo "<script>alert('There is already such video!');
          window.loacation.href='video_add.php';
          </script>";
        } else {

        $note_title=trim(mysqli_real_escape_string($con, $_POST['video_title']));
        
        $note_text="";
        //$cust_id = (int)$_POST['customer']; // if cust_id = moje vlastne poznamky
        $cust_id = 0;
        //$tags_ids=$_POST['categories']; 
        $note_type="video"; 
        $date_created = date('Y-m-d H:m:s');
        $sql="INSERT INTO tblcustomer_notes (cust_id, title, note,note_source,date_created, note_type) VALUES ($cust_id,'$note_title','$note_text','$note_source','$date_created','$note_type')";
              $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));
      
        $curr_date=date('Y-m-d H:i:s');
        $curr_action="create_video";
        $curr_app="notepad";
        $new_note_id=GetLatestNote();
        
             
        if(isset($_POST['categories'])){
    
         $tags_ids=$_POST['categories']; 
         foreach($tags_ids as $tag_id){
            $sql="INSERT INTO tblcustomer_notes_tags (note_id,cat_id) VALUES($new_note_id,$tag_id)";
            //echo $sql;
            $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));
            }
   }

               
            $link1 = mysqli_connect(null, "brick_wall", "h3jSXv3gLf", "brick_wall", null, "/tmp/mariadb55.sock");
            //$link1=mysqli_connect('localhost','root',null,'brick_wall',null,null);
            $diary_text="EIS: Bolo pridane nove video s nazvom <strong>$note_title</strong>";
            $sql="INSERT INTO diary (diary_text, date_added,location,isMobile,is_read) VALUES ('$diary_text','$curr_date','',0,0)";
            $result = mysqli_query($link1, $sql) or die("MySQLi ERROR: ".mysqli_error($link1));
            mysqli_close($link1);
        


        $text_logu="new video $new_note_id has been added";
        
        $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
        //echo $sql;
        
        $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($link1));
        echo "<script>alert('new video has been added');
          window.location='index.php';   
          </script>";
            //header('Location: index.php'); // presmeruje spat aby sa zbranilo vkladaniu duplicity
      }
    }

    if(isset($_POST['add_cat'])){ //pridat novy kategoriu
   
      AddNewCategory($_POST['cat_name']);
      
      header('location:video_add.php');

   }
   ?>
   
<!DOCTYPE html>
<head>
   <meta charset="utf-8" />
   <title>E.I.S - Add new video</title>
   <link href="../css/style_new.css?<?php echo time(); ?>" rel="stylesheet" type="text/css">
   <link href="../css/font-awesome.css" rel="stylesheet" type="text/css" />
   <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
   <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
   <link rel='shortcut icon' href='../eis.png'>

    <script type="text/javascript">
      function add_new_cat() {

         document.getElementById('new_cat_form').style.display = "block";
      }
   </script>
</head>

<body>
   <div id="header">
      <!--header -->
      <div class="header-logo">e.i.s.</div>
      <!--logo -->
      <div class="header-menu">
         <!--menu-->
        <?php include('../include/menu.php') ?>
      </div>
   </div>
   <!--end of header -->
   <div id="layout">
        <div class="edit_note_wrap">
            <form action="" method="post" id="new_note_form">
               <table id="new_note">
                  <tr>
                       <td><input type="text" name="video_title" placeholder="video title"></td>
                  </tr>
                  <tr>
                     <td><input type="text" name="video_source" placeholder="video url"></td>
                  </tr>
                  <tr>
                  <tr>
                     <td style="text-align:right"><button name="add_video" type="submit" class="flat-btn">Add video</button></td>
                  </tr>   
                  <tr>
                    <td colspan="2"><a href='index.php' class="action_link">&lt;&lt; Back to home</a></td>
                  </tr>  
               </table>
            </form>
         </div>
      
            <!-- main wrap-->
   </div>
   <!-- id layout-->
</body>
</html>
