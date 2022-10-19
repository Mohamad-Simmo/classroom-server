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
    return false;
  }

  public function check() {
    $query = $this->conn->prepare(
      "SELECT * FROM {$this->table} WHERE user_id = ? AND class_id = ?"
    );

    $query->bind_param("ii", $this->user_id, $this->class_id);
    $query->execute();
    $query->store_result();

    if ($query->num_rows > 0) {
      return true;
    }
    return false;
  }


}


?>