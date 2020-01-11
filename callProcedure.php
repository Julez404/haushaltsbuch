<?php

$procedure = $_POST['postprocedure'];

$dbname = "finance";
// Create connection
$sql = new mysqli(ini_get("mysqli.default_host"), ini_get("mysqli.default_user"),ini_get("mysqli.default_pw"), $dbname);

// Check connection
if ($sql->connect_error)
{
	http_response_code(550);
	die("Connection failed: " . $sql->connect_error);
}

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

//Close connections
$result->close();
$sql->close();
exit();

?>
