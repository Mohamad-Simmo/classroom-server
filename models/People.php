<?php 
Class People {
  private $conn;
  private $table = 'users_classes';

  public $user_id;
  public $class_id;

  public function __construct($db) {
    $this->conn = $db;
  }

  public function add() {
    $query = $this->conn->prepare(
      "INSERT INTO {$this->table} VALUES(?, ?)"
    );

    $query->bind_param("ii", $this->user_id, $this->class_id);
    
    if ($query->execute()) {
      return true;
    }
    
    throw new Exception("Add failed");
  }

}


?>