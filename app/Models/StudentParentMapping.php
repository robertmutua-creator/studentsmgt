<?php

namespace Studentsmgt\Models;

use PDO;

class StudentParentMapping
{
  private $pdo;

  public function __construct()
  {
    $this->pdo = (new Database())->getConn();
  }

  // Assign a parent to a student
  public function assignParent($student_id, $parent_user_id)
  {
    $sql = "INSERT INTO student_parent_mapping (student_id, parent_id)
                VALUES (:student_id, :parent_id)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->bindParam(':parent_id', $parent_user_id, PDO::PARAM_INT);
    return $stmt->execute();
  }

  // Remove a parent from a student
  public function removeParent($student_id, $parent_user_id)
  {
    $sql = "DELETE FROM student_parent_mapping
                WHERE student_id = :student_id AND parent_id = :parent_id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->bindParam(':parent_id', $parent_user_id, PDO::PARAM_INT);
    return $stmt->execute();
  }

  // Get all parents for a student
  public function getParentsByStudent($student_id)
  {
    $sql = "SELECT p.*, u.email,u.name,u.code, u.school_id
                FROM parents p
                INNER JOIN student_parent_mapping spm ON p.user_id = spm.parent_id
                INNER JOIN users u ON p.user_id = u.id
                WHERE spm.student_id = :student_id";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Get all students for a parent
  public function getStudentsByParent($parent_user_id)
  {
    $sql = "SELECT s.*
                FROM students s
                INNER JOIN student_parent_mapping spm ON s.id = spm.student_id
                WHERE spm.parent_id = :parent_id";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':parent_id', $parent_user_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Check if mapping exists
  public function exists($student_id, $parent_user_id)
  {
    $sql = "SELECT 1 FROM student_parent_mapping
                WHERE student_id = :student_id AND parent_id = :parent_id";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->bindParam(':parent_id', $parent_user_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchColumn() ? true : false;
  }
}
