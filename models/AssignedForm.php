<?php
Class AssignedForm {
  private $conn;
  private $table = 'assigned_forms';

  public $id;
  public $form_id;
  public $class_id;
  public $type;
  public $start_date_time;
  public $end_date_time;

  public function __construct($db) {
    $this->conn = $db;
  }

  public function assign() {
    $query = $this->conn->prepare(
      "INSERT INTO {$this->table} 
      (form_id, class_id, type, start_date_time, end_date_time) 
      VALUES (?, ?, ?, ?, ?)"
    );

    $query->bind_param(
      "iisss",
      $this->form_id,
      $this->class_id,
      $this->type,
      $this->start_date_time,
      $this->end_date_time
    );
    

    if ($query->execute()) {
      return true;
    }
    return false;
  }

}



?>