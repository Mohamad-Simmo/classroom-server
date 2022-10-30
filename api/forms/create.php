<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
  if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit('ok');
  }

  require_once '../../config/Database.php';
  require_once '../../models/Form.php';
  require_once '../../models/Question.php';
  require_once '../../models/Choice.php';

  $database = new Database();
  $db = $database->connect();

  $form = new Form($db);

  try {
    require '../../config/protect.php';
    $data = json_decode(file_get_contents("php://input"));
    $questions = $data->questions;

    $form->user_id = $user_id;
    $form->class_id = $data->class_id;
    $form->title = $data->title;

    $form->id = $form->create();

    foreach ($questions as $question) {
      $correctIndex = $question->correctChoiceIndex;

      // insert question
      $q = new Question($db);
      $q->form_id = $form->id;
      $q->question = $question->question;
      $q->grade = $question->grade;
      $q->id = $q->create();

      $choiceIndex = 0;
      foreach ($question->choices as $choice) {
        // insert choice , if correct choice update question->correct choice id
        $c = new Choice($db);
        $c->question_id = $q->id;
        $c->choice = $choice;
        $c->id = $c->create();

        if ($correctIndex === $choiceIndex) {
          $q->correct_choice_id = $c->id;
          $q->setCorrect();
        }
        $choiceIndex++;
      }
    }

    http_response_code(201);
    
    
  } catch (Exception $e) {
    http_response_code(401);
    echo json_encode(
      array('message' => $e->getMessage())
    );
  }


?>