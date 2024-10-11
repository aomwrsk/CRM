<?php
function connectDB() {
    $host = '203.151.66.176';
    $username = 'sa';
    $password = 'System2560';
    $dbname = 'EntechWebDB';

    // Create a MySQLi connection
    $conn = mysqli_connect($host, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }else{
        echo "Success";
    }

    return $conn;
}
?>
