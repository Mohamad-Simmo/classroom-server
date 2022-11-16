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
  require_once '../../models/Section.php';
  require_once '../../models/Material.php';

  $database = new Database();
  $db = $database->connect();

  $section = new Section($db);

  try {
    require '../../config/protect.php';

    $data = json_decode(file_get_contents("php://input"));

    $section->class_id = $data->class_id;
    $section->title = $data->title;

    $insertID = $section->create();

    http_response_code(201);
    
    echo json_encode(["id" => $insertID, "title" => $section->title, 
    "material"=> []]); 

  } catch (Exception $e) {
    http_response_code(401);
    echo json_encode(
      array('message' => $e->getMessage())
    );
  }

?>