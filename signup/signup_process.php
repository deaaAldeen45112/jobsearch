
<?php




	if($_POST["name"] != ''&& $_POST["collageName"]!=''&& $_POST["email"]!=''&& $_POST["pass"]!='')
	{
		
$name=$_POST["name"];
$collageName=$_POST["collageName"];
$email=$_POST["email"];
$pass=$_POST["pass"];

include_once '../Database.php';
// Instantiate DB & connect
$database = new Database();
$conn = $database->connect();
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

/*
while($row = $result->fetch_assoc()) {
    echo "id: " . $row["name"]. " - Name: " . $row["pass"]. "<br>";
  }
*/

$sql1 = "INSERT INTO person (PERSON_NAME,PERSON_PASS,PERSON_EMAIL,PERSON_COLLAGE,PERSON_TYPE_ACSESS) VALUES (?,?,?,?,'USER')";
$stmt= $conn->prepare($sql1);
$stmt->bind_param("ssss",$name,$pass,$email,$collageName);
$stmt->execute();
$last_id = $conn->insert_id;

$sqlSel="SELECT AD_ID FROM ad ";
$result=$conn->query($sqlSel);

$rows=array();
$count=0;
if ($result->num_rows > 0) {
  // output data of each row (PRI, int)
  while($row = $result->fetch_assoc()) {
   $rows[$count++]=$row["AD_ID"];
  }
} 
for ($i=0; $i <count($rows) ; $i++) { 
$sql2 = "INSERT INTO likee (LIKE_ID,LIKE_ONOFF,LIKE_PERSON_ID,LIKE_AD_ID) VALUES (default,0,?,?)";
$stmt= $conn->prepare($sql2);
$stmt->bind_param("ii",$last_id,$rows[$i]);
$stmt->execute();
}



echo json_encode(strval($result->num_rows));


}



?>