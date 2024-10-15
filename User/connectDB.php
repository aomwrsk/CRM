<?php
function connectDB()
{
    // Database connection parameters
    $serverName = "203.151.66.176,55449";  // IP address or hostname of the SQL Server
    $userName = "sa";             // SQL Server login username
    $userPassword = "System2560";      // SQL Server login password
    $dbName = "EntechWebDB";            // Name of the database to connect to

    // Connection options
    $connectionInfo = array(
        "Database" => $dbName,
        "UID" => $userName,
        "PWD" => $userPassword,
        "ReturnDatesAsStrings" => true,        // Return date values as strings
        "MultipleActiveResultSets" => true,    // Allow multiple active result sets
        "CharacterSet" => 'UTF-8'             // Set character encoding to UTF-8
    );

    // Attempt to establish a connection
    $objCon = sqlsrv_connect($serverName, $connectionInfo);

    return $objCon;  // Return the connection object (or FALSE if connection fails)
}
if($objCon = connectDB())
	{
		//echo "Database Connected.";
       
	}
?>
