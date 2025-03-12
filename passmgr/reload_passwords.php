<?php

include "../include/dbconnect.php";
include "../include/functions.php";

$limit = 20;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start = ($page > 1) ? ($page - 1) * $limit : 0;

// Získanie celkového počtu hesiel
$count_query = "SELECT COUNT(*) FROM tblpasswords WHERE is_archived=0";
$count_result = mysqli_query($con, $count_query);
$total_pages = mysqli_fetch_array($count_result)[0];

// Použitie pripraveného SQL dotazu na bezpečný výber dát
$query = "SELECT PassID, cust_id, group_name, system_name, user_name, password, category, url, description, is_favorite 
          FROM tblpasswords 
          WHERE is_archived=0 
          ORDER BY PassID DESC 
          LIMIT ?, ?";

$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "ii", $start, $limit);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Počet stránok
$lastpage = ceil($total_pages / $limit);
$prev = $page - 1;
$next = $page + 1;

echo "<table>";
while ($row = mysqli_fetch_array($result)) {
    $id = $row["PassID"];
    $cust_id = $row['cust_id'];
    $customer_name = htmlspecialchars(CustomerName($cust_id));
    $group_name = htmlspecialchars($row['group_name']);
    $system_name = htmlspecialchars($row['system_name']);
    $user_name = htmlspecialchars($row['user_name']);
    $password = htmlspecialchars($row['password']);  // Heslo je zachované
    $category = htmlspecialchars($row['category']);
    $url = htmlspecialchars($row['url']);
    $description = htmlspecialchars($row['description']);
    $is_favorite = $row['is_favorite'];

    echo "<tr>";
    echo "<td class='hasTooltip' data-href='$url'><b>$system_name</b></td>";
    echo "<td>$user_name</td>";
    echo "<td>$password</td>";  // Zobrazovanie hesla
    echo "<td><div id='fav-$id' class='fav_id'>";
    if ($is_favorite == 1) {
        echo "<a href='#' onclick='RemoveFavorite($id);'><i class='fas fa-star'></i></a>";
    } else {
        echo "<a href='#' onclick='AddFavorite($id);'><i class='far fa-star'></i></a>";
    }
    echo "</div></td>";
    echo "<td>
            <a href='password?id=$id' class='btn-small'><i class='fas fa-eye'></i></a>
            <a href='password_edit?id=$id' class='btn-small'><i class='fas fa-edit'></i></a>
            <a href='password_archive?id=$id' class='btn-small'><i class='fas fa-archive'></i></a>
            <a href='password_clone?id=$id' class='btn-small'><i class='fas fa-copy'></i></a>
            <a href='password_delete?id=$id' class='btn-small remove'><i class='fas fa-times'></i></a>
          </td>";
    echo "</tr>";
}
echo "</table>";

// Generovanie stránkovania
echo "<div class='paginate'>";
if ($page > 1) {
    echo "<a href='?page=$prev'>Previous</a>";
} else {
    echo "<span class='disabled'>Previous</span>";
}

for ($i = 1; $i <= $lastpage; $i++) {
    if ($i == $page) {
        echo "<span class='current'>$i</span>";
    } else {
        echo "<a href='?page=$i'>$i</a>";
    }
}

if ($page < $lastpage) {
    echo "<a href='?page=$next'>Next</a>";
} else {
    echo "<span class='disabled'>Next</span>";
}
echo "</div>";
?>
