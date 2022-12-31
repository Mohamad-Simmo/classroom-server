<?php
Class FormSubmissions {
  private $conn;
  private $table = "form_submissions";

  public $id;
  public $user_id;
  public $assign_id;
  public $date_time;
  public $grade;

  public function __construct($db){
    $this->conn = $db;
  }

  public function submit() {
    $query = $this->conn->prepare(
      "INSERT INTO {$this->table} (user_id, assign_id, grade)
      VALUES(?, ?, ?)"
    );

    $query->bind_param("iii", $this->user_id, $this->assign_id, $this->grade);
    $query->execute();
  }

  public function getGrades($role) {
    $queryString = '';
    if ($role === 'student') {
      $queryString = "SELECT form_submissions.id, form_submissions.date_time,
      form_submissions.grade, forms.title, assigned_forms.type, classes.name,
      classes.id as class_id
      from form_submissions
      JOIN assigned_forms ON assigned_forms.id = form_submissions.assign_id
      JOIN forms ON assigned_forms.form_id = forms.id
      JOIN classes ON assigned_forms.class_id = classes.id
      WHERE form_submissions.user_id = ?
      ORDER BY form_submissions.date_time DESC";

    } else if ($role === 'teacher') {
      $queryString = "SELECT DISTINCT form_submissions.id, form_submissions.date_time,
      form_submissions.grade, assigned_forms.type, classes.name,
      CONCAT(users.fname, ' ', users.lname) AS full_name, forms.title, classes.id as class_id
      FROM form_submissions
      JOIN assigned_forms ON assigned_forms.id = form_submissions.assign_id
      JOIN forms ON assigned_forms.form_id = forms.id
      JOIN classes ON classes.id = assigned_forms.class_id
      JOIN users ON users.id = form_submissions.user_id
      JOIN users_classes ON users_classes.class_id = classes.id
      WHERE users_classes.class_id IN (
        SELECT users_classes.class_id
        FROM users_classes 
        WHERE users_classes.user_id = ?
      )
      ORDER BY form_submissions.date_time DESC";
    }

    $query = $this->conn->prepare($queryString);
    $query->bind_param("i", $this->user_id);
    $query->execute();
    return $query->get_result();
  }
}

?>

