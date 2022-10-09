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
  require_once '../../models/Class.php';
  require_once '../../models/People.php';

  // Connect db
  $database = new Database();
  $db = $database->connect();

  $class = new _Class($db);
  $people = new People($db);

  try {
    // Protect route
    require '../../config/protect.php';

    // Get posted data
    $data = json_decode(file_get_contents("php://input"));
    
    $class->user_id = $user_id;
    $class->name = $data->name;
    $class->description = $data->description;

    if ($insertID = $class->create()) {
      // Add teacher to class
      $people->user_id = $user_id;
      $people->class_id = $insertID;
      $people->add();

      http_response_code(201);
      echo json_encode(
        array('message' => 'Class Created')
      );
    }
  }
  catch (Exception $e) {
    http_response_code(400);
    echo json_encode(
      array('message' => $e->getMessage())
    );
  }

?>