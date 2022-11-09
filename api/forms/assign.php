<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
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
    $data = json_decode(file_get_contents("php://input"));
    $assignedForm->form_id = $data->form_id;
    $assignedForm->class_id = $data->class_id;
    $assignedForm->type = $data->type;
    $assignedForm->start_date_time = $data->start;
    $assignedForm->end_date_time = $data->end;

    $assignedForm->assign();
    http_response_code(201);

  } catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["message" => $e->getMessage()]);
  }

?>