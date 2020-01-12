<?php

//LEGACY -> needs to be moved to call stored Value in Settings
$dbname = "finance";

//Read Parameters, set default Values.
$table = "TRANSACTION";
$date = isset($_POST['date']) ? $_POST['date'] : '';
$date_from = isset($_POST['date_to']) ? $_POST['date_from'] : '';
$date_to = isset($_POST['date_to']) ? $_POST['date_to'] : '';
$value = isset($_POST['value']) ? $_POST['value'] : '';
$value_compare = isset($_POST['value_compare']) ? $_POST['value_compare'] : '';
$sort_by = isset($_POST['sort_by']) ? $_POST['sort_by'] : '';
$group_by = isset($_POST['group_by']) ? $_POST['group_by'] : '';
$sort_order = isset($_POST['sort_order']) ? $_POST['sort_order'] : 'asc';

//Turn asc/desc because Output is mirrored
if ($sort_order == 'asc')
{
	$sort_order = 'desc';
}
else
{
	$sort_order = 'asc';
}

// Create connection
$sql = new mysqli(ini_get("mysqli.default_host"), ini_get("mysqli.default_user"),ini_get("mysqli.default_pw"), $dbname);

// Check connection
if ($sql->connect_error)
{
	http_response_code(550);
	die("Connection failed: " . $sql->connect_error);
}

// Check arguments for semantic errors
if ($date != '' and $date_from != '' and $date_to != '')
{
	http_response_code(560);
	die("To many Date arguments where provided" . $sql->connect_error);
}

// Base query
if ($group_by == '')
{
	$query = "SELECT TRANSACTION.*, CATEGORY.CATEGORY_NAME FROM $table";
}
else
{
	if ($group_by == TRANSACTION_CATEGORY_ID)
	{
		$query = "SELECT $group_by, CATEGORY.CATEGORY_NAME, SUM(TRANSACTION_VALUE) FROM $table";
	}else {
		$query = "SELECT $group_by, SUM(TRANSACTION_VALUE) FROM $table";
	}
}

// Add Category Name from CATEGORY table if (Category is requested)
if ($group_by == '' or $group_by == "TRANSACTION_CATEGORY_ID")
{
	$query .= " JOIN CATEGORY ON CATEGORY.CATEGORY_ID = TRANSACTION.TRANSACTION_CATEGORY_ID";
}

// Subselect
if ($date != '' or $date_to != '' or $date_from != '') //Every possibiliy
{
	$query .= " WHERE";
	if ($date != '')
	{
		$query .= " TRANSACTION_DATE_SPENT = '$date'";
	}
	if ($date_to != '' and $date_from != '')
	{
		$query .= " TRANSACTION_DATE_SPENT BETWEEN '$date_from' AND '$date_to'";
	}
}

//group
if ($group_by != '')
{
	$query .= " GROUP BY $group_by";
}

// Sort Query
if ($sort_by != '')
{
	if ($sort_order != 'asc' and $sort_order != 'desc')
	{
		http_response_code(561);
		die("Sort Order parameter was incorrect" . $sql->connect_error);
	}
	//$query .= " $sort_by $sort_order";
	$query .= " ORDER BY $sort_by $sort_order";
}





//Create Prepared Statement
$stmt = $sql->prepare($query);

// Check if preparation was successfully
if(!$stmt)
{
	http_response_code(552);
	die("Prepared Statement Failed: $query" . $sql->connect_error);
}

//sent statement
$stmt->execute();

//Save response in Array
$result = $stmt->get_result();
$response = array();
while($row = $result->fetch_array(MYSQLI_ASSOC))
{
	$response[] = $row;
}

//Echo to Javyscript
header('Content-type: application/json');
echo json_encode($response);
//echo $query;
exit();


/*
if ($type == 'new') {
	//Prepared Query Statement
	$query = "SELECT * FROM TRANSACTION(TRANSACTION_CATEGORY_ID, TRANSACTION_DATE_SPENT, TRANSACTION_VALUE, TRANSACTION_DESCRIPTION) VALUES(?,?,?,?)";

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
*/
?>
