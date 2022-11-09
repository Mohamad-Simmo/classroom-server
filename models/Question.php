<?php 
Class Question {
  private $conn;
  private $table = 'questions';

  public $id;
  public $form_id;
  public $question;
  public $correct_choice_id;
  public $grade;

  public function __construct($db) {
    $this->conn = $db;
  }

  public function create() {
    $query = $this->conn->prepare(
      "INSERT INTO {$this->table} (form_id, question, grade) VALUES(?, ?, ?)"
    );

    $query->bind_param('isi', $this->form_id, $this->question, $this->grade);
    $query->execute();
    return $query->insert_id;
  }

  public function setCorrect() {
    $query = $this->conn->prepare(
      "UPDATE {$this->table} SET correct_choice_id=? WHERE id=?"
    );
    $query->bind_param('ii', $this->correct_choice_id, $this->id);
    $query->execute();
  }

  public function get($isTeacher) {
    if ($isTeacher) {
      $query = $this->conn->prepare(
        "SELECT id, question, correct_choice_id, grade FROM {$this->table}
        WHERE form_id = ?"
      );
    } else {
      $query = $this->conn->prepare(
        "SELECT id, question, grade FROM {$this->table}
        WHERE form_id = ?"
      );
    }
    $query->bind_param("i", $this->form_id);
    $query->execute();
    return $query->get_result();
  }
}




?>