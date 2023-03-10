
<?php




	if( $_POST["email"]!=''&& $_POST["pass"]!='')
	{
		

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



$sql = "SELECT * FROM person;";
$result = $conn->query($sql);
$isUserEixist=false;
$typeAccess="";
$PERSON_ID=-1;
if ($result->num_rows > 0) {
  // output data of each row

while($row = $result->fetch_assoc()) {
 

       if($row["PERSON_PASS"]==$pass&&$row["PERSON_EMAIL"]==$email){ 
        
           $isUserEixist=true; 
           $typeAccess=$row["PERSON_TYPE_ACSESS"];
           $PERSON_ID=$row["PERSON_ID"];
           session_start();
           $_SESSION['PERSON_ID']=$PERSON_ID;

  }
  
}
} 


if($isUserEixist){



echo json_encode(trim($typeAccess));




}

else {

echo json_encode(trim("no"));


}








}



?>