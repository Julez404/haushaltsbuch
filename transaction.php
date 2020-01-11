<?php

//Types: new, change
$type = $_POST['type'];
$change_id = $_POST['change_id'];
$category_id = $_POST['category_id'];
$date_spent = $_POST['date'];
$description = $_POST['description'];
$value = $_POST['value'];

$dbname = "finance";
// Create connection
$sql = new mysqli(ini_get("mysqli.default_host"), ini_get("mysqli.default_user"),ini_get("mysqli.default_pw"), $dbname);

// Check connection
if ($sql->connect_error)
{
	http_response_code(550);
	die("Connection failed: " . $sql->connect_error);
}

if ($type == 'new') {
	//Prepared Query Statement
	$query = "INSERT INTO entries (category, date_spent, value, description) VALUES(?,?,?,?)";

	//Create Prepared Statement
	$stmt = $sql->prepare($query);

	// Check if preparation was successfully
	if(!$stmt)
	{
		http_response_code(552);
		die("Prepared Statement Failed: " . $sql->connect_error);
	}

	//Insert data and sent statement
	$stmt->bind_param("ssss", $category_id, $date_spent, $value, $description);
	$stmt->execute();
}

//Close connections
$sql->close();
exit();

?>
