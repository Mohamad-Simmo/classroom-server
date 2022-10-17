<?php 
Class Post {
  private $conn;
  private $table = 'posts';

  public $id;
  public $user_id;
  public $class_id;
  public $body;
  public $timestamp;

  public function __construct($db) {
    $this->conn = $db;
  }

  public function create() {
    $query = $this->conn->prepare(
      "INSERT INTO {$this->table} (user_id, class_id, body) VALUES (?, ?, ?)"
    );

    $query->bind_param("iis", $this->user_id, $this->class_id, $this->body);

    if ($query->execute()) {
      return true;
    } else {
      return false;
    }
  }

  public function get() {
    $query = $this->conn->prepare(
      "SELECT users.id as user_id, CONCAT(users.fname,' ', users.lname) AS full_name,
      posts.id, posts.body, posts.timestamp
      FROM {$this->table}
      JOIN users ON posts.user_id = users.id
      WHERE posts.class_id = ? 
      ORDER BY posts.timestamp DESC"
    );

    $query->bind_param("i", $this->class_id);
    if ($query->execute()) {
      $result = $query->get_result();
      return $result;
    }
  }

  public function update() {
  }

  public function delete() {
  }

}
?>