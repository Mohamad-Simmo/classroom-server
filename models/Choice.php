<?php
Class Choice {
  private $conn;
  private $table = 'choices';

  public $id;
  public $question_id;
  public $choice;
  
  public function __construct($db) {
    $this->conn = $db;
  }

  public function create() {
    $query = $this->conn->prepare(
      "INSERT INTO {$this->table} (question_id, choice) VALUES(?, ?)"
    );

    $query->bind_param("is", $this->question_id, $this->choice);
    $query->execute();
    return $query->insert_id;
  }

  public function get() {
    $query = $this->conn->prepare(
      "SELECT id, choice FROM {$this->table} WHERE question_id = ?"
    );
    $query->bind_param("i", $this->question_id);
    $query->execute();
    return $query->get_result();
  }
}


?>