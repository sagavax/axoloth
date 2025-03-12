<?php
include("../include/dbconnect.php");
include("../include/functions.php");


$sql = "SELECT * from tblpasswords WHERE is_archived=1 ORDER BY PassID DESC";
//echo $sql;
global $con;
$limit = 20;
$result = mysqli_query($con, $sql) or die("MySQL ERROR: " . mysqli_error($con));
$total_pages = mysqli_num_rows($result);

if (mysqli_num_rows($result) == 0) {
    // echo "Zaznam neexistuje!!!!"; 
    echo "<script>toastr.error('Zaznam neexistuje')</script>";
} else {
    $stages = 3;
    $page = mysqli_escape_string($con, $_GET['page']);
    if ($page) {
        $start = ($page - 1) * $limit;
    } else {
        $start = 0;
    }
    global $con;
    $query1 = $sql . " LIMIT $start, $limit";
    $result = mysqli_query($con, $query1);

    // Initial page num setup
    if ($page == 0) {
        $page = 1;
    }
    $prev = $page - 1;
    $next = $page + 1;
    $lastpage = ceil($total_pages / $limit);
    $LastPagem1 = $lastpage - 1;


    $paginate = '';
    if ($lastpage > 1) {

        $paginate .= "<div class='paginate'>";
        // Previous
        if ($page > 1) {
            $paginate .= "<a href='$targetpage?page=$prev'>previous</a>";
        } else {
            $paginate .= "<span class='disabled'>previous</span>";
        }



        // Pages
        if ($lastpage < 7 + ($stages * 2))  // Not enough pages to breaking it up
        {
            for ($counter = 1; $counter <= $lastpage; $counter++) {
                if ($counter == $page) {
                    $paginate .= "<span class='current'>$counter</span>";
                } else {
                    $paginate .= "<a href='$targetpage?page=$counter'>$counter</a>";
                }
            }
        } elseif ($lastpage > 5 + ($stages * 2))   // Enough pages to hide a few?
        {
            // Beginning only hide later pages
            if ($page < 1 + ($stages * 2)) {
                for ($counter = 1; $counter < 4 + ($stages * 2); $counter++) {
                    if ($counter == $page) {
                        $paginate .= "<span class='current'>$counter</span>";
                    } else {
                        $paginate .= "<a href='$targetpage?page=$counter'>$counter</a>";
                    }
                }
                $paginate .= "...";
                $paginate .= "<a href='$targetpage?page=$LastPagem1'>$LastPagem1</a>";
                $paginate .= "<a href='$targetpage?page=$lastpage'>$lastpage</a>";
            }
            // Middle hide some front and some back
            elseif ($lastpage - ($stages * 2) > $page && $page > ($stages * 2)) {
                $paginate .= "<a href='$targetpage?page=1'>1</a>";
                $paginate .= "<a href='$targetpage?page=2'>2</a>";
                $paginate .= "...";
                for ($counter = $page - $stages; $counter <= $page + $stages; $counter++) {
                    if ($counter == $page) {
                        $paginate .= "<span class='current'>$counter</span>";
                    } else {
                        $paginate .= "<a href='$targetpage?page=$counter'>$counter</a>";
                    }
                }
                $paginate .= "...";
                $paginate .= "<a href='$targetpage?page=$LastPagem1'>$LastPagem1</a>";
                $paginate .= "<a href='$targetpage?page=$lastpage'>$lastpage</a>";
            }
            // End only hide early pages
            else {
                $paginate .= "<a href='$targetpage?page=1'>1</a>";
                $paginate .= "<a href='$targetpage?page=2'>2</a>";
                $paginate .= "...";
                for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++) {
                    if ($counter == $page) {
                        $paginate .= "<span class='current'>$counter</span>";
                    } else {
                        $paginate .= "<a href='$targetpage?page=$counter'>$counter</a>";
                    }
                }
            }
        }

        // Next
        if ($page < $counter - 1) {
            $paginate .= "<a href='$targetpage?page=$next'>next</a>";
        } else {
            $paginate .= "<span class='disabled'>next</span>";
        }

        $paginate .= "</div>";
    }
    //echo $total_pages.' Results';
    // pagination
   // echo $paginate;

    while ($row = mysqli_fetch_array($result)) {

        $id = $row["PassID"];
        $cust_id = $row['cust_id'];
        $customer_name = CustomerName($cust_id);
        $group_name = $row['group_name'];
        $system_name = $row['system_name'];
        $user_name = $row['user_name'];
        $password = $row['password'];
        $category = $row['category'];
        $url = $row['url'];
        $description = $row['description'];
        $is_favorite = $row['is_favorite'];

        /*  if($url<>'') {//url je zadane
                       // echo "<tr data-href='$url'>";
                       
                    } else {    
                    echo "<tr data-href='password.php?id=$id'>";
                    
                        }        

                    */
        echo "<td class='pass_system_name hasTooltip' data-href='$url'><b>$system_name</b></td>";
        echo "<td>$user_name</td>";
        //echo "<td>$customer_name</td>";
        echo "<td><div id='fav-$id' class='fav_id'>";
        if ($is_favorite == 1) {
            echo "<a href='#' onclick='RemoveFavorite($id);'><i class='fas fa-star'></i></a>";
        } else {
            echo "<a href='#' onclick='AddFavorite($id);'><i class='far fa-star'></i></a>";
        }
        echo "</div></td>";
        echo "<td><ul id='pass_actions'><li><a href='password${page_ext_qry}id=$id' class='btn-small' >View</a></li><li><a href='password_edit${page_ext_qry}id=$id' class='btn-small'>Edit</a></li><li><a href='password_delete${page_ext_qry}id=$id' class='btn-small'><i class='fas fa-times'></i></a></li><li><a href='password_archive${page_ext_qry}id=$id' class='btn-small'><i class='fas fa-archive'></i></a></li></ul></td>";
        echo "</tr>";
    }
}
?>
            </table>
<?php echo $paginate; ?>  