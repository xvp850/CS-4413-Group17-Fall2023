<?php
session_start();
//include 'connect.php';
include 'header.php';

			
if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true) {
    echo '<tr><th>User</th></tr>';
    
    echo '<tr>';
            echo '<td class="leftpart">';
            echo '<h3>.$_SESSION['user_name'].</h3></td>';
    echo '</tr>';
}
	    
include 'footer.php';
?>
