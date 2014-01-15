<?
//$temp ='date=2013-07-19&new_patient-values%5B%5D=on&new_patient-values%5B%5D=on&new_patient-values%5B%5D=on&new_patient-values%5B%5D=on&new_patient-values%5B%5D=on&new_patient-values%5B%5D=on&new_patient-values%5B%5D=on&new_patient-values%5B%5D=on&new_patient-values%5B%5D=on&new_patient-values%5B%5D=on';

//echo sizeof($temp);
error_reporting(E_ALL);
require_once('connect.class.php');

	try {
		$db = Database::getConnection();
//		echo $db;
	    //$db = null;
	} catch (PDOException $e) {
	    print "Error!: " . $e->getMessage() . "<br/>";
	    die();
	}
	$result = $db->prepare("SELECT * FROM users");
	$result->execute();
	$row = $result->fetch(PDO::FETCH_ASSOC);
	
	print_r($row);
?>
