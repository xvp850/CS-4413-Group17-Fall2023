<?php
session_start();
// create_cat.php 
include 'connect.php';
include 'header.php';
// first select the category based on $_GET['cat_id'] 
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (!is_numeric($id) || $id <= 0 || floor($id) != $id) {
        echo 'Invalid category ID: ' . htmlspecialchars($id);
        exit;  // Stop execution to prevent further processing
    }
    //echo "Debug: ID from GET = $id<br>";  // Add this line for debug
    // Select the category based on $_GET['id']
    $sql = "SELECT cat_id, cat_name, cat_description 
            FROM categories 
            WHERE cat_id = ?";
    $stmt_category = mysqli_prepare($db_connection, $sql);

    if ($stmt_category) {
        mysqli_stmt_bind_param($stmt_category, 'i', $id);
        mysqli_stmt_execute($stmt_category);
        $result_category = mysqli_stmt_get_result($stmt_category);

        if (!$result_category) {
            echo 'The category could not be displayed, please try again later.' . mysqli_error($db_connection);
        } else {
            if (mysqli_num_rows($result_category) == 0) {
                echo 'This category does not exist.';
            } else {
                while ($row = mysqli_fetch_assoc($result_category)) {
                //    echo "Category ID: " . $row['cat_id'] . "<br>";
                //    echo "Category Name: " . $row['cat_name'] . "<br>";
                //    echo "Category Description: " . $row['cat_description'] . "<br>";
                //    echo '<h2>Topics in ′' . $row['cat_name'] . '′ category</h2>';
                }
            }

            // Query for the topics in the category
            $sql_topics = "SELECT 
                            topic_id, 
                            topic_subject, 
                            topic_date 
                        FROM 
                            topics 
                        WHERE 
                            topic_cat = ?";

            $stmt_topics = mysqli_prepare($db_connection, $sql_topics);
            mysqli_stmt_bind_param($stmt_topics, 'i', $id);
            mysqli_stmt_execute($stmt_topics);
            $result_topics = mysqli_stmt_get_result($stmt_topics);

            if (!$result_topics) {
                echo 'The topics could not be displayed, please try again later.';
            } else {
                if (mysqli_num_rows($result_topics) == 0) {
                    echo 'There are no topics in this category yet.';
                } else {
                    echo '<table border="1"> 
                            <tr> 
                                <th>Topic</th> 
                                <th>Created at</th> 
                            </tr>';

                    while ($row = mysqli_fetch_assoc($result_topics)) {
                        echo '<tr>';
                        echo '<td class="leftpart">';
                        echo '<h3><a href="topic.php?id=' . $row['topic_id'] . '">' . $row['topic_subject'] . '</a></h3>';
                        echo '</td>';
                        echo '<td class="rightpart">';
                        echo date('d-m-Y', strtotime($row['topic_date']));
                        echo '</td>';
                        echo '</tr>';
                    }

                    echo '</table>';
                }
            }
        }
        mysqli_stmt_close($stmt_category);
        mysqli_stmt_close($stmt_topics);
    } else {
        echo "Error in preparing the statement: " . mysqli_error($db_connection);
    }
} else {
    echo "Category ID is not set.";
}

include 'footer.php';
?>