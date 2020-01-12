<?php

$procedure = $_POST['postprocedure'];
$date =  $_POST['date'];

$dbname = "finance";
// Create connection
$sql = new mysqli(ini_get("mysqli.default_host"), ini_get("mysqli.default_user"),ini_get("mysqli.default_pw"), $dbname);

// Check connection
if ($sql->connect_error)
{
	http_response_code(550);
	die("Connection failed: " . $sql->connect_error);
}

//Check for Procedure with or without parameters
if ($date == '')
{
	//Call Query
	$result = $sql->query("CALL $procedure");
	if ($result == false)
	{
		http_response_code(551);
		die("Query failed!");
	}

	//Save response in Array
	$response = array();
	while($row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$response[] = $row;
	}

	//Echo to Javyscript
	header('Content-type: application/json');
	echo json_encode($response);
	exit();
}

if($date != '')
{
	//Call Query
	$stmt = $sql->prepare("CALL $procedure(?)");
	if ($stmt == false)
	{
		http_response_code(554);
		die("Statement could not be Assigned!");
	}

	//Bind Variable
	$stmt->bind_param("s",$date);
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
	exit();
}
?>
