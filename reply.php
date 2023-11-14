<?php
session_start();
include 'connect.php';
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo 'This file cannot be called directly.';
} else {
    if (!$_SESSION['signed_in']) {
        echo 'You must be signed in to post a reply.';
    } else {
        $sql = "INSERT INTO 
                    posts(post_content, 
                    post_date, 
                    post_topic, 
                    post_by) 
                VALUES (?, NOW(), ?, ?)";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sii", $_POST['reply-content'], $_GET['id'], $_SESSION['user_id']);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_errno($stmt)) {
            echo 'Your reply has not been saved, please try again later.';
        } else {
            // Redirect back to the topic page after posting the reply
            header("Location: topic.php?id=" . htmlentities($_GET['id']));
            exit(); // Ensure script stops executing after the redirect
        }
    }
}

include 'footer.php';
?>