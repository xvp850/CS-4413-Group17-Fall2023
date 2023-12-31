<?php
/* This is delete_category.php */

//Currently just copied the category page

session_start();

include 'connect.php';
include 'header.php';

error_reporting(E_ALL);

ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    // The form hasn't been posted yet, display it 
    echo "<form method='post' action=''> 
Category name: <input type='text' name='category_name' /> 
Category description: <textarea name='category_description'></textarea> 
<input type='submit' value='Add category' /> 
</form>";
} else {
    // The form has been posted, so save it 
    $category_name = $_POST['category_name'];
    $category_description = $_POST['category_description'];
    
    // Prepare the SQL statement with placeholders 
    $sql = "INSERT INTO categories (category_name, category_description) 
VALUES (?, ?)";
    
    // Create a prepared statement 
    $stmt = mysqli_prepare($db_connection, $sql);
    
    // Bind parameters to the prepared statement 
    mysqli_stmt_bind_param($stmt, 'ss', $category_name, $category_description);
    
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
?>
