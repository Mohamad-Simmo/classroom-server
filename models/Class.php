<?php 
Class _Class {
  private $conn;
  private $table = 'classes';

  public $id;
  public $user_id;
  public $name;
  public $description;
  public $code;
  public $archived;

  public function __construct($db) {
    $this->conn = $db;
    $this->code = substr(md5(uniqid()),0,13);
    $this->archived = 0;
  }

  public function create() {
    // Create class
    $query = $this->conn->prepare(
      "INSERT INTO {$this->table} VALUES(NULL, ?, ?, ?, ?, ?)"
    );

    $query->bind_param(
      "isssi", $this->user_id, $this->name, $this->description, $this->code, $this->archived
    );

    if ($query->execute()) {
      return $query->insert_id;
    } else {
      throw new Exception("Class creation failed");
    }
  }

  public function read() { //CONCAT( firstname, " ", lastname ) AS fullname 
    $query = $this->conn->prepare(
      "SELECT CONCAT(users.fname,' ', users.lname) AS full_name,
              classes.id, classes.name, 
              classes.description, classes.code, COUNT(*) as num_people
      FROM {$this->table}
      JOIN users_classes ON users_classes.class_id = classes.id 
      AND (
        classes.user_id = ?
        OR users_classes.class_id IN (
          SELECT users_classes.class_id 
          FROM users_classes 
          WHERE users_classes.user_id = ?
        )
      )
      JOIN users ON users.id = classes.user_id
      GROUP BY classes.code ASC"
    );

    $query->bind_param('ii', $this->user_id, $this->user_id );

    if ($query->execute()) {
      $result = $query->get_result();
      return $result;
    }
    throw new Exception("Network error");
  }

  public function delete() {
    $query = $this->conn->prepare(
      "DELETE FROM {$this->table} WHERE id = ? AND user_id = ?"
    );

    $query->bind_param("ii", $this->id, $this->user_id);
    $query->execute();

    if ($query->affected_rows > 0) {
      return true;
    }
    return false;
  }

  public function update() {
  }

}
?>