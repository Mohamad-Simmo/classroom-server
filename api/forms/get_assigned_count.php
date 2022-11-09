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
    $result = $assignedForm->getCount();

    http_response_code(200);
    echo json_encode($result->fetch_assoc());

  } catch (Exception $e) {
    echo $e->getMessage();
  }

?>