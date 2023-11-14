<?php
session_start();
include 'connect.php';
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if it's a new topic or a reply
    if (isset($_POST['post-content'])) {
        // It's a new topic
        $topic_subject = mysqli_real_escape_string($db_connection, $_POST['topic_subject']);
        $topic_cat = mysqli_real_escape_string($db_connection, $_POST['topic_cat']);
        $topic_by = $_SESSION['user_id'];

        $sql = "INSERT INTO topics (topic_subject, topic_date, topic_cat, topic_by) 
                VALUES ('$topic_subject', NOW(), '$topic_cat', '$topic_by')";

        $result = mysqli_query($db_connection, $sql);

        if (!$result) {
            echo 'An error occurred while creating your topic. Please try again later.';
        } else {
            $topic_id = mysqli_insert_id($db_connection);

            // Now, insert the post for the new topic
            $post_content = mysqli_real_escape_string($db_connection, $_POST['post-content']);
            $post_topic = $topic_id;
            $post_by = $_SESSION['user_id'];

            $sql = "INSERT INTO posts (post_content, post_date, post_topic, post_by) 
                    VALUES ('$post_content', NOW(), '$post_topic', '$post_by')";

            $result = mysqli_query($db_connection, $sql);

            if (!$result) {
                echo 'An error occurred while creating your post. Please try again later.';
            } else {
                echo 'You have successfully created your new topic and posted a reply.';
            }
        }
    } elseif (isset($_POST['reply-content'])) {
        // It's a reply
        $post_content = mysqli_real_escape_string($db_connection, $_POST['reply-content']);
        $post_topic = mysqli_real_escape_string($db_connection, $_POST['topic_id']);
        $post_by = $_SESSION['user_id'];

        $sql = "INSERT INTO posts (post_content, post_date, post_topic, post_by) 
                VALUES ('$post_content', NOW(), '$post_topic', '$post_by')";

        $result = mysqli_query($db_connection, $sql);

        if (!$result) {
            echo 'An error occurred while creating your reply. Please try again later.';
        } else {
            echo 'You have successfully posted a reply.';
        }
    }
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $topic_id = $_GET['id'];

    // Fetch the topic information
    $topic_sql = "SELECT 
                    topic_id, 
                    topic_subject, 
                    topic_date 
                FROM 
                    topics 
                WHERE 
                    topic_id = ?";
    $topic_stmt = mysqli_prepare($db_connection, $topic_sql);

    if ($topic_stmt) {
        mysqli_stmt_bind_param($topic_stmt, 'i', $topic_id);
        mysqli_stmt_execute($topic_stmt);
        $topic_result = mysqli_stmt_get_result($topic_stmt);

        if ($topic_result && $topic_row = mysqli_fetch_assoc($topic_result)) {
            echo '<h2>' . $topic_row['topic_subject'] . '</h2>';
            echo '<p>Created at ' . date('d-m-Y', strtotime($topic_row['topic_date'])) . '</p>';

            // Fetch and display replies
            $replies_sql = "SELECT 
                                post_id, 
                                post_content, 
                                post_date 
                            FROM 
                                posts 
                            WHERE 
                                post_topic = ?";
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
                            </tr>';

                    while ($reply_row = mysqli_fetch_assoc($replies_result)) {
                        echo '<tr>';
                        echo '<td class="leftpart">' . $reply_row['post_content'] . '</td>';
                        echo '<td class="rightpart">' . date('d-m-Y', strtotime($reply_row['post_date'])) . '</td>';
                        echo '</tr>';
                    }

                    echo '</table>';
                } else {
                    echo 'Error fetching replies: ' . mysqli_error($db_connection);
                }
            } else {
                echo 'Error preparing statement for replies: ' . mysqli_error($db_connection);
            }

            // Display the combined reply form
            if ($_SESSION['signed_in']) {
                echo '<h3>Post a Reply</h3>';
                echo '<form method="post" action="">
                        <textarea name="reply-content"></textarea>
                        <input type="hidden" name="topic_id" value="' . $topic_id . '">
                        <input type="submit" value="Post Reply">
                    </form>';
            } else {
                echo 'You must be signed in to post a reply.';
            }
        } else {
            echo 'Topic not found.';
        }
    } else {
        echo 'Error preparing statement for topic: ' . mysqli_error($db_connection);
    }
} else {
    echo 'Invalid topic ID.';
}

include 'footer.php';
?>





