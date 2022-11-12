<?php
Class Section {
  private $conn;
  private $table = "sections";

  public $id;
  public $class_id;
  public $title;

  public function __construct($db) {
    $this->conn = $db;
  }


  public function create() {
    $query = $this->conn->prepare(
      "INSERT INTO {$this->table} (class_id, title) VALUES (?, ?)"
    );

    $query->bind_param("is", $this->class_id, $this->title);
    $query->execute();
    return $query->insert_id;
  }

  public function get() {
    $query = $this->conn->prepare(
      "SELECT {$this->table}.id, {$this->table}.title 
      FROM {$this->table}
      WHERE class_id = ? 
      ORDER BY id DESC"
    );

    $query->bind_param("i", $this->class_id);
    $query->execute();
    return $query->get_result();
  }

  public function delete() {
    $query = $this->conn->prepare(
      "DELETE FROM {$this->table} WHERE id = ?"
    );
    $query->bind_param("i", $this->id);
    $query->execute();
  }
}



?>