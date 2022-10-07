<?php 
class User {
  private $conn;
  private $table = 'users';

  // User props
  public $id;
  public $role;
  public $school_id;
  public $fname;
  public $lname;
  public $email;
  public $password;

  public function __construct($db) {
    $this->conn = $db;
  }

  // Create user
  public function register() {

    // Check if email is taken
    $query = $this->conn->prepare(
      "SELECT email FROM {$this->table} WHERE email = ?"
    );

    // Clean email
    $this->email = htmlspecialchars(strip_tags($this->email));

    // Bind email and execute
    $query->bind_param("s", $this->email);
    
    $query->execute();
    $query->store_result();

    if ($query->num_rows > 0) {
      throw new Exception("Email already exists");
    }

    // Create query and prepare
    $query = $this->conn->prepare(
      "INSERT INTO {$this->table} VALUES(NULL,'user', NULL, ?, ?, ?, ?)"
    );

    // Clean rest of data
    $this->fname = htmlspecialchars(strip_tags($this->fname));
    $this->lname = htmlspecialchars(strip_tags($this->lname));
    $this->email = htmlspecialchars(strip_tags($this->email));
    $this->password = htmlspecialchars(strip_tags($this->password));

    // Hash password
    $this->password = password_hash($this->password, PASSWORD_BCRYPT);

    // Bind data
    $query->bind_param("ssss", $this->fname, $this->lname, $this->email, $this->password);

    // Execute Query
    if ($query->execute()) {
      return $query->insert_id;
    }

    printf("Error : %s.\n", $this->conn->error);
    return false;
  }

}
?>