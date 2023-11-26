<?php
session_start();
include 'connect.php';
include 'header.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<h3>Sign Out</h3>';

if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true) {
    session_unset();
    session_destroy();
    echo 'Goodbye onii-chan. You are signed out, you can <a href="sign_in.php">sign in</a> again if you want.';
} else {
    echo 'Baka. You are already signed out, you can <a href="sign_in.php">sign in</a> if you want.';
}

include 'footer.php';
?>
