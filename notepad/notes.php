<?php
include("../include/dbconnect.php");
include("../include/functions.php");

  global $con;

//$cat_id = $_GET['category_id'];
//var_dump($_GET);
if(isset($_GET['tab_id'])){

  $cat_id = $_GET['tab_id'];
    if ($cat_id == "All") {
        // $sql="SELECT a.id, a.cust_id, a.title, a.note, a.date_created,a.note_source, b.cust_id, b.customer_name,a.cat_id, a.note_type from tblcustomer_notes a, tblcustomers b WHERE a.cust_id=b.cust_id and a.cat_id=".$_GET['cat_id']." ORDER BY a.id DESC";
        // $sql="SELECT a.* from tblcustomer_notes a INNER JOIN tblcustomer_notes_tags b ON a.cat_id=b.cat_id WHERE a.cat_id=".$_GET['cat_id']." ORDER BY a.id DESC";
        $sql = "SELECT a.id, a.cust_id, a.title, a.note, a.date_created,a.note_source, b.cust_id, b.customer_name,a.cat_id,a.note_type from tblcustomer_notes a, tblcustomers b WHERE a.cust_id=b.cust_id ORDER BY a.id DESC";
        } else if($cat_id=="Notes") {
        $sql = "SELECT a.id, a.cust_id, a.title, a.note, a.date_created,a.note_source, b.cust_id, b.customer_name,a.cat_id,a.note_type from tblcustomer_notes a, tblcustomers b WHERE a.cust_id=b.cust_id  and a.note_type='note' ORDER BY a.id DESC";
    } else if ($cat_id =="Links"){
        $sql = "SELECT a.id, a.cust_id, a.title, a.note, a.date_created,a.note_source, b.cust_id, b.customer_name,a.cat_id,a.note_type from tblcustomer_notes a, tblcustomers b WHERE a.cust_id=b.cust_id  and a.note_type='link' ORDER BY a.id DESC";
    } else if ($cat_id =="Videos"){
        $sql = "SELECT a.id, a.cust_id, a.title, a.note, a.date_created,a.note_source, b.cust_id, b.customer_name,a.cat_id,a.note_type from tblcustomer_notes a, tblcustomers b WHERE a.cust_id=b.cust_id  and a.note_type='video' ORDER BY a.id DESC";
    } else if ($cat_id =="Images"){
        $sql = "SELECT a.id, a.cust_id, a.title, a.note, a.date_created,a.note_source, b.cust_id, b.customer_name,a.cat_id,a.note_type from tblcustomer_notes a, tblcustomers b WHERE a.cust_id=b.cust_id  and a.note_type='ext_image' OR a.note_type='image' ORDER BY a.id DESC";
    }
} elseif (isset($_GET['category_id'])){
    $cat_id = $_GET['category_id'];
    if($cat_id=="all"){
        $sql = "SELECT a.id, a.cust_id, a.title, a.note, a.date_created,a.note_source, b.cust_id, b.customer_name,a.cat_id,a.note_type from tblcustomer_notes a, tblcustomers b WHERE a.cust_id=b.cust_id ORDER BY a.id DESC";
    } elseif($cat_id=="Unsorted"){
        $sql = "SELECT a.id, a.cust_id, a.title, a.note, a.date_created,a.note_source, b.cust_id, b.customer_name,a.cat_id,a.note_type from tblcustomer_notes a, tblcustomers b WHERE a.cat_id =0 and a.cust_id=b.cust_id ORDER BY a.id DESC";
    }else {
        $sql = "SELECT a.id, a.cust_id, a.title, a.note, a.date_created,a.note_source, b.cust_id, b.customer_name,a.cat_id,a.note_type from tblcustomer_notes a, tblcustomers b WHERE cat_id = $cat_id and a.cust_id=b.cust_id ORDER BY a.id DESC";
    }
    
}
//$sql = "SELECT a.id, a.cust_id, a.title, a.note, a.date_created,a.note_source, b.cust_id, b.customer_name,a.cat_id,a.note_type from tblcustomer_notes a, tblcustomers b WHERE a.cust_id=b.cust_id ORDER BY a.id DESC";

//echo $sql;

$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
if(mysqli_num_rows($result)==0){
    echo "<div class='error_message'>No records found! <i class='fa fa-times'></i></div>";
}
while ($row = mysqli_fetch_array($result)) {
        $note_id = $row['id'];
        $cust_id = $row['cust_id'];

        $customer_name = GetCustName($cust_id);
        $url = $row['note_source'];
        $title = $row['title'];
        $note = $row['note'];
        $date_created = $row['date_created'];
        $cat_id = $row['cat_id'];
        $note_type = $row['note_type'];

        $string = "";


        //$langs = array('PHP', 'Javascript', 'T-SQL', 'PL-SQL', 'Powershell'); //zoznam programovacich jazykov

        echo "<div class='note'>";


        $cat_ids = GetTagList($note_id);

        //zoznam categorii
        $cat_list = "<div class='tag_list_wrap'><ul class='tag_list'>";
        foreach ($cat_ids as $cat_id) {
            //print_r($cat_ids);
            $cat_name = GetCatName($cat_id);
            //$cat_list.="<li><a href='index.php?view=all_notes&cat_id=$cat_id' class='btn-small'>$cat_name</a></li>";
            $cat_list .= "<li><a href='index.php?cat_id=$cat_id' class='btn-small'>$cat_name</a></li>";
        }
        $cat_list .= "</ul></div>";

        //echo $cat_list;

        //echo "<div class='note_title_wrap'><div class='note_title'>$title</div><div class='note_date'>$date_created</div></div>";
        echo "<div class='note_title_wrap'><div class='note_title'>$title</div></div>";


        if ($string == "") {
            $string = $url;
        }

        if ($note_type == 'note') {
            echo "<div class='note_text'>";
            //echo "<b>".$note_type."</b>"; 
            $string = html_entity_decode(strip_tags(htmlspecialchars($note)));

        if (strlen($string) > 500) {

            // truncate string
            $stringCut = substr($string, 0, 500);
            $stringCut = preg_replace("~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~", "<a href=\"\\0\">\\0</a>", $stringCut);
            $string = nl2br(substr($stringCut, 0, strrpos($stringCut, ' '))) . "... <a href='note.php?note_id=" . $note_id . "''>Read More</a>";
        }

            echo nl2br($string);
            echo "</div>";
            
            
            }   
            
            if($note_type=="link"){
                echo "<div class='note_text'>";   
                 $url=preg_replace("~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~", "<a href=\"\\0\">\\0</a>", $url);
                 echo nl2br($url);
                 echo "</div>";   
            }
         


        if ($note_type == 'video') {
            
            echo "<div class='note_video'>";

            //echo $note_type;

            $content = preg_replace("~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~", "<a target='_blank' href=\"\\0\">\\0</a>", $url);

            /*require_once 'oembed.php';
            $autoembed = new AutoEmbed();
            $content = $autoembed->parse($url);*/
            echo $content;

            /*echo $url;

/*       <iframe id="ytplayer" type="text/html" width="<?php echo $width ?>" height="<?php echo $height ?>"
src="https://www.youtube.com/embed/<?php echo $id ?>?rel=0&showinfo=0&color=white&iv_load_policy=3"
frameborder="0" allowfullscreen></iframe> */


            echo "</div>";
        }
        if ($note_type == 'image') { // typ obrazok
            echo "<div class='note_image'>";
            echo "<img src='images/$url' loading='lazy'>";
            echo "</div>";
        }
        if ($note_type == 'gallery') {
            //echo $note_type;
            echo "<div class='note_image'>";
            $images = array();
            $get_images = "SELECT * from tblcustomer_notes_gallery where gallery_name='$title'";

            $result_get_images = mysqli_query($con, $get_images) or die("MySQL ERROR: " . mysqli_error($con));
            while ($row = mysqli_fetch_array($result_get_images)) {
                $image_id = $row['image_id'];
                $images[] = $image_id;
            }
            foreach ($images as $image) {
                //echo $image;
                $get_image_source = "SELECT note_source from tblcustomer_notes where id=$image";
                //echo $get_image_source;
                $result_image_source = mysqli_query($con, $get_image_source) or die("MySQL ERROR: " . mysqli_error($con));
                while ($row_image = mysqli_fetch_array($result_image_source)) {
                    $image_source = $row_image['note_source'];
                    echo "<img src='$image_source'>";
                } //get image
            }

            //print_r($images);
            echo "</div>";
        }


        if ($note_type == 'ext_image') { // typ obrazok
            //get gallery
           
            echo "<div class='note_image'>";

           //$mime=mime_content_type($url); 
           //echo $url;
            //echo "<img src=data_uri('$url,'image/png') loading='lazy'>";
            echo "<img src='$url' loading='lazy'>";
            echo "</div>";
        }

        if ($note_type == 'code') {
            echo "<div class='note_text'>";
               highlight_string($note);
           // } else {
                //echo  "<pre>$note</pre>";   
            //}
                echo "</div>";
        }    
        echo "<div class='note_footer'><div class='note_action'>";
        if ($note_type == "note") {
            echo "<a href='note.php?note_id=$note_id' title='view note' class='btn-small'><i class='fa fa-eye'></i></a><a href='note_edit.php?note_id=$note_id' class='btn-small' title='edit note'><i class='fa fa-edit'></i></a>";
        } elseif ($note_type == "link") {
            echo "<a href='link_edit.php?note_id=$note_id' class='btn-small' title='edit link'><i class='fa fa-edit'></i></a>";
        } elseif ($note_type == "ext_image") {
            echo "<a href='picture_external_edit.php?note_id=$note_id' class='btn-small' title='edit picture'><i class='fa fa-edit'></i></a> <a href='picture_external_add.php?note_id=$note_id' class='btn-small' title='add picture to gallery'><i class='fa fa-plus'></i></a>";
        } elseif ($note_type == "gallery") {
            //echo $note_type;
            echo "<a href='gallery_edit.php?note_id=$note_id' class='btn-small' title='edit gallery'><i class='fa fa-images'></i></a> <a href='picture_external_add.php?note_id=$note_id' class='btn-small' title='add picture to gallery'><i class='fa fa-plus'></i></a>";
        } elseif ($note_type == "video") {
            //echo $note_type;
            echo "<a href='video_edit.php?note_id=$note_id' class='btn-small' title='edit video'><i class='fa fa-edit'></i></a>";
        }
        echo "<a href='note_delete.php?note_id=$note_id' title='delete note' class='btn-small'><i class='fa fa-times'></i></a><a href='note_clone.php?note_id=$note_id' title='clone note' class='btn-small'><i class='fa fa-clone'></i></a><a href='note_archive.php?note_id=$note_id' title='archive note' class='btn-small'><i class='fa fa-archive'></i></a>";
        
        if($note_type=='note'){echo "<a href='note_attachment.php?note_id=$note_id' title='attach a file' class='btn-small'><i class='fas fa-paperclip'></i></a>";
        }   
     
            echo "</div><div class='note_cat_wrap'><ul><li><button title='Add new category' class='btn-small-hollow' data-id=$note_id onclick='ShowModalAddCat($note_id)';><i class='fa fa-plus'></i></button></li><li>" . $cat_list . "<div style='clear:both'></div></li><li><span class='note_customer'><a href='index.php?view=all_notes&cust_id=$cust_id' class='btn-small-green'>$customer_name</a></span></li></ul></div></div>";
        //echo "<div class='note_taglist'>".GetTagList($note_id)."</div>";
        echo "<div style='clear:both'></div>";
        echo "</div>";
    } //end while
?>