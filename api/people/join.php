<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit('ok');
  }

  require_once '../../config/Database.php';
  require_once '../../models/People.php';

  // Connect db
  $database = new Database();
  $db = $database->connect();

  $people = new People($db);

  try {
    require '../../config/protect.php';
    $data = json_decode(file_get_contents("php://input"));

    $people->user_id = $user_id;
    
    if ($people->join_class($data->code)) {
      http_response_code(201);
      echo "succes";
    }
  } catch (Exception $e) {
    http_response_code(400);
    echo json_encode(
      array('message' => $e->getMessage())
    );
  }
?>