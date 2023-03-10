
<?php


session_start();
$PERSON_ID = $_SESSION['PERSON_ID'];
include_once '../Database.php';
// Instantiate DB & connect
$database = new Database();
$conn = $database->connect();

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
if ($_POST["opreation"] == "onOff") {
  $sql =
    "UPDATE likee SET `LIKE_ONOFF`=" . $_POST["onOff"] . " WHERE LIKE_PERSON_ID=" . $PERSON_ID . " &&LIKE_AD_ID=" . $_POST["id"];
  if ($conn->query($sql) === TRUE) {
    echo json_encode("ok");
  } else {
    echo "Error updating record: " . $conn->error;
  }
} else if ($_POST["opreation"] == "setcom") {
  $sql2 = "INSERT INTO comment (COMMENT_ID,COMMENT_DESCRBTION,COMMENT_PERSON_ID,COMMENT_AD_ID) VALUES (default,?,?,?)";
  $stmt = $conn->prepare($sql2);
  $stmt->bind_param("sii", $_POST["comment"], $PERSON_ID, $_POST["id"]);
  $stmt->execute();

  echo json_encode("ok");
} else if ($_POST["opreation"] == "getcomment") {
  $sql = "SELECT comment.COMMENT_DESCRBTION, person.PERSON_NAME FROM comment INNER JOIN person ON comment.COMMENT_PERSON_ID=person.PERSON_ID
WHERE comment.COMMENT_AD_ID=" . $_POST["AD_ID"];
  $count = 0;
  $result = $conn->query($sql);
  $rows = array();
  if ($result->num_rows > 0) {
    // output data of each row (PRI, int)
    while ($row = $result->fetch_assoc()) {
      $rows[$count++] = $row["COMMENT_DESCRBTION"];
      $rows[$count++] = $row["PERSON_NAME"];
    }
  } else {
    echo "0 results";
  }

  echo ('["' . implode('", "', $rows) . '"]');
}

?>