<?php

namespace Studentsmgt\Models;

use PDO;
use PDOException;

class Database
{
  private $host = '127.0.0.1';
  private $db_name = 'studentsmgt';
  private $username = 'appuser';
  private $password = 'yourpassword';

  private $conn = null;

  public function getConn()
  {
    if ($this->conn === null) {
      $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";

      try {
        $this->conn = new PDO($dsn, $this->username, $this->password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
      }
    }

    return $this->conn;
  }
}
