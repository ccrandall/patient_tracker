<?php 

if(!isset($_SESSION)) {
    session_start();
}

require_once('connect.class.php');

if(isset($_POST['login_submitted'])) { 
	if(empty($_POST['username']) || empty($_POST['password'])){
 
		$errors[] = 'All fields are required.';
	} else {
		//print_r($_POST);
		//--------------------------------------------------------------------------
		// Example php script for fetching data from mysql database
		//--------------------------------------------------------------------------
        // include('connect.php'); 
		// $db = mysql_connect($dbHost,$dbUser,$dbPass)or die("Error connecting to database."); 
		//$db = new PDO("mysql:host=$host;dbname=$databaseName;", "$user", "$pass");
		
		$dbconn = Database::getConnection();
		//echo $dbconn;
		$username = htmlentities($_POST['username']);
		$password = sha1($_POST['password']);
		$tableName = "users";
		
		$result = $dbconn->prepare("SELECT * FROM $tableName WHERE username = ? AND password = ? LIMIT 1");
		$result->execute(array($username, $password));
		//print_r($result);
		$row = $result->fetch(PDO::FETCH_ASSOC);

		if ($row == false) {
			//return false;
			//header("Location: login.php?error"); 
			echo json_encode($row);
		    // exit;
		} else {
			echo json_encode($row);
			$_SESSION['logged_in'] = true;
		}
	} 
}
?>
