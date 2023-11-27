<?php
/* This is create_category.php */

// Maybe make this admin only?

session_start();

include 'connect.php';
include 'header.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Must be logged in to create a category. Please sign in.";
} else {
    // Check if the user is an admin (user_level = 0)
    if ($_SESSION['user_level'] == 0) {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            // The form hasn't been posted yet, display it 
            echo "<form method='post' action=''> 
                Category name: <input type='text' name='category_name' />
                <br><br>Category description: <br><textarea name='category_description'></textarea> 
                <input type='submit' value='Add category' /> 
                </form>";
        } else {
            // The form has been posted, so save it 
            $cat_name = $_POST['category_name'];
            $cat_description = $_POST['category_description'];
            
            // Prepare the SQL statement with placeholders 
            $sql = "INSERT INTO categories (cat_name, cat_description) 
                    VALUES (?, ?)";
            
            // Create a prepared statement 
            $stmt = mysqli_prepare($db_connection, $sql);
            
            // Bind parameters to the prepared statement 
            mysqli_stmt_bind_param($stmt, 'ss', $cat_name, $cat_description);
            
            // Execute the prepared statement 
            $result = mysqli_stmt_execute($stmt);
            if (!$result) {
                // Something went wrong, display the error 
                echo 'Error: ' . mysqli_error($db_connection);
            } else {
                echo 'New category successfully added.';
            }
            
            // Close the prepared statement 
            mysqli_stmt_close($stmt);
        }
    } else {
        echo "Must be admin to create a category. Please sign in as an admin.";
    }
}
?>
