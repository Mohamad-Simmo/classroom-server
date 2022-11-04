<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: DELETE');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit('ok');
  }

  require_once '../../config/Database.php';
  require_once '../../models/Form.php';

  // Connect db
  $database = new Database();
  $db = $database->connect();

  $form = new Form($db);

  try {
    require '../../config/protect.php';
    $data = json_decode(file_get_contents("php://input"));
    $form->id = $data->form_id;

    $form->delete();

    http_response_code(204);

  } catch (Exception $e) {
    http_response_code(401);
    echo $e->getMessage();
  }
?>