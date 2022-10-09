<?php
Class Database {
  private $host = 'localhost';
  private $user = 'root';
  private $password = '';
  private $db = 'classroom';
  private $conn;

  public function connect() {
    $this->conn = null;

    try {
      $this->conn = new mysqli($this->host,$this->user , $this->password, $this->db);
      
    } catch (Exception $e) {
      echo 'Connection error: ' . $e->getMessage();
    }

    return $this->conn;
  }
}
?>