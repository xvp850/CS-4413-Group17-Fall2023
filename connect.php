<?php

// connect.php

$server = 'xvp850-mysql.mysql.database.azure.com';

$username = 'xvp850';

$password = 'Mypassword1';

$database = 'CS4413-Group17-Database';

$db_connection = mysqli_connect($server, $username, $password, $database);

// Check connection
if (mysqli_connect_errno())
{
    //echo 'Connection to database failed:'.mysqli_connect_error();
    exit();
}

//echo "database connection success<br>";
//echo "<strong>now showing results from a database query...</strong>";

// Select database
if (!mysqli_select_db($db_connection, $database)) {
    exit('Error: could not select the database');
}

?>

