<?php
Class Form {
  private $conn;
  private $table = 'forms';

  public $id;
  public $user_id;
  public $class_id;
  public $title;

  public function __construct($db) {
    $this->conn = $db;
  }
  
  public function create() {
    try {
      $query = $this->conn->prepare(
        "INSERT INTO {$this->table} (user_id, class_id, title) VALUES (?, ?, ?)"
      );
  
      $query->bind_param("iis", $this->user_id, $this->class_id, $this->title);
      $query->execute();
      return $query->insert_id;
    } catch (Exception $e) {
      return $e->getMessage();
    }
  }
}


?>