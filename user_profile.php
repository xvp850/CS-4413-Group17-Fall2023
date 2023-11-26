<?php
session_start();
include 'connect.php';
include 'header.php';

			
if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true) {
    $user_id = $_GET['id'];

    // Fetch the topic information
    $user_sql = "SELECT 
                    u.user_id, 
                    u.user_name 
                FROM 
                    users t
                JOIN 
                    users u = u.user_id
    $user_stmt = mysqli_prepare($db_connection, $user_id);

    if ($user_stmt) {
        mysqli_stmt_bind_param($user_stmt, 'i', $user_id);
        mysqli_stmt_execute($user_stmt);
        $user_result = mysqli_stmt_get_result($user_stmt);

        if ($user_result && $user_row = mysqli_fetch_assoc($user_result)) {
            echo '<h2>' . $user_row['user_name'] . '</h2>';
/*
            // Fetch and display replies
            $replies_sql = "SELECT 
                                p.post_id, 
                                p.post_content, 
                                p.post_date, 
                                u.user_id, 
                                u.user_name 
                            FROM 
                                posts p
                            JOIN 
                                users u ON p.post_by = u.user_id
                            WHERE 
                                p.post_topic = ?";
            $replies_stmt = mysqli_prepare($db_connection, $replies_sql);

            if ($replies_stmt) {
                mysqli_stmt_bind_param($replies_stmt, 'i', $topic_id);
                mysqli_stmt_execute($replies_stmt);
                $replies_result = mysqli_stmt_get_result($replies_stmt);

                if ($replies_result) {
                    echo '<h3>Replies</h3>';
                    echo '<table border="1"> 
                            <tr> 
                                <th>Reply</th> 
                                <th>Created at</th> 
                                <th>Created by</th>
                            </tr>';

                    while ($reply_row = mysqli_fetch_assoc($replies_result)) {
                        echo '<tr>';
                        echo '<td class="leftpart">' . $reply_row['post_content'] . '</td>';
                        echo '<td class="rightpart">' . date('d-m-Y', strtotime($reply_row['post_date'])) . '</td>';
                        echo '<td class="rightpart">' . $reply_row['user_name'] . '</td>';
                        echo '</tr>';
                    }

                    echo '</table>';
*/
                } else {
                    echo 'Error fetching replies: ' . mysqli_error($db_connection);
                }
            } else {
                echo 'Error preparing statement for replies: ' . mysqli_error($db_connection);
            }
include 'footer.php';
?>
