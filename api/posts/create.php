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
  require_once '../../models/Post.php';

  // Connect db
  $database = new Database();
  $db = $database->connect();

  $post = new Post($db);

  try {
    // Protect route
    require '../../config/protect.php';

    // Get posted data
    $data = json_decode(file_get_contents("php://input"));

    $post->user_id = $user_id;
    $post->class_id = $data->class_id;
    $post->body = $data->body;

    if ($post->create()) {
      http_response_code(201);
      echo json_encode(
        array('message' => 'Post Created')
      );
    } else {
      throw new Exception("Post Failed");
    }

  } catch (Exception $e) {
    http_response_code(400);
    echo json_encode(
      array('message' => $e->getMessage())
    );
  }
?>