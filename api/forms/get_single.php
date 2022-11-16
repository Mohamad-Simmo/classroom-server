<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: GET');
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
    $form->id = $_GET["id"];
    $formResult = $form->getSingle()->fetch_object();

    $form->title = $formResult->title;

    // get questions and choices
    $questions = new Question($db);
    $questions->form_id = $form->id;
    $questionsResult = $questions->get($role === "teacher");
    $response = [];
    $response["title"] = $form->title;
    $questionsResponse = [];
    while ($row = $questionsResult->fetch_assoc()) {
      $current = [];

      // id, question, correct_choice_id, grade
      $current["id"] = $row["id"];
      $current["question"] = $row["question"];
      $current["correct_choice_id"] = $row["correct_choice_id"];
      $current["grade"] = $row["grade"];
      
      $choices = [];
      $c = new Choice($db);
      $c->question_id = $row["id"];
      $choicesResult = $c->get();
      while ($subRow = $choicesResult->fetch_assoc()) {
        $choices[] = $subRow;
      }
      $current["choices"] = $choices;

      $questionsResponse[] = $current;
    }
    $response["questions"] = $questionsResponse;

    http_response_code(200);
    echo json_encode($response);
  } catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["message" => $e->getMessage()]);
  }
?>