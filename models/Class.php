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

  public function read_single() {
    $query = $this->conn->prepare(
      "SELECT CONCAT(users.fname,' ', users.lname) AS full_name,
      classes.id, classes.name, 
      classes.description, classes.code, COUNT(*) as num_people, classes.archived
      FROM classes 
      JOIN users ON classes.user_id = users.id
      JOIN users_classes ON users_classes.class_id = classes.id 
      WHERE classes.id = ?
      HAVING num_people > 0
      LIMIT 1;"
    );

    $query->bind_param('i', $this->id);
    $query->execute();
    $response = $query->get_result();

    if ($response->num_rows > 0) {
      return $response;
    } else return false;

  }

  public function read() {
    $query = $this->conn->prepare(
      "SELECT CONCAT(users.fname,' ', users.lname) AS full_name,
              classes.id, classes.name, 
              classes.description, classes.code, COUNT(*) as num_people,
              classes.archived
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
    $query->store_result();

    if ($query->affected_rows > 0) {
      return true;
    }
    return false;
  }

  public function update() {
    $query = $this->conn->prepare(
      "UPDATE classes SET name = ?, description = ? WHERE id = ?"
    );

    $query->bind_param("ssi", $this->name, $this->description, $this->id);
    $query->execute();

    if ($query->affected_rows > 0) {
      return true;
    }
    return false;
  }

  public function archive() {
    $query = $this->conn->prepare(
      "UPDATE classes SET archived = ? WHERE id = ?"
    );

    $query->bind_param("ii", $this->archived, $this->id);
    $query->execute();

    if ($query->affected_rows > 0) {
      return true;
    }
    return false;
  }

}
?>