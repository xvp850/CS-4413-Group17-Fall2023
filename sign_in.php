<?php
session_start();
include 'connect.php';
include 'header.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<h3>Sign in</h3>';

if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true) {
    echo 'You are already signed in, you can <a href="sign_out.php">sign out</a> if you want.';
} else {
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        echo '<form method="post" action="">
        Username: <input type="text" name="user_name" />
        Password: <input type="password" name="user_pass">
        <input type="submit" value="Sign in" />
        </form>';
        echo 'Click here if you <a href="forgot_password.php">forgot your password.</a>';
    } else {
        $errors = array();

        if (empty($_POST['user_name'])) {
            $errors[] = 'The username field must not be empty.';
        }
        if (empty($_POST['user_pass'])) {
            $errors[] = 'The password field must not be empty.';
        }

        if (!empty($errors)) {
            echo 'There are errors in the fields and they are not filled in correctly.';
            echo '<ul>';

            foreach ($errors as $key => $value) {
                echo '<li>' . $value . '</li>';
            }
            echo '</ul>';
        } else {
            $sql = "SELECT user_id, user_name, user_level FROM users WHERE user_name = ? AND user_pass = ?";
            $stmt = mysqli_prepare($db_connection, $sql);

            if ($stmt) {
                $username = $_POST['user_name'];
                $password = sha1($_POST['user_pass']); // Hash the password

                mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if (!$result) {
                    echo 'Something went wrong while signing in. Please try again later.';
                } else {
                    if (mysqli_num_rows($result) == 0) {
                        echo 'You have supplied a wrong user/password combination. Please try again.';
                    } else {
                        $row = mysqli_fetch_assoc($result);

                        $_SESSION['signed_in'] = true;
                        $_SESSION['user_id'] = $row['user_id'];
                        $_SESSION['user_name'] = $row['user_name'];
                        $_SESSION['user_level'] = $row['user_level'];

                        echo 'Welcome, ' . $_SESSION['user_name'] . '. <a href="index.php">Proceed to the forum overview</a>.';
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
