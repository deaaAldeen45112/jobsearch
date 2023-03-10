
<?php
session_start();
$PERSON_ID = $_SESSION['PERSON_ID'];


$opreation = trim($_POST["opreation"]);
include_once '../Database.php';
// Instantiate DB & connect
$database = new Database();
$conn = $database->connect();

# code...
if ($opreation == "select") {

  $item = $_POST["item"];

  $sql = "SELECT * FROM ad WHERE ad_collage_id =" . $item;
  $count = 0;
  $result = $conn->query($sql);
  $rows = array();
  if ($result->num_rows > 0) {
    // output data of each row (PRI, int)
    while ($row = $result->fetch_assoc()) {
      $rows[$count++] = $row["AD_ID"];
      $rows[$count++] = $row["AD_DESCRBTION"];
    }
  } else {
    echo "0 results";
  }

  echo json_encode($rows);
}

if ($opreation == "selectwithlike") {
  // 1 45 85  0
  // 2 45 120 1

  $item = $_POST["item"];
  $sql1 = "SELECT * FROM likee inner join ad on likee.LIKE_AD_ID =ad.AD_ID WHERE LIKE_PERSON_ID=" . $PERSON_ID . "&& ad_collage_id =" . $item;
  $count1 = 0;
  $rows1 = array();
  $result1 = $conn->query($sql1);
  if ($result1->num_rows > 0) {
    // output data of each row (PRI, int)
    while ($row = $result1->fetch_assoc()) {

      $rows1[$count1++] = $row["LIKE_ONOFF"];
    }
  } else {
    echo "0 results";
  }

  //  85  jewjisf   0  
  // 120 sjdfagjoa 1
  $sql = "SELECT * FROM ad WHERE ad_collage_id =" . $item;
  $count = 0;
  $x = 0;
  $result = $conn->query($sql);
  $rows = array();
  if ($result->num_rows > 0) {
    // output data of each row (PRI, int)
    while ($row = $result->fetch_assoc()) {
      $rows[$count++] = $row["AD_ID"];
      $rows[$count++] = $row["AD_DESCRBTION"];
      $rows[$count++] = $rows1[$x++];
    }
  }

  // 1  sakhdfsdiafhisdufhisdhf 0
  // 2 lksjglsadjglsdgjlsdjglej 1
  else {
    echo "0 results";
  }

  echo json_encode($rows);
}


if ($opreation == "editAds") {
  $ad = $_POST["ad"];
  $AD_ID = $_POST["AD_ID"];
  $sql = "UPDATE `ad` SET `AD_DESCRBTION`='" . $ad . "' WHERE AD_ID=" . $AD_ID;
  if ($conn->query($sql) === TRUE) {
    echo json_encode("ok");
  } else {
    echo "Error updating record: " . $conn->error;
  }
}

$sqlSel = "SELECT AD_ID FROM ad ";
$result = $conn->query($sqlSel);

$rows = array();

for ($i = 0; $i < count($rows); $i++) {
  $sql2 = "INSERT INTO likee (LIKE_ID,LIKE_ONOFF,LIKE_PERSON_ID,LIKE_AD_ID) VALUES (default,0,?,?)";
  $stmt = $conn->prepare($sql2);
  $stmt->bind_param("ii", $last_id, $rows[$i]);
  $stmt->execute();
}

if ($opreation == "insert") {
  $sqlSel = "SELECT PERSON_ID FROM person ";
  $result = $conn->query($sqlSel);
  $rows = array();
  $count = 0;
  if ($result->num_rows > 0) {
    // output data of each row (PRI, int)
    while ($row = $result->fetch_assoc()) {
      $rows[$count++] = $row["PERSON_ID"];
    }
  }

  $ad = $_POST["ad"];
  $item = $_POST["item"];
  $sql1 = "INSERT INTO ad (ad_collage_id,AD_DESCRBTION,AD_ID,AD_PERSON_ID ) VALUES (?,?,default,?)";
  $stmt = $conn->prepare($sql1);
  $stmt->bind_param("isi", $item, $ad, $PERSON_ID);
  $stmt->execute();
  $last_id = $conn->insert_id;
  for ($i = 0; $i < count($rows); $i++) {


    $sql2 = "INSERT INTO likee (LIKE_ID,LIKE_ONOFF,LIKE_PERSON_ID,LIKE_AD_ID) VALUES (default,0,?,?)";
    $stmt = $conn->prepare($sql2);
    $stmt->bind_param("ii", $rows[$i], $last_id);
    $stmt->execute();
  }
  echo json_encode($last_id);
}
if ($opreation == "ListDropDown") {

  $sql = "SELECT * FROM collage ";

  $result = $conn->query($sql);
  $rows = array();
  $count = 0;
  if ($result->num_rows > 0) {
    // output data of each row (PRI, int)
    while ($row = $result->fetch_assoc()) {
      $rows[$count++] = $row["COLLAGE_ID"];
      $rows[$count++] = $row["COLLAGE_NAME"];
      $rows[$count++] = $row["COLLAGE_PHOTO"];
    }
  } else {
    echo "0 results";
  }


  echo ('["' . implode('", "', $rows) . '"]');
}
if ($opreation == "delete") {
  $AD_ID = $_POST["id"];
  $deleteCommetns = "DELETE FROM comment WHERE COMMENT_AD_ID=" . $AD_ID;
  $deleteLike = "DELETE FROM likee WHERE LIKE_AD_ID=" . $AD_ID;

  $sql = "DELETE FROM ad WHERE AD_ID=" . $AD_ID;

  if ($conn->query($deleteCommetns) === TRUE) {

    echo json_encode("ok");
  } else {
    echo "Error deleting record: " . $conn->error;
  }

  if ($conn->query($deleteLike) === TRUE) {

    echo json_encode("ok");
  } else {
    echo "Error deleting record: " . $conn->error;
  }





  if ($conn->query($sql) === TRUE) {

    echo json_encode("ok");
  } else {
    echo "Error deleting record: " . $conn->error;
  }
}

?>