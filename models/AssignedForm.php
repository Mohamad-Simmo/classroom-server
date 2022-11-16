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

  public function getAssigned($user_id) {
    $query = $this->conn->prepare(
      "SELECT forms.title, forms.id as form_id, {$this->table}.start_date_time, 
              {$this->table}.end_date_time, {$this->table}.type,
              {$this->table}.id as assigned_id
      FROM forms
      JOIN {$this->table} ON forms.id = {$this->table}.form_id
      WHERE {$this->table}.class_id = ? 
      AND {$this->table}.end_date_time > UTC_TIMESTAMP()
      AND {$this->table}.start_date_time <= UTC_TIMESTAMP()
      AND {$this->table}.id NOT IN (
        SELECT assign_id FROM form_submissions WHERE user_id = ?
      )"
    );
    $query->bind_param("ii", $this->class_id, $user_id);
    $query->execute();
    return $query->get_result();
  }

  public function getSingleAssigned() {
    $query = $this->conn->prepare(
      "SELECT {$this->table}.id, {$this->table}.form_id, {$this->table}.class_id, 
              {$this->table}.type, {$this->table}.start_date_time,
              {$this->table}.end_date_time, forms.title
      FROM {$this->table}
      JOIN forms ON forms.id = {$this->table}.form_id
      WHERE {$this->table}.id = ?"
    );
    $query->bind_param("i", $this->id);
    $query->execute();
    return $query->get_result();
  }


}



?>