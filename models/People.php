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

  public function add_email($email) {
    $query = $this->conn->prepare(
      "INSERT INTO users_classes VALUES((SELECT users.id
      FROM users
      WHERE users.email = ?), ?)"
    );

    $query->bind_param("si", $email, $this->class_id);
    if ($query->execute()) {
      return true;
    }
    return false;
  }

  public function join_class($code) {
    $query = $this->conn->prepare(
      "INSERT INTO users_classes VALUES (?, (SELECT id FROM classes WHERE code = ?))"
    );
    $query->bind_param("is", $this->user_id, $code);
    $query->execute();
    if ($query->affected_rows > 0) {
      return true;
    }
    return false;
  }

  public function get() {
    $query = $this->conn->prepare(
      "SELECT users.fname, users.lname, users.email, users.role
      FROM users
      JOIN users_classes ON users_classes.user_id = users.id
      JOIN classes ON users_classes.class_id = classes.id
      WHERE classes.id = ?
      ORDER BY users.role"
    );

    $query->bind_param("i", $this->class_id);
    if($query->execute()) {
      return $query->get_result();
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