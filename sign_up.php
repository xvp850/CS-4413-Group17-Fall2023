<?php
/* This is the sign_up.php */

include 'connect.php';
include 'header.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<h3>Sign up</h3>';

if($_SERVER['REQUEST_METHOD'] != 'POST')
{
    echo '<form method="post" action="">
Username: <input type="text" name="user_name" /><br>
Password: <input type="password" name="user_pass"><br>
Password again: <input type="password" name="user_pass_check"><br>
E-mail: <input type="email" name="user_email"><br>
User level (delete after): <input type="number" name="user_level"><br>
<br><input type="submit" value="Create Account" /><br>
</form>';
}
else
{

    $errors = array(); /* declare the array for later use */

	if(isset($_POST['user_name']))
	{
		// the user name exists
		if(!ctype_alnum($_POST['user_name']))
		{
			$errors[] = 'The username can only contain letters and digits.';
		}
		// the user name is longer than 25 characters
		if(strlen($_POST['user_name']) > 25)
		{
			$errors[] = 'The username cannot be longer than 25 characters.';
		}

	}
	else
	{
		$errors[] = 'The username field must not be empty.';
	}

	if(isset($_POST['user_pass']))
	{
		if($_POST['user_pass'] != $_POST['user_pass_check'])
		{
			$errors[] = 'The two passwords did not match.';
		}
	}

	else
	{
		$errors[] = 'The password field cannot be empty.';
	}

	if(!empty($errors)) /*check for an empty array, if there are errors, they're in this array (note the ! operator)*/
	{
		echo 'There are error in the fields and are not filled in correctly.';
		echo '<ul>';

		foreach($errors as $key => $value) /* Go through the array so all errors get displayed */
		{
			echo '<li>' . $value . '</li>'; /* error list */
		}
		echo '</ul>';
	}
	else
	{
		//the form has been posted without, so save it
		// mysql_real_escape_string for safety
		// the sha1 function which hashes the password
		// Prepare the SQL statement with placeholders

        $sql = "INSERT INTO users(user_name, user_pass, user_email, user_date, user_level) VALUES (?, ?, ?, NOW(), 0)";

        // Create a prepared statement
        $stmt = mysqli_prepare($db_connection, $sql);

        // Bind parameters to the prepared statement
        mysqli_stmt_bind_param($stmt, 'sss', $_POST['user_name'], sha1($_POST['user_pass']), $_POST['user_email'], $_POST['user_level']);

        // Execute the prepared statement
        $result = mysqli_stmt_execute($stmt);

        if(!$result)
		{
			//something went wrong, display the error
			echo 'Boop boop. Something went wrong while registering. Please try again later.';
			//echo mysqli_error($db_connection); // Display the mysqli error //debugging purposes, uncomment when needed
		}
		else
		{
			echo 'Successfully registered. You can now <a href="sign_in.php">sign in</a> and start sharing your most intimate and unwanted thoughts!'; //Why
		}
	}
}

include 'footer.php';
?>

