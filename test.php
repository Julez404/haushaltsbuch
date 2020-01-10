<?php

$dbname = "finance";
// Create connection
$sql = new mysqli(ini_get("mysqli.default_host"), ini_get("mysqli.default_user"),ini_get("mysqli.default_pw"), $dbname);

// Check connection
/*
if ($conn->connect_error)
{
	die("Connection failed: " . $conn->connect_error);
}
*/

//Call Query
$result = $sql->query("CALL GetAllCategories");

//Save response in Array
$response = array();
while($row = $result->fetch_array(MYSQLI_ASSOC))
{
	$response[] = $row;
}

//Echo to Javyscript
header('Content-type: application/json');
echo json_encode($response);

//Close connections
$result->close();
$sql->close();
exit();

?>
