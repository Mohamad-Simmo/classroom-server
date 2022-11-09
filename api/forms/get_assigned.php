<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: GET');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit('ok');
  }

  require_once '../../config/Database.php';
  require_once '../../models/AssignedForm.php';

  $database = new Database();
  $db = $database->connect();

  $assignedForm = new AssignedForm($db);

  try {
    require '../../config/protect.php';
    $assignedForm->class_id = $_GET["class_id"];

    $result = $assignedForm->getAssigned();
    $response = [];
    while ($row = $result->fetch_assoc()) {
      $response[] = $row;
    }

    http_response_code(200);
    echo json_encode($response);
  } catch (Exception $e) {

  }
?>