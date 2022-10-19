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
    
    $class->user_id = $user_id;
    $class->id = htmlspecialchars($_GET["id"]);

    // Check if user id is in class
    $people->user_id = $user_id;
    $people->class_id = htmlspecialchars($_GET["id"]);

    if ($people->check()) {
      if ($response = $class->read_single()) {
        http_response_code(200);
        echo json_encode($response->fetch_assoc());
      }
    }
    else {
      throw new Exception("Unauthorized");
    }
    
  } catch (Exception $e) {
    http_response_code(401);
    echo json_encode(
      array('message' => $e->getMessage())
    );
  }



?>