<?php
session_start();
// create_cat.php 
include 'connect.php';
include 'header.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
$sql = "SELECT 
    c.cat_id, 
    c.cat_name, 
    c.cat_description,
    (SELECT t.topic_subject FROM topics t WHERE t.topic_cat = c.cat_id ORDER BY t.topic_date DESC LIMIT 1) AS topic_subject,
    (SELECT t.topic_date FROM topics t WHERE t.topic_cat = c.cat_id ORDER BY t.topic_date DESC LIMIT 1) AS topic_date
FROM 
    categories c";
    
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
            if ($row['topic_subject']) {
                echo '<a href="topic.php?id=' . $row['cat_id'] . '">' . $row['topic_subject'] . '</a> at ' . date('d-m-Y', strtotime($row['topic_date']));
            } else {
                echo 'No topics in this category yet.';
            }
            echo '</td>';
            echo '</tr>';
        }
    }
}
include 'footer.php';
?>

