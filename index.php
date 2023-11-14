<?php
session_start();
// create_cat.php 
include 'connect.php';
include 'header.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
$sql = "SELECT 
cat_id, 
cat_name, 
cat_description 
FROM 
categories";
$result = mysqli_query($db_connection, $sql);
if (!$result) {
    echo 'The categories could not be displayed, please try again later.';
} else {
    if (mysqli_num_rows($result) == 0) {
        echo 'No categories defined yet.';
    } else {
        // Prepare the table 
        echo '<table border="1"> 
<tr> 
<th>Category</th> 
<th>Last topic</th> 
</tr>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td class="leftpart">';
            echo '<h3><a href="category.php?id=' . $row['cat_id'] . '">' . $row['cat_name'] . '</a></h3>' . $row['cat_description'];
            echo '</td>';
            echo '<td class="rightpart">';
            echo '<a href="topic.php?id=">Topic subject</a> at 10-10';
            echo '</td>';
            echo '</tr>';
        }
    }
}
include 'footer.php';
?>

