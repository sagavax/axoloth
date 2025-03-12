<?php session_start() ;
$_SESSION['url'] = $_SERVER['REQUEST_URI'];
?>
<?php include("../include/dbconnect.php"); ?>
<?php include("../include/functions.php"); ?>
<?php include("../include/config.php"); ?>
   
<!DOCTYPE html>
<head>
   <meta name="viewport" content="width=device-width,initial-scale=1">
   <meta charset="utf-8" />
   <link href="../css/style_new.css?<?php echo time(); ?>" rel="stylesheet" type="text/css">
   <link href="../css/font-awesome.css" rel="stylesheet" type="text/css" />
   <meta name="viewport" content="width=device-width,initial-scale=1">
   <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
   <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/default.min.css">
   <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
   <script>hljs.initHighlightingOnLoad();</script>
   <link rel='shortcut icon' href='../eis.png'>
</head>
<body>
   <?php
      $note_id=$_GET['note_id']; // get the note's ID
    ?>	
   <div id="header">
      <!--header -->
      <div class="header-logo"><?php  echo APP_SHORT_NAME ?></div>
      <!--logo -->
      <div class="header-menu">
         <div class="header-menu">
         <!--menu-->
         <?php
            include("../include/menu.php");
       
         ?>
      </div>
      </div>
   </div>
   <!--end of header -->
   <div id="layout">
      <div id="view_note">
         <?php 
               $sql="SELECT a.id, a.cust_id, a.cat_id,a.title, a.note, a.note_source, b.cust_id, b.customer_name,a.note_type from tblcustomer_notes a,tblcustomers b where a.id=$note_id and a.cust_id=b.cust_id";
               //echo $sql;
               $result=mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error($con));
               
               while ($row = mysqli_fetch_array($result)){
               	$note_id=$row['id'];
                  $cat_id=$row['cat_id'];
               	//echo $cat_id;
                  $cat_name=GetCatName($cat_id);
                  $cust_id=$row['cust_id'];
                  $customer_name=$row['customer_name'];
                  $title=htmlspecialchars($row['title']);
                  echo "<title>e.i.s. - Note:".$title."</title>";
                  $note=htmlspecialchars($row['note']);
                  //$date_created=$row['date_created'];
                  $source=$row['note_source'];
                  $note_type=$row['note_type'];

                  
                  $source = preg_replace("~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~", "<a href=\"\\0\">\\0</a>", $source);
                                    
                  //echo $note;
               ?>	
              
               <input type="hidden" name="note_id" value="<?php echo $_GET['note_id'] ?>">
               <div class="edit_note_customer"><?php echo $customer_name?></div>
               <?php
               
               echo "<div class='note_wrap'>";
                           echo "<div class='edit_note_title'>".$title."</div>";

                if($note_type == "note") {
                  echo "<div class='note_text'>".htmlspecialchars_decode(nl2br($note))."</div>";
                }  

                elseif($note_type="ext_image") {
                   echo "<div class='note_image'><img src='".$row['note_source']."'></div>";
                }



                   
                           //echo "<div class='note_text' id='editor'>$note</div>";
                           
                           echo "<div style='clear:both'></div>";
                           echo "</div>";
                           ?>
               
               <div class="view_note_category_wrap">
                  <ul id="myTags">
                      <?php
                        $sql="SELECT a.cat_id,b.cat_name from tblcustomer_notes_tags a, tblcustomer_categories b where note_id=$note_id and a.cat_id=b.cat_id";
                       // echo $sql;
                        $result = mysqli_query($con, $sql)  or die("MySQL ERROR: ".mysqli_error($con));
                         	while ($row = mysqli_fetch_array($result)) {
                                $cat_name=$row['cat_name'];
                                echo "<li><span class='flat-btn-small'>$cat_name</span></li>";                            
                              }
                          ?>
                     </ul>
                  </div>
                  <div class='note_view_action'><a href='index.php' class="flat-btn-small">&lt;&lt; Back</a> <a href='note_edit.php?note_id=$note_id' class='flat-btn-small'>Edit</a> <a href='note_delete.php?note_id=$note_id' class='flat-btn-small'>Delete</a> <a href='note_clone.php?note_id=$note_id' class='flat-btn-small'>Clone</a></div>
                  
                  <?php
                  if($source<>"" )
                  if($source<>""&&$note_type<>"ext_image"&&$note_type<>"image") {
                     echo "<div class='edit_note_url'>$source</div>";
                  }
                  ?>
                  
                  <!-- <div class="edit_note_back_link"><a href='index.php' class="action_link">&lt;&lt; Back</a></div>-->
                  
               <?php
                  }
                  ?>	
           
         <!-- left content-->
      </div>
      <!-- main wrap-->  
   </div>
   <!-- id layout-->		
</body>

</html>