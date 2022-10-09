<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: GET');
  header('Access-Control-Allow-Headers: Accept, Origin, Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit('ok');
  }

  try {
    // Protect route
    require '../../../config/protect.php';

    http_response_code(200);
    echo json_encode([
      "role" => $role
    ]);

  }
  catch (Exception $e){
    http_response_code(400);
    echo json_encode([
      'message'=> $e->getMessage()
    ]);
  }

?>