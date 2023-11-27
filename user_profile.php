<?php
session_start();
include 'connect.php';
include 'header.php';

			
if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true) {

    echo'.$_SESSION['user_name'].';
}
	    
include 'footer.php';
?>
