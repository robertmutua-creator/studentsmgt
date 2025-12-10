<?php

namespace Studentsmgt\Models;

use PDO;
use PDOException;

class Students
{
  private $pdo;

  public function __construct()
  {
    $this->pdo = (new Database())->getConn();
  }

  // Create a new student
  public function create($adm_no, $name, $date_of_birth, $current_class_id = null, $school_id = null)
  {
    try {
      $sql = "INSERT INTO students (adm_no, name, date_of_birth, current_class_id, school_id) 
                    VALUES (:adm_no, :name, :date_of_birth, :current_class_id, :school_id)";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(':adm_no', $adm_no);
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':date_of_birth', $date_of_birth);
      $stmt->bindParam(':current_class_id', $current_class_id, PDO::PARAM_INT);
      $stmt->bindParam(':school_id', $school_id, PDO::PARAM_INT);
      return $stmt->execute();
    } catch (PDOException $e) {
      // Handle error appropriately, e.g., log it
      error_log("Create Student Error: " . $e->getMessage());
      return false;
    }
  }

  // Read a student by ID
  public function getById($id)
  {
    try {
      $sql = "SELECT * FROM students WHERE id = :id";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Get Student Error: " . $e->getMessage());
      return false;
    }
  }

  // Read all students for a given school, including class name
  public function getAllBySchool($school_id)
  {
    try {
      $sql = "
            SELECT 
                s.id,
                s.adm_no,
                s.name,
                s.date_of_birth,
                c.name AS class_name
            FROM students s
            LEFT JOIN classes c ON s.current_class_id = c.id
            WHERE s.school_id = :school_id
            ORDER BY s.name ASC
        ";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(':school_id', $school_id, PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Get Students By School Error: " . $e->getMessage());
      return false;
    }
  }

  // count by school
  public function countStudents($school_id)
  {
    $students = $this->getAllBySchool($school_id);
    return count($students);
  }

  // Update student info by ID
  public function update($id, $adm_no, $name, $date_of_birth, $current_class_id = null, $school_id = null)
  {
    try {
      $sql = "UPDATE students 
                    SET adm_no = :adm_no, name = :name, date_of_birth = :date_of_birth,
                        current_class_id = :current_class_id, school_id = :school_id
                    WHERE id = :id";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(':adm_no', $adm_no);
      $stmt->bindParam(':name', $name);
      $stmt->bindParam(':date_of_birth', $date_of_birth);
      $stmt->bindParam(':current_class_id', $current_class_id, PDO::PARAM_INT);
      $stmt->bindParam(':school_id', $school_id, PDO::PARAM_INT);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      return $stmt->execute();
    } catch (PDOException $e) {
      error_log("Update Student Error: " . $e->getMessage());
      return false;
    }
  }

  // Delete a student by ID
  public function delete($id)
  {
    try {
      $sql = "DELETE FROM students WHERE id = :id";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      return $stmt->execute();
    } catch (PDOException $e) {
      error_log("Delete Student Error: " . $e->getMessage());
      return false;
    }
  }

  // Count students by class
  public function countByClass($class_id)
  {
    try {
      $sql = "SELECT COUNT(*) AS total FROM students WHERE current_class_id = :class_id";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(':class_id', $class_id, \PDO::PARAM_INT);
      $stmt->execute();
      $result = $stmt->fetch(\PDO::FETCH_ASSOC);
      return (int) ($result['total'] ?? 0);
    } catch (PDOException $e) {
      error_log("Count Students By Class Error: " . $e->getMessage());
      return 0;
    }
  }

  // Get all students by class ID
  public function getByClass($class_id)
  {
    try {
      $sql = "
            SELECT s.id, s.adm_no, s.name, s.date_of_birth, c.name AS class_name
            FROM students s
            LEFT JOIN classes c ON s.current_class_id = c.id
            WHERE s.current_class_id = :class_id
            ORDER BY s.name ASC
        ";
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(':class_id', $class_id, \PDO::PARAM_INT);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Get Students By Class Error: " . $e->getMessage());
      return [];
    }
  }
}
