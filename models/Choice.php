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
}


?>