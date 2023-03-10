
<?php

if(isset($_POST["name"]))
	if($_POST["name"] != ''&& $_POST["pass"]!='')
	{
		
$name=$_POST["name"];
$pass=$_POST["pass"];
include_once 'Database.php';
// Instantiate DB & connect
$database = new Database();
$conn = $database->connect();
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT name ,pass FROM go where name =  "."'".$name."'";
$result = $conn->query($sql);
echo json_encode(strval($result->num_rows));
if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "id: " . $row["name"]. " - Name: " . $row["pass"]. "<br>";
  }
} else {
}






}

?>