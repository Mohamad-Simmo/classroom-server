<?php
Class Material {
  private $conn;
  private $table ="material";

  public $id;
  public $section_id;
  public $file_name;
  public $url;
  public $size;
  public $type;

  public function __construct($db) {
    $this->conn = $db;
  }

  public function create() {
    $query = $this->conn->prepare(
      "INSERT INTO {$this->table} (section_id, file_name, url, size, type)
      VALUES(?, ?, ?, ?, ?)"
    );
    $query->bind_param(
      "issis", $this->section_id, $this->file_name, $this->url, $this->size, $this->type
    );

    $query->execute();
  }

  public function get() {
    $query = $this->conn->prepare(
      "SELECT file_name, url
      FROM {$this->table} 
      WHERE section_id = ?"
    );
    $query->bind_param("i", $this->section_id);

    $query->execute();
    return $query->get_result();
  }
}


?>