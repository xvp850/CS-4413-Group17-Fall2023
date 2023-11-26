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

                } else {
                    echo 'Error fetching replies: ' . mysqli_error($db_connection);
                }
            } else {
                echo 'Error preparing statement for replies: ' . mysqli_error($db_connection);
            }
include 'footer.php';
?>
