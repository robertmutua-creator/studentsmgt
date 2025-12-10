<?php

namespace Studentsmgt\Models;

use PDO;
use PDOException;
use Studentsmgt\Models\Database;

class Schools
{
  private $pdo;

  public function __construct()
  {
    $this->pdo = (new Database())->getConn();
  }

  // Create a new school
  public function create($name, $address)
  {
    $sql = "INSERT INTO schools (name, address) VALUES (:name, :address)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':address', $address);
    return $stmt->execute() ? $this->pdo->lastInsertId() : false;
  }

  // Get all schools
  public function getAll()
  {
    $sql = "SELECT * FROM schools ORDER BY id DESC";
    $stmt = $this->pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Get a school by ID
  public function getById($id)
  {
    $sql = "SELECT * FROM schools WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  // Update a school
  public function update($id, $name, $address)
  {
    $sql = "UPDATE schools SET name = :name, address = :address WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
  }

  // Delete a school
  public function delete($id)
  {
    $sql = "DELETE FROM schools WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
  }
}
