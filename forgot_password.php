<?php
session_start();
include 'connect.php';
include 'header.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<h3>Sign in</h3>';

if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true) {
    echo 'You are already signed in, you can change your password in the <a href="user_profile.php">user profile</a> if you want.';
} else {
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        echo '<form method="post" action="">
        Username: <input type="text" name="user_name" /><br>
        New Password: <input type="password" name="user_pass">
        Password again: <input type="password" name="user_pass_check"><br>
        <input type="submit" value="New password" />
        </form>';
    } else {
        $errors = array();
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

	} else {
		$errors[] = 'The username field must not be empty.';
	}

	if(isset($_POST['user_pass']))
	{
		if($_POST['user_pass'] != $_POST['user_pass_check'])
		{
			$errors[] = 'The two passwords did not match.';
		}
	} else {
		$errors[] = 'The password field cannot be empty.';
	}
	    
        if (!empty($errors)) {
            echo 'There are errors in the fields and they are not filled in correctly.';
            echo '<ul>';

            foreach ($errors as $key => $value) {
                echo '<li>' . $value . '</li>';
            }
            echo '</ul>';
        } else {
		
            $sql = "SELECT user_id, user_name, user_level FROM users WHERE user_name = ?";
            $stmt = mysqli_prepare($db_connection, $sql);

            if ($stmt) {
                $username = $_POST['user_name'];
                $password = sha1($_POST['user_pass']); // Hash the password

                mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if (!$result) {
                    echo 'There was an error in updating the password. Please contact customer support.';
                } else {
                    if (mysqli_num_rows($result) == 0) {
                        echo 'Please try again.';
                    } else {
                        echo 'Your password has been reset. Did you want to <a href="sign_in.php">sign in</a>.';
                    }
                }
            } else {
                echo "Error in preparing the statement: " . mysqli_error($db_connection);
            }
        }
    }
}

include 'footer.php';
?>
