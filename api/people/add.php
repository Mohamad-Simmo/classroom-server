<?php
  // Headers
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

    $emails = explode(',', $data->emails);
    $people->class_id = $data->class_id;

    $response = [];
    foreach ($emails as $email) {
      try {
        $response += [$email => $people->add_email($email)];
      } catch (Exception $e) {
        $response += [$email => false];
      }
    }
    http_response_code(201);
    echo json_encode($response);

  } catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["message" => "Unauthorized"]);
  }

?>