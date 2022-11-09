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
  require_once '../../models/AssignedForm.php';
  require_once '../../models/Question.php';
  require_once '../../models/Choice.php';

  $database = new Database();
  $db = $database->connect();

  $assignedForm = new AssignedForm($db);

  $response = [];
  try {
    require '../../config/protect.php';

    $assignedForm->id = $_GET["assign_id"];
    $result = ($assignedForm->getSingleAssigned())->fetch_assoc();
    $response = $result;
    
    $questionsObj = new Question($db);
    $questionsObj->form_id = $result["form_id"];

    $questionsResult = $questionsObj->get($role === "teacher");

    $questionsResponse = [];
    while ($row = $questionsResult->fetch_assoc()) {
      $current = [];
      $current["id"] = $row["id"];
      $current["question"] = $row["question"];
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
    echo $e->getMessage();
  }
?>
