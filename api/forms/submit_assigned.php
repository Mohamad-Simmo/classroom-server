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

    // Calculate Grade
    $questions = new Question($db);
    $questions->form_id = $data->form_id;
    $questionsResult = $questions->get(true);
    $questionsArr = $questionsResult->fetch_all(MYSQLI_ASSOC);

    $grade = 0;
    for ($i = 0; $i < sizeof($questionsArr); $i++) {
      $q =  $questionsArr[$i];
      $ans = $answers[$i];

      if ($q["correct_choice_id"] === $ans->choice_id) {
        $grade += $q["grade"];
      }
    }

    $submitAssigned->grade = $grade;
    $submitAssigned->submit();
    http_response_code(201);

  } catch (Exception $e) {

  }


?>