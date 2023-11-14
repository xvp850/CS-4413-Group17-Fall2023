<?php
session_start();

include 'connect.php';
include 'header.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $topic_id = $_GET['id'];

    // Retrieve the topic information
    $topic_sql = "SELECT 
                    topic_subject, 
                    topic_date 
                FROM 
                    topics 
                WHERE 
                    topic_id = $topic_id";

    $topic_result = mysqli_query($db_connection, $topic_sql);

    if ($topic_result && mysqli_num_rows($topic_result) > 0) {
        $topic_row = mysqli_fetch_assoc($topic_result);

        echo '<h2>' . $topic_row['topic_subject'] . '</h2>';
        echo '<p>Created at: ' . date('d-m-Y', strtotime($topic_row['topic_date'])) . '</p>';

        // Retrieve and display posts for the selected topic
        $posts_sql = "SELECT 
                        post_content, 
                        post_date 
                    FROM 
                        posts 
                    WHERE 
                        post_topic = $topic_id";

        $posts_result = mysqli_query($db_connection, $posts_sql);

        if ($posts_result && mysqli_num_rows($posts_result) > 0) {
            echo '<h3>Posts</h3>';
            echo '<ul>';

            while ($post_row = mysqli_fetch_assoc($posts_result)) {
                echo '<li>';
                echo '<p>' . $post_row['post_content'] . '</p>';
                echo '<p>Posted at: ' . date('d-m-Y', strtotime($post_row['post_date'])) . '</p>';
                echo '</li>';
            }

            echo '</ul>';
        } else {
            echo 'No posts for this topic yet.';
        }
    } else {
        echo 'Topic not found.';
    }
} else {
    echo "Topic ID is not set or invalid.";
}

?>
<form method="post" action="reply.php">
    <!-- Your form fields go here -->
    <textarea name="reply-content"></textarea>
    <input type="submit" value="Post Reply">
</form>