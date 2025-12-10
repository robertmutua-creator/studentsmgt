<?php

namespace Studentsmgt\Models;

use PDO;
use PDOException;

class AttendanceLogs
{
  private $pdo;

  public function __construct()
  {
    $this->pdo = (new Database())->getConn();
  }

  // Record a new attendance
  public function create($academic_year, $student_id, $class_id, $teacher_id, $status, $message = null)
  {
    $sql = "INSERT INTO attendance_logs (academic_year,student_id, class_id, teacher_id, status, message)
                VALUES (:academic_year,:student_id, :class_id, :teacher_id, :status, :message)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->bindParam(':class_id', $class_id, PDO::PARAM_INT);
    $stmt->bindParam(':teacher_id', $teacher_id, PDO::PARAM_INT);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':message', $message);
    $stmt->bindParam(':academic_year', $academic_year);
    return $stmt->execute() ? $this->pdo->lastInsertId() : false;
  }

  // Get all attendance logs
  public function getAll()
  {
    $sql = "SELECT al.*, s.name AS student_name, c.name AS class_name, u.name AS teacher_name
                FROM attendance_logs al
                LEFT JOIN students s ON al.student_id = s.id
                LEFT JOIN classes c ON al.class_id = c.id
                LEFT JOIN users u ON al.teacher_id = u.id
                ORDER BY al.timestamp DESC";
    $stmt = $this->pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Get attendance log by ID
  public function getById($id)
  {
    $sql = "SELECT al.*, s.name AS student_name, c.name AS class_name, u.name AS teacher_name
                FROM attendance_logs al
                LEFT JOIN students s ON al.student_id = s.id
                LEFT JOIN classes c ON al.class_id = c.id
                LEFT JOIN users u ON al.teacher_id = u.id
                WHERE al.id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  // Update attendance log
  public function update($id, $student_id, $class_id, $teacher_id, $status, $message = null)
  {
    $sql = "UPDATE attendance_logs
                SET student_id = :student_id, class_id = :class_id, teacher_id = :teacher_id,
                    status = :status, message = :message
                WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->bindParam(':class_id', $class_id, PDO::PARAM_INT);
    $stmt->bindParam(':teacher_id', $teacher_id, PDO::PARAM_INT);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':message', $message);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
  }

  // Delete attendance log
  public function delete($id)
  {
    $sql = "DELETE FROM attendance_logs WHERE id = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
  }

  // Get all logs for a specific student
  public function getByStudent($student_id)
  {
    $sql = "SELECT al.*, c.name AS class_name, u.name AS teacher_name
                FROM attendance_logs al
                LEFT JOIN classes c ON al.class_id = c.id
                LEFT JOIN users u ON al.teacher_id = u.id
                WHERE al.student_id = :student_id
                ORDER BY al.timestamp DESC";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Get all logs for a specific class
  public function getByClass($class_id)
  {
    $sql = "SELECT al.*, s.name AS student_name, u.name AS teacher_name
                FROM attendance_logs al
                LEFT JOIN students s ON al.student_id = s.id
                LEFT JOIN users u ON al.teacher_id = u.id
                WHERE al.class_id = :class_id
                ORDER BY al.timestamp DESC";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':class_id', $class_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Get all logs for a specific teacher
  public function getByTeacher($teacher_id)
  {
    $sql = "SELECT al.*, s.name AS student_name, c.name AS class_name
                FROM attendance_logs al
                LEFT JOIN students s ON al.student_id = s.id
                LEFT JOIN classes c ON al.class_id = c.id
                WHERE al.teacher_id = :teacher_id
                ORDER BY al.timestamp DESC";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':teacher_id', $teacher_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getLatestByStudent($student_id)
  {
    $sql = "SELECT *
          FROM attendance_logs
          WHERE student_id = :student_id
          ORDER BY timestamp DESC
          LIMIT 1";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  // Get last movement status per student for a class
  public function getLastStatusByClass($class_id)
  {
    $sql = "
        SELECT a.student_id, a.status
        FROM attendance_logs a
        INNER JOIN (
            SELECT student_id, MAX(timestamp) AS last_time
            FROM attendance_logs
            WHERE class_id = :class_id
            GROUP BY student_id
        ) last ON last.student_id = a.student_id AND last.last_time = a.timestamp
    ";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':class_id', $class_id, PDO::PARAM_INT);
    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Make lookup array [student_id => status]
    $map = [];
    foreach ($rows as $row) {
      $map[$row['student_id']] = $row['status'];
    }

    return $map;
  }
}
