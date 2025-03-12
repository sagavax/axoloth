<?php session_start();

?>

<?php include("../include/dbconnect.php"); ?>
<?php include("../include/functions.php"); ?>

<?php
  
   if(isset($_POST['add_cat'])){ //pridat novy kategoriu
    
    AddNewCategory($_POST['cat_name']);
    
     //header('location:note_add.php');

   }


   ?>
   
<!DOCTYPE html>
<head>
   <meta charset="utf-8" />
   <title>E.I.S - Add new note</title>
   <link rel="stylesheet" href="../css/style_new.css?<?php echo time()?>" type="text/css">
   <link href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
   <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
   <link href="../css/toastr.min.css" rel="stylesheet" type='text/css'>
   <link rel='shortcut icon' href='../eis.png'>
   
</head>
<body>
<div id="header"> <!--header -->
<div class="header-logo">E.I.S.</div> <!--logo -->
 <div class="header-menu">
     <!--menu-->
    <?php include('../include/menu.php') ?>
  </div>
</div> <!-- end of header -->
   <!--end of header -->
   <div id="layout">
      <div class="edit_note_wrap">
         <form action="upload_picture.php" method="post" class="new_note_form"  enctype="multipart/form-data"> 
         <div class="edit_note_title">
            <input type="text" placeholder="Title..." name="note_title" autocomplete="off">
         </div>
         <div class="edit_note_title">
               <input type="file"  accept="image/gif, image/jpeg, image/png" name="picture" onchange="loadFile(event)" id="file" style="display: none;">
         <div class="edit_note_title"><label for="file" style="cursor: pointer;" class="flat-btn">Upload Image</label></div>
            <img id="output" width="100%" />	
            <script>
            var loadFile = function(event) {
            var image = document.getElementById('output');
            image.src = URL.createObjectURL(event.target.files[0]);
            };
            </script>
           <div class="edit_note_action">
               <button name="add_image" type="submit" class="flat-btn" id="add_image">Add picture</button>
           </div>
         </form>
      </div>
   </div>   

   <a href='index.php' class="action_link">&lt;&lt; Back to home</a>
   
   </div>
   </div>
 
</body>
</html>