<?
	//--------------------------------------------------------------------------
	// 1) Connect to mysql database
	//--------------------------------------------------------------------------
	// include 'connect.php';
	require('connect.class.php');
	//$con = mysql_connect($host,$user,$pass);
	// $dbs = mysql_select_db($databaseName, $con);
	
	$tableName = 'patient_log';
	
	// $db = new PDO("mysql:host=$host;dbname=$databaseName;", "$user", "$pass");
	$db = Database::getConnection();
		
$numeric = array(
	'1'=>'one',
	'2'=>'two',
	'3'=>'three',
	'4'=>'four',
	'5'=>'five',
	'6'=>'six',
	'7'=>'seven',
	'8'=>'eight',
	'9'=>'nine',
	'10'=>'ten',
	'11'=>'eleven',
	'12'=>'twelve',
	'13'=>'thirteen',
	'14'=>'fourteen',
	'15'=>'fifteen',
	'16'=>'sixteen',
	'17'=>'seventeen',
	'18'=>'eighteen',
	'19'=>'nineteen',
	'20'=>'twenty'
);	

/*
	for ($i = 1; $i <= count($_POST['new_patient_values']); $i++) {
		$new_patients[] = $numeric[$i];
	}
	$new_patients = implode(', ', $new_patients);

	for ($i = 1; $i <= count($_POST['follow_up_values']); $i++) {
		$follow_up[] = $numeric[$i];
	}
	$follow_up = implode(', ', $follow_up);
*/
	$new_patients = count($_POST['new_patient_values']);

	$follow_up = count($_POST['follow_up_values']);
	
	$date = $_POST['date'];

	//--------------------------------------------------------------------------
	// 2) Query database for data
	//--------------------------------------------------------------------------
	// $existing_row = mysql_query("SELECT id from $tableName WHERE date='$date'");

	// $existing_row_test = mysql_fetch_row($existing_row);

	$existing_row = $db->prepare("SELECT id from $tableName WHERE date=?");
	$existing_row->execute(array($date));
	
	$existing_row_test = $existing_row->fetch(PDO::FETCH_ASSOC);

	$id = $existing_row_test['id'];

	// echo json_encode($_POST)
	
	if ($new_patients == 0 && $follow_up == 0) {
		echo json_encode("Please enter at least 1 new or follow up patient.");
	} else if ($id) {
		// $query = "UPDATE $tableName SET new_patients = '$new_patients', follow_up = '$follow_up' WHERE id = $id";
		
		// $result = mysql_query($query);
		
		$result = $db->prepare("UPDATE $tableName SET new_patients = ?, follow_up = ? WHERE id = ?");
		$result->execute(array($new_patients, $follow_up, $id));
		
		$affected_rows = $result->rowCount();
		
		echo json_encode($affected_rows);
		
		//$array = $result->fetch(PDO::FETCH_ASSOC);
		// echo json_encode($array);
		
		//echo json_encode($result);
		//print $result;
		/*if ($result === false) {
			return false;
		} else {
			while ($row = mysql_fetch_assoc($result)) {
				echo json_encode($array);
			}
		}*/		
	} else {

		$result = $db->prepare("INSERT INTO $tableName (new_patients, follow_up, date) VALUES (:new_patients, :follow_up, :date)");
		$result->execute(array(':new_patients' => $new_patients, ':follow_up' => $follow_up, ':date' => $date));
		
		$affected_rows = $result->rowCount();

		echo json_encode($affected_rows);		
		// $array = $result->fetch(PDO::FETCH_ASSOC);
		//echo json_encode($array);
		
		/*$query = "INSERT INTO $tableName (new_patients, follow_up, date) VALUES ('$new_patients', '$follow_up', '$date')";

		$result = mysql_query($query);

		//$array = mysql_fetch_row($result);
		//echo json_encode($array);
		if ($result === false) {
			return false;
		} else {
			$array = mysql_fetch_assoc($result);
			echo json_encode($array);
		}*/		
	}
	
	//--------------------------------------------------------------------------
	// 3) echo result as json 
	//--------------------------------------------------------------------------
	// echo json_encode($existing_row_test[0]);
	//echo json_encode($array);

?>
