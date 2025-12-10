<?php

namespace Studentsmgt\Models;

use PDO;
use PDOException;

class Classes
{
  private $pdo;

  public function __construct()
  {
    $this->pdo = (new Database())->getConn();
  }

  // Create a new class
  public function create($school_id, $name, $current_teacher_id = null)
  {
    $sql = "INSERT INTO classes (school_id, name, current_teacher_id)
                VALUES (:school_id, :name, :current_teacher_id)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':school_id', $school_id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':current_teacher_id', $current_teacher_id, PDO::PARAM_INT);
    return $stmt->execute();
  }


  // Get class by ID
  public function getById($id)
  {
    $sql = "SELECT c.*, s.name AS school_name, u.name AS teacher_name
                FROM classes c
                LEFT JOIN schools s ON c.school_id = s.id
                LEFT JOIN users u ON c.current_teacher_id = u.id
                WHERE c.id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  // Update class
  public function update($id, $school_id, $name, $teacher_id = null)
  {
    $sql = "UPDATE classes
                SET school_id = :school_id, name = :name, current_teacher_id = :current_teacher_id
                WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':school_id', $school_id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':current_teacher_id', $teacher_id, PDO::PARAM_INT);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
  }

  // Delete class
  public function delete($id)
  {
    $sql = "DELETE FROM classes WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
  }

  // Get all classes for a specific school
  // Get all classes for a school, including the current teacher name
  public function getBySchool($school_id)
  {
    try {
      $sql = "
            SELECT 
                c.id,
                c.name,
                c.current_teacher_id,
                u.name AS teacher_name
            FROM classes c
            LEFT JOIN users u ON c.current_teacher_id = u.id
            WHERE c.school_id = :school_id
            ORDER BY c.id DESC
        ";

      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(':school_id', $school_id, PDO::PARAM_INT);
      $stmt->execute();
      $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Ensure teacher_name is not null
      foreach ($classes as &$class) {
        if (empty($class['teacher_name'])) {
          $class['teacher_name'] = 'N/A';
        }
      }

      return $classes;
    } catch (PDOException $e) {
      error_log("Get Classes By School Error: " . $e->getMessage());
      return false;
    }
  }

  // count by school
  public function countClasses($school_id)
  {
    return count($this->getBySchool($school_id));
  }

  // Optional: Get all classes for a specific teacher
  public function getByTeacher($teacher_id)
  {
    $sql = "SELECT id,school_id,name, current_teacher_id
                FROM classes c
                WHERE c.current_teacher_id = :teacher_id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':teacher_id', $teacher_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
