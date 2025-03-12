<?php session_start();
$_SESSION['url'] = $_SERVER['REQUEST_URI'];


include("../include/dbconnect.php"); 
include("../include/functions.php"); 





if(isset($_POST['add_new_pass'])){
  header('location:password_add.php');
}

if(isset($_POST['favorite_add'])){
    //print_r($_POST['favorite_add']);
     $id=key($_POST['favorite_add']);
    $sql="update tblpasswords set is_favorite=1 where PassID=$id";
    //echo $sql;
    $result = mysqli_query($con,$sql);

    $curr_date=date('Y-m-d H:i:s');
    $curr_action="update_password";
    $curr_app="passmgr";
    $text_logu="Password with id $id has been added into favorites";
    
    $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
    //$sql;
    
    $result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());
    
    echo "<script>alert('The password has been added into favorites');
    location.href='index.php';
    </script>";
}

if(isset($_POST['favorite_remove'])){
    $id=key($_POST['favorite_remove']);
    $sql="update tblpasswords set is_favorite=0 where PassID=$id";
    $result = mysqli_query($con,$sql);

    $curr_date=date('Y-m-d H:i:s');
    $curr_action="update_password";
    $curr_app="passmgr";
    
    $text_logu="Password with id $id has been removed from favorites";
    
    $sql="INSERT INTO tblcustomer_notes_history (action, app, text_logu, date_added, is_read) VALUES ('$curr_action','$curr_app','$text_logu','$curr_date',0)";
        
    $result = mysqli_query($con, $sql); // or die("MySQL ERROR: ".mysqli_error());
    
    echo "<script>alert('The password has been removed from favorites');
    location.href='index.php';
    </script>";
    
}

?>


<!DOCTYPE html>
    <head>
        <meta charset="utf-8" />
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <meta name="author" content="misura" />
        <link href="../css/style_new.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
        <script defer src="https://use.fontawesome.com/releases/v5.0.9/js/all.js" integrity="sha384-8iPTk2s/jMVj81dnzb/iFR2sdA7u06vHJyyLlAd4snFpCl/SnyUjRrbdJsw1pGIl" crossorigin="anonymous"></script>
        <link href="../css/tabs.css" rel="stylesheet" type="text/css" />
        <link href="../css/toastr.min.css" rel="stylesheet" type="text/css" />
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <!-- <script src="Chart.js"></script> -->
        
        <script src="../js/toastr.min.js"></script>
        <meta name="viewport" content="width=device-width,initial-scale=1">
       
        <link rel='shortcut icon' href='../eis.ico'>
        <title>E.I.S. - Passwords</title>
         
    </head>
<body>



<div id="header"> <!--header -->
    <div class="header-logo">E.I.S.</div> <!--logo -->
     <div class="header-menu">
         <!--menu-->
        <?php include('../include/menu.php') ?>
      </div>
 </div> <!-- end of header -->



<div id="layout">
  <div id="passwords_wrap">
    	<div id="left_panel">
           <div id="letter_list"><!--letter list -->
         <?php
            echo "<ul>";
                        foreach (range('A', 'Z') as $char) {
                          echo "<li><a href='index.php?alphabet=$char'>$char</a></li>";

                        }
                          echo "<li><a href='index.php?char=all'>All</a></li>";
                          echo "<li><form action='' method='post'><button type='submit' name='add_new_pass' class='flat-btn' id='add_new_pass'><i class='fa fa-plus'></i> Add new password</button></form></li>";
                        echo "</ul>";
                    ?>
        </div><!--letter list --> 
          <div class="search_box">
             
              <form method="get" action="index.php" accept-char="utf-8">
              <div class="embed-button">   
                    <input type="text" name="search_string" value="" placeholder="System name or  user name" autocomplete="off">
                    <button class="flat-btn"><i class="fas fa-search"></i></button>
                    </div>    
            </form>
            
            <!--<span><a href="password_add.php" class='flat-btn'>+ Add new</a></span> --> 
        </div>
    

          <table id="pass_maintable">

            <tr>
                <th class="pass_system_name">System name:</th><th class="pass_user_name">User name:</th><th class="pass_group">Group</th><th class="pass_favorites">Favorite</th><th>Actions:</th>
             </tr>

             <?php
                error_reporting(E_ERROR | E_WARNING | E_PARSE);
                $page_ext = ".php";
                $page_ext_qry = $page_ext."?";
                $sql_order = "ORDER BY PassID DESC"; //defaultne zotriedenie

                //sekcia kde osetrujem stavy ked kliknem na nejaky link

                if (isset($_GET['search_string'])) { //nieco hladam
                    $search_string=$_GET['search_string'];

                    $search_string=mysqli_real_escape_string($con,$search_string);

                    $sql="SELECT * from tblpasswords where system_name LIKE '%$search_string%' OR user_name LIKE '%$search_string%' OR description LIKE '%$search_string%' and is_archived=0";
                                    //echo "$sql";
                    }

                                elseif(isset($_GET['alphabet'])) { //kliknem na pismeno

                                    $letter=$_GET['alphabet'];
                                    $sql = "SELECT DISTINCT * FROM tblpasswords WHERE LEFT(system_name,1) = '$letter' and is_archived=0 ".$sql_order;

                                } elseif (isset($_GET['cust_id'])) { //kliknem na skupinu

                                    $group=$_GET['cust_id'];
                                    $sql= "SELECT * from tblpasswords WHERE cust_id='$group' and is_archived=0 ORDER BY system_name ASC";

                                } elseif (isset($_GET['category'])){ //klignema na jednu z hlavnych kategorii

                                    $category=$_GET['category'];
                                    $sql="SELECT DISTINCT * from tblpasswords where category='$category'";
                                } elseif(isset($_GET['show'])) 
                                {
                                    if($_GET['show']=='favorites'){
                                        $sql="SELECT * from tblpasswords where is_favorite=1";
                                    }    
                                 }
                                else { //alebo necham tak alebo stlacim na all

                                  //  $sql="SELECT DISTINCT * FROM tblpasswords WHERE is_archived=0 and is_favorite=1 union all 
                                  //  SELECT DISTINCT * FROM tblpasswords WHERE is_archived=0 and is_favorite=0 ORDER BY PassID DESC";
                                    
                                  $sql="SELECT DISTINCT * FROM tblpasswords WHERE is_archived=0 ORDER BY PassID DESC"; }

                                        global $con;
                                        $limit = 20;
                                        $result=mysqli_query($con,$sql) or die("MySQL ERROR: ".mysqli_error($con));
                                        $total_pages=mysqli_num_rows($result);

                                        if(mysqli_num_rows($result)==0){    
                                           // echo "Zaznam neexistuje!!!!"; 
                                            echo "<script>toastr.error('Zaznam neexistuje')</script>";    
                                               } else {
                                        $stages = 3;
                                        $page = mysqli_escape_string($con,$_GET['page']);
                                        if($page){
                                            $start = ($page - 1) * $limit;
                                        }else{
                                            $start = 0;
                                            }
                                        global $con;
                                        $query1 = $sql. " LIMIT $start, $limit";
                                        $result = mysqli_query($con,$query1);

                                                // Initial page num setup
                                                if ($page == 0){$page = 1;}
                                                $prev = $page - 1;
                                                $next = $page + 1;
                                                $lastpage = ceil($total_pages/$limit);
                                                $LastPagem1 = $lastpage - 1;


                                                $paginate = '';
                                                if($lastpage > 1)
                                                {

                                                    $paginate .= "<div class='paginate'>";
                                                    // Previous
                                                    if ($page > 1){
                                                        $paginate.= "<a href='$targetpage?page=$prev'>previous</a>";
                                                    }else{
                                                        $paginate.= "<span class='disabled'>previous</span>";   }



                                                    // Pages
                                                    if ($lastpage < 7 + ($stages * 2))  // Not enough pages to breaking it up
                                                    {
                                                        for ($counter = 1; $counter <= $lastpage; $counter++)
                                                        {
                                                            if ($counter == $page){
                                                                $paginate.= "<span class='current'>$counter</span>";
                                                            }else{
                                                                $paginate.= "<a href='$targetpage?page=$counter'>$counter</a>";}
                                                        }
                                                    }
                                                    elseif($lastpage > 5 + ($stages * 2))   // Enough pages to hide a few?
                                                    {
                                                        // Beginning only hide later pages
                                                        if($page < 1 + ($stages * 2))
                                                        {
                                                            for ($counter = 1; $counter < 4 + ($stages * 2); $counter++)
                                                            {
                                                                if ($counter == $page){
                                                                    $paginate.= "<span class='current'>$counter</span>";
                                                                }else{
                                                                    $paginate.= "<a href='$targetpage?page=$counter'>$counter</a>";}
                                                            }
                                                            $paginate.= "...";
                                                            $paginate.= "<a href='$targetpage?page=$LastPagem1'>$LastPagem1</a>";
                                                            $paginate.= "<a href='$targetpage?page=$lastpage'>$lastpage</a>";
                                                        }
                                                        // Middle hide some front and some back
                                                        elseif($lastpage - ($stages * 2) > $page && $page > ($stages * 2))
                                                        {
                                                            $paginate.= "<a href='$targetpage?page=1'>1</a>";
                                                            $paginate.= "<a href='$targetpage?page=2'>2</a>";
                                                            $paginate.= "...";
                                                            for ($counter = $page - $stages; $counter <= $page + $stages; $counter++)
                                                            {
                                                                if ($counter == $page){
                                                                    $paginate.= "<span class='current'>$counter</span>";
                                                                }else{
                                                                    $paginate.= "<a href='$targetpage?page=$counter'>$counter</a>";}
                                                            }
                                                            $paginate.= "...";
                                                            $paginate.= "<a href='$targetpage?page=$LastPagem1'>$LastPagem1</a>";
                                                            $paginate.= "<a href='$targetpage?page=$lastpage'>$lastpage</a>";
                                                        }
                                                        // End only hide early pages
                                                        else
                                                        {
                                                            $paginate.= "<a href='$targetpage?page=1'>1</a>";
                                                            $paginate.= "<a href='$targetpage?page=2'>2</a>";
                                                            $paginate.= "...";
                                                            for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++)
                                                            {
                                                                if ($counter == $page){
                                                                    $paginate.= "<span class='current'>$counter</span>";
                                                                }else{
                                                                    $paginate.= "<a href='$targetpage?page=$counter'>$counter</a>";}
                                                            }
                                                        }
                                                    }

                                                            // Next
                                                    if ($page < $counter - 1){
                                                        $paginate.= "<a href='$targetpage?page=$next'>next</a>";
                                                    }else{
                                                        $paginate.= "<span class='disabled'>next</span>";
                                                        }

                                                    $paginate.= "</div>";


                                            }
                                             //echo $total_pages.' Results';
                                             // pagination
                                             echo $paginate;
                                        
                                        while ($row = mysqli_fetch_array($result)) {
                                       
                                        $id = $row["PassID"];
                                        $cust_id=$row['cust_id'];
                                        $customer_name=CustomerName($cust_id);
                                        $group_name=$row['group_name'];
                                        $system_name=$row['system_name'];
                                        $user_name=$row['user_name'];
                                        $password=$row['password'];
                                        $category=$row['category'];
                                        $url=$row['url'];
                                        $description=$row['description'];
                                        $is_favorite=$row['is_favorite'];

                                      /*  if($url<>'') {//url je zadane
                                           // echo "<tr data-href='$url'>";
                                           
                                        } else {    
                                        echo "<tr data-href='password.php?id=$id'>";
                                        
                                            }        

                                        */    
                                            echo "<td class='pass_system_name hasTooltip' data-href='$url'><b>$system_name</b></td>";
                                               echo "<td>$user_name</td><td>$customer_name</td>";
                                                echo "<td><div id='fav-$id' class='fav_id'>";
                                                if($is_favorite==1) {
                                                    echo "<a href='#' onclick='RemoveFavorite($id);'><i class='fas fa-star'></i></a>";
                                                } else {
                                                    echo "<a href='#' onclick='AddFavorite($id);'><i class='far fa-star'></i></a>";
                                                  }
                                                echo "</div></td>";   
                                                echo "<td style='width:215px'><ul id='pass_actions'><li><a href='password${page_ext_qry}id=$id' class='btn-small'>View</a></li><li><a href='password_edit${page_ext_qry}id=$id' class='btn-small'>Edit</a></li><li><a href='password_delete${page_ext_qry}id=$id' class='btn-small'><i class='fas fa-times'></i></a></li><li><a href='password_archive${page_ext_qry}id=$id' class='btn-small'><i class='fas fa-archive'></i></a></li></ul></td>";                                                
                                            echo "</tr>";

                                            }
                                        }           
                                            ?>
                                            <script type="text/javascript">
                                                function AddFavorite(PassID){
                                                    var xhttp = new XMLHttpRequest();
                                                    xhttp.onreadystatechange = function() {
                                                        if (this.readyState == 4 && this.status == 200) {
                                                        document.getElementById("fav-"+PassID).innerHTML =    
                                                        "<a href='#' onclick='RemoveFavorite("+PassID+");'><i class='fas fa-star'></i></a>";
                                                       
                                                        }
                                                    };
                                                        xhttp.open("GET", "add_favorites.php?id="+PassID, true);
                                                        xhttp.send();
                                                }

                                            function RemoveFavorite(PassID){
                                                var xhttp = new XMLHttpRequest();
                                                xhttp.onreadystatechange = function() {
                                                    if (this.readyState == 4 && this.status == 200) {
                                                    document.getElementById("fav-"+PassID).innerHTML =    
                                                    "<a href='#' onclick='AddFavorite("+PassID+");'><i class='far fa-star'></i></a>";
                                                    
                                                    }
                                                };
                                                    xhttp.open("GET", "remove_favorites.php?id="+PassID, true);
                                                    xhttp.send();
                                            }
                                      </script>  
                            </table>
                            
                            <!-- ked kliknes na riadok, tak ta to presmeruje -->
                            <script>
                                   document.addEventListener("DOMContentLoaded", ()=> {
                                        const rows = document. querySelectorAll("td[data-href]");
                                        
                                        rows.forEach(row => {
                                            row.addEventListener("click", () => {
                                                //window.location.href=row.dataset.href;
                                               window.open(row.dataset.href);
                                            });
                                        });
                                   });         
                            
                            </script>
                            <?php echo $paginate; ?>
                           
                           <!-- chart -->
                           <!--<div class="pass_history_chart_wrap">
                           <canvas id="myChart" width="800" height="400"></canvas>
                            <script>
                                var ctx = document.getElementById("myChart");
                                var myLineChart = new Chart(ctx, {
                                    type: 'line',
                                    data: {

                                    },
                                    options: options
                                });
                            </script>               
                            </div>-->
                            
                        </div><!-- left panel -->


                        <div id="right_panel">

                            <div id="password_categories">
                              <span class="list-head"><i class="fa fa-users" aria-hidden="true"></i> Customers</span>
                                <ul>

                                    <?php
                                        global $con;
                                        $sql="SELECT b.customer_name, a.cust_id, b.customer_status, COUNT(a.cust_id)  as counts from tblpasswords a, tblcustomers b  WHERE a.cust_id=b.cust_id and b.customer_status='active' group by a.cust_id ORDER BY b.customer_name ASC";

                                        $result=mysqli_query($con,$sql) or die(mysqli_error($con));;


                                        while ($row = mysqli_fetch_array($result)) {
                                              $customer_name=$row['customer_name'];
                                              $cust_id=$row['cust_id'];
                                              $counts=$row['counts'];
                                              //if ($group_name=='') {$group_name='unsorted';}
                                              //$NrOfItems = GetCountofPassCatMemebers($group_name);
                                              echo "<li><a href='index.php?cust_id=$cust_id' class='pass_group_name'>$customer_name</a><span class='nr_of_items'>$counts</span></li>";
                                              }


                                        ?>
                                </ul>
                            </div><!--categories -->

                            <div id="password_favorite">
                                <span class="list-head"><i class="fa fa-star"></i> <a href="index.php?show=favorites">Favorites</a></span>
                                <ul>
                                
                                <?php
                                    $sql="SELECT PassID, system_name, is_favorite from tblpasswords where is_favorite=1";
                                    $result=mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error($con));
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo "<li><a href='password.php?id=".$row['PassID']."' class='link'>".$row['system_name']."</a></li>";
                                    }
                                ?>
                            </div>    

                            <div id="password_private_biznis">
                                <span class="list-head"><i class="fa fa-tags"></i> <a href="index.php?show=category">Category</a></span>
                                <ul>
                                    <li><a href="index.php?category=Private">Personal</a></li>
                                    <li><a href="index.php?category=Business">Business</a></li>
                                </ul>
                            </div>
                            
                            <div id="password_most_hits">
                                <span class="list-head"><i class="fa fa-heart"></i> <a href="index.php?show=popular">Popular</a></span>
                                <ul>
                                 <?php
                                    //global $con;
                                    $sql="SELECT hits,PassID,system_name from tblpasswords WHERE hits>0 ORDER BY hits DESC LIMIT 5";
                                    //echo $sql;
                                    $result=mysqli_query($con,$sql) or die("MySQL ERROR: ".mysqli_error($con));
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo "<li><a href='password.php?id=".$row['PassID']."' class='link'>".$row['system_name']."</a></li>";
                                    }
                                ?>
                                </ul>
                            </div>
                            <div id="password_newest">
                                <span class="list-head"><i class="far fa-plus-square"></i> <a href="index.php?show=newest">Newest</a></span>
                                <ul>
                                <?php
                                    global $con;
                                    $sql="SELECT PassID,system_name from tblpasswords ORDER BY PassID DESC LIMIT 5";
                                    $result=mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error($con));
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo "<li><a href='password.php?id=".$row['PassID']."' class='link'>".$row['system_name']."</a></li>";
                                    }
                                ?>
                                </ul>
                            </div>
                            <div id="password_recent">
                                <span class="list-head"><i class="fas fa-history"></i> <a href="index.php?show=recent">Recent</span>
                                <ul>
                                <?php
                                    global $con;
                                    $sql="SELECT PassID,system_name, date_hit from tblpasswords where date_hit<>'0000-00-00 00:00:00' ORDER BY date_hit DESC LIMIT 5";
                                    $result=mysqli_query($con, $sql) or die("MySQL ERROR: ".mysqli_error($con));
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo "<li><a href='password.php?id=".$row['PassID']."' class='link'>".$row['system_name']."</a></li>";
                                    }
                                ?>
                                </ul>
                            </div>
                        </div><!-- right pannel -->
                      </div>  
        </div>
          
</body>
</html>
