<?php
Class Form {
  private $conn;
  private $table = 'forms';

  public $id;
  public $user_id;
  public $title;

  public function __construct($db) {
    $this->conn = $db;
  }
  
  public function create() {
    $query = $this->conn->prepare(
      "INSERT INTO {$this->table} (user_id, title) VALUES (?, ?)"
    );

    $query->bind_param("is", $this->user_id, $this->title);
    $query->execute();
    return $query->insert_id;
  }

  public function getSingle() {
    $query = $this->conn->prepare(
      "SELECT id, title FROM {$this->table} WHERE id = ?"
    );

    $query->bind_param("i", $this->id);

    $query->execute();
    return $query->get_result();
  }

  public function getAll() {
    $query = $this->conn->prepare(
      "SELECT id, title FROM {$this->table} WHERE user_id = ?"
    );

    $query->bind_param("i", $this->user_id);

    $query->execute();
    return $query->get_result();
  }

  public function delete() {
    $query = $this->conn->prepare(
      "DELETE FROM {$this->table} WHERE id = ?"
    );
    $query->bind_param("i", $this->id);
    if ($query->execute()) return true;
    return false;
  }
}

?>