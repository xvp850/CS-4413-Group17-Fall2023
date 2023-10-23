<?php

// connect.php

$server = 'xvp850-mysql.mysql.database.azure.com';

$username = 'xvp850';

$password = 'Mypassword1';

$database = 'CS4413-Group17-Database';

$conn = mysqli_connect($server, $username, $password, $database);

// Check connection

if (!$conn) {

    exit('Error: could not establish database connection');

}

// Select database

if (!mysqli_select_db($conn, $database)) {

    exit('Error: could not select the database');

}

?>

