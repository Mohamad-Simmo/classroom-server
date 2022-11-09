<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit('ok');
  }

  require_once '../../config/Database.php';
  require_once '../../models/FormSubmissions.php';
  require_once '../../models/AssignedForm.php';
  require_once '../../models/Question.php';

  $database = new Database();
  $db = $database->connect();

  $submitAssigned = new FormSubmissions($db);

  try {
    require '../../config/protect.php';
    $data = json_decode(file_get_contents("php://input"));

    $submitAssigned->user_id = $user_id;
    $submitAssigned->form_id = $data->form_id;
    $submitAssigned->assign_id = $data->assign_id;
    
    $answers = $data->answers;

    var_dump($answers);
    
    // Calculate Grade
    $questions = new Question($db);
    $questions->form_id = $data->form_id;
    $questionsResult = $questions->get(true);
    $questionsArr = [];
    while ($row = $questionsResult->fetch_assoc()) {
      $questionsArr[] = $row;
    }

    var_dump($questionsArr);

    $grade = 0;
    
    foreach ($answers as $answer) {

    }




  } catch (Exception $e) {

  }


?>