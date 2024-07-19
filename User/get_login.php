<?php
	ini_set('display_errors', 0);
	error_reporting(~0);

	session_start();
  include_once('./connectDB.php');
$objCon = connectDB();
$data = $_POST;
$username = $data["username"];
$password = sha1($data["password"]);
$time = time();
date_default_timezone_set("Asia/Bangkok");
$timestamp = date('Y-m-d H:i:s', $time);
//print_r ($data);
	if( $conn === false ) {
      die( print_r( sqlsrv_errors(), true));
    }
	
	$strSQL = "SELECT * FROM a_user WHERE username=? AND password=?";
	$parameters = [$username, $password];
	$objQuery = sqlsrv_query($objCon, $strSQL, $parameters);
	$objResult = sqlsrv_fetch_array($objQuery,SQLSRV_FETCH_ASSOC);
	 $id = $objResult["staff_id"];
	if ($objResult === false) {
		die(print_r(sqlsrv_errors(), true));
	} 
	
	if(!$objResult)
	{
			echo '<script>alert("Username and Password Incorrect!");window.location="pages-login.html";</script>';
	}
	else
	{
		
			$_SESSION["staff_id"] = $objResult["staff_id"];
			$_SESSION["status"] = $objResult["status"];
			$_SESSION["appoint"] = $objResult["appoint"];
			$_SESSION["order"] = $objResult["order"];
			$_SESSION["booking"] = $objResult["booking"];
			session_write_close();
			$strSQL = "UPDATE a_user SET record_time = ? WHERE staff_id = $id";
			$param = array($timestamp) ;
			$objQuery = sqlsrv_query($objCon, $strSQL, $param);
			if($objResult["status"] == "Admin")
			{
        echo '<script>alert("Login suceessed");window.location="/index.html";</script>';
	
			}
			else
			{
        echo '<script>alert("Login suceessed");window.location="/index";</script>';
		
			}
	}
	sqlsrv_close($objCon);

?>