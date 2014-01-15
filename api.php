<?php 
	//header("Content-type: application/json");
	//--------------------------------------------------------------------------
	// 1) Connect to mysql database
	//--------------------------------------------------------------------------
	require('connect.class.php');
	// $con = mysql_connect($host,$user,$pass);
	// $dbs = mysql_select_db($databaseName, $con);
	
	// $db = new PDO("mysql:host=$host;dbname=$databaseName;", "$user", "$pass");
	$tableName = 'patient_log';
	
	$db = Database::getConnection();
	//print_r($_GET);
	if ($_GET['date'])
		$date = $_GET['date'];

	// action = view or add
	//$action = ($_GET['action']) ? $_GET['action'] : '';

	//--------------------------------------------------------------------------
	// 2) Query database for data
	//--------------------------------------------------------------------------
	/* PDO */
	
	$result = $db->query("SELECT * FROM $tableName WHERE date = '$date'");
	
	if ($result === false) {
		return false;
	} else {
		$array = $result->fetch(PDO::FETCH_ASSOC);
		echo json_encode($array);
	}

	/* PRE PDO */
	
	/*
	if ($date) {
		$result = mysql_query("SELECT * FROM $tableName WHERE date = '$date'");
		if ($result === false) {
			return false;
		} else {
			$array = mysql_fetch_assoc($result);
			echo json_encode($array);
		}
	} 
	else {
		$result = mysql_query("SELECT * FROM $tableName");
		while ($array = mysql_fetch_array($result)) {
			//$log_dates = $array['date'];
			//echo json_encode($log_dates) . ', ';
			echo json_encode($array);
			//echo json_encode($array['date']);
		}
	}*/

	

?>
