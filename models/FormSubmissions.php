<?php
Class FormSubmissions {
  private $conn;
  private $table = "form_submissions";

  public $id;
  public $user_id;
  public $assign_id;
  public $date_time;
  public $grade;

  public function __construct($db){
    $this->conn = $db;
  }

  public function submit() {
    $query = $this->conn->prepare(
      "INSERT INTO {$this->table} (user_id, assign_id, grade)
      VALUES(?, ?, ?)"
    );

    $query->bind_param("iii", $this->user_id, $this->assign_id, $this->grade);
    $query->execute();
  }
}


?>