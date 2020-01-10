<?php

$dbname = "finance";

// Create connection
$sql = new mysqli(ini_get("mysqli.default_host"), ini_get("mysqli.default_user"),ini_get("mysqli.default_pw"), $dbname);

// Check connection
if ($conn->connect_error)
{
	die("Connection failed: " . $conn->connect_error);
}



// mysqli_query($sql, "CALL GetAllCategories");


//$sql->query("CALL GetAllCategories");


//$result = $sql->query("CALL GetAllCategories");
$result = $sql->query("SELECT * FROM categories");

//echo json_encode($result);
/*
echo json_encode(($result->fetch_array(MYSQLI_ASSOC)));


$result->close();
$sql->close();
return json_encode(($result->fetch_assoc(MYSQLI_ASSOC)));
*/


// run the query
// $result = mysql_query("SELECT * FROM table WHERE field = '{$q}'");

// fetch all results into an array
$response = array();
while($row = $result->fetch_array(MYSQLI_ASSOC))
{
	$response[] = $row;
}


header('Content-type: application/json');
echo json_encode($response);

// save the JSON encoded array
//$jsonData = json_encode($response);
$result->close();
$sql->close();
exit();




/*

$result = $sql->query($query);
if (!$result) {
  printf("Query failed: %s\n", $mysqli->error);
  exit;
}

while($row = $result->fetch_row()) {
  $rows[]=$row;
}

$result->close();
$sql->close();
return $rows;
*/
/*
//loop the result set
echo "SQL-TEST";
echo "<br>";
while ($row = mysqli_fetch_row($result)){
    $length = count($row);
    $count = $length;
    $length = 0;
    while($length < $count)
    {
        echo $row[$length]. "    ";
        $length = $length +1;
    }
    echo "<br>";
}
*/

?>
