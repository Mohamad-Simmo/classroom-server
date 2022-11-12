<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: GET');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit('ok');
  }

  require_once '../../config/Database.php';
  require_once '../../models/Section.php';
  require_once '../../models/Material.php';

  $database = new Database();
  $db = $database->connect();

  $section = new Section($db);

  try {
    require '../../config/protect.php';

    $section->class_id = $_GET["class_id"];

    $result = $section->get();


    $response = [];
    while ($row = $result->fetch_assoc()) {
      $current = [];
      $current["id"] = $row["id"];
      $current["title"] = $row["title"];

      $material = new Material($db);
      $material->section_id = $row["id"];
      $materialRes = $material->get();

      $materialArr = [];
      while ($subRow = $materialRes->fetch_assoc()) {
        $materialArr[] = $subRow;
      }
      $current["material"] = $materialArr;
      $response[] = $current;
    }
    
    echo json_encode($response);

  } catch (Exception $e) {

  }


?>