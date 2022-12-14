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
  require_once '../../models/FormSubmissions.php';

  // Connect db
  $database = new Database();
  $db = $database->connect();

  $grades = new FormSubmissions($db);

  require '../../config/protect.php';

  $grades->user_id = $user_id;

  $result = ($grades->getGrades($role))->fetch_all(MYSQLI_ASSOC);
  echo json_encode($result);

    // Get all grades for all classes
    // if teacher -> all grades for every class

?>