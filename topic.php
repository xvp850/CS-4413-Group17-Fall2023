<?php
session_start();

include 'connect.php';
include 'header.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Retrieve the topics within the specified category
    $sql = "SELECT 
                topic_id, 
                topic_subject, 
                topic_date 
            FROM 
                topics 
            WHERE 
                topic_cat = $id";

    $stmt = mysqli_prepare($db_connection, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (!$result) {
            echo 'The topics could not be displayed, please try again later.';
        } else {
            if (mysqli_num_rows($result) == 0) {
                echo 'There are no topics in this category yet.';
            } else {
                echo '<h2>Topics in this category</h2>';
                echo '<table border="1"> 
                        <tr> 
                            <th>Topic</th> 
                            <th>Created at</th> 
                        </tr>';

                while ($row = mysqli_fetch_assoc($result)) {
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
    } else {
        echo "Error in preparing the statement: " . mysqli_error($db_connection);
    }
} else {
    echo "Category ID is not set.";
}

include 'footer.php';
?>