<?php

namespace Studentsmgt\Models;

use PDO;
use PDOException;

class Users
{
  private $pdo;

  public function __construct()
  {
    $this->pdo = (new Database())->getConn();
  }

  public function getByEmail($email)
  {
    try {
      $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email=:email");
      $stmt->bindParam(":email", $email);
      $stmt->execute();
      $user = $stmt->fetch(PDO::FETCH_ASSOC);
      return $user ? $user : [];
    } catch (PDOException $th) {
      return [];
    }
  }

  /**
   * Save a user (create or update)
   * 
   * @param int|null $id If null, creates new user; else updates existing
   * @param int $school_id
   * @param string $name
   * @param string $email
   * @param string $role 'admin'|'teacher'|'parent'
   * @param string|null $password Plain password (optional for update)
   * @param string|null $code Optional code for teachers/admins
   * @param string|null $phone_number Parent phone
   * @param string|null $push_token Parent push token
   * @return int|bool user_id on success, false on failure
   */
  public function save(
    $school_id,
    $name,
    $email,
    $role,
    $password = null,
    $id = null,
    $code = null,
    $phone_number = null,
    $push_token = null
  ) {
    try {
      $this->pdo->beginTransaction();

      // Hash password if provided
      $hashedPassword = $password ? password_hash($password, PASSWORD_DEFAULT) : null;

      if ($id) {
        // Update existing user
        $sql = "UPDATE users SET school_id=:school_id, name=:name, email=:email, role=:role";
        if ($hashedPassword) $sql .= ", password=:password";
        if ($code !== null) $sql .= ", code=:code";
        $sql .= " WHERE id=:id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':school_id', $school_id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($hashedPassword) $stmt->bindParam(':password', $hashedPassword);
        if ($code !== null) $stmt->bindParam(':code', $code);
        $stmt->execute();

        $userId = $id;
      } else {
        // Create new user
        $sql = "INSERT INTO users (school_id, name, email, role, password, code)
                        VALUES (:school_id, :name, :email, :role, :password, :code)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':school_id', $school_id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':code', $code);
        $stmt->execute();

        $userId = $this->pdo->lastInsertId();
      }

      // Handle parent data
      if ($role === 'parent') {
        // Check if parent exists
        $stmtCheck = $this->pdo->prepare("SELECT user_id FROM parents WHERE user_id=:user_id");
        $stmtCheck->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmtCheck->execute();
        $exists = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($exists) {
          // Update
          $sqlParent = "UPDATE parents SET phone_number=:phone_number, push_token=:push_token, updated_at=CURRENT_TIMESTAMP WHERE user_id=:user_id";
        } else {
          // Insert
          $sqlParent = "INSERT INTO parents (user_id, phone_number, push_token) VALUES (:user_id, :phone_number, :push_token)";
        }

        $stmtParent = $this->pdo->prepare($sqlParent);
        $stmtParent->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmtParent->bindParam(':phone_number', $phone_number);
        $stmtParent->bindParam(':push_token', $push_token);
        $stmtParent->execute();
      }

      $this->pdo->commit();
      return $userId;
    } catch (PDOException $e) {
      $this->pdo->rollBack();
      error_log("Users::save Error: " . $e->getMessage());
      return false;
    }
  }

  // Get a user by ID (includes parent data if exists)
  public function getById($id)
  {
    $sql = "SELECT u.*, s.name AS school_name, p.phone_number, p.push_token
                FROM users u
                LEFT JOIN schools s ON u.school_id=s.id
                LEFT JOIN parents p ON u.id=p.user_id
                WHERE u.id=:id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  // Get all users (optionally filtered by role)
  public function getAll($role = null)
  {
    $sql = "SELECT u.*, s.name AS school_name, p.phone_number, p.push_token
                FROM users u
                LEFT JOIN schools s ON u.school_id=s.id
                LEFT JOIN parents p ON u.id=p.user_id";
    if ($role) $sql .= " WHERE u.role=:role";
    $sql .= " ORDER BY u.id DESC";

    $stmt = $this->pdo->prepare($sql);
    if ($role) $stmt->bindParam(':role', $role);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Delete user (with parent data if applicable)
  public function delete($id)
  {
    try {
      $this->pdo->beginTransaction();

      $stmtParent = $this->pdo->prepare("DELETE FROM parents WHERE user_id=:id");
      $stmtParent->bindParam(':id', $id, PDO::PARAM_INT);
      $stmtParent->execute();

      $stmtUser = $this->pdo->prepare("DELETE FROM users WHERE id=:id");
      $stmtUser->bindParam(':id', $id, PDO::PARAM_INT);
      $stmtUser->execute();

      $this->pdo->commit();
      return true;
    } catch (PDOException $e) {
      $this->pdo->rollBack();
      error_log("Users::delete Error: " . $e->getMessage());
      return false;
    }
  }

  // Get all teachers in a school
  public function getTeachersBySchool($school_id)
  {
    $sql = "SELECT * FROM users WHERE school_id=:school_id AND role='teacher'";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':school_id', $school_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Get all parents in a school
  public function getParentsBySchool($school_id)
  {
    $sql = "SELECT u.id, u.school_id, u.name, u.email, u.role, p.phone_number, p.push_token
                FROM users u
                LEFT JOIN parents p ON u.id=p.user_id
                WHERE u.role='parent' AND u.school_id=:school_id
                ORDER BY u.name ASC";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':school_id', $school_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  // Count teachers
  public function countTeachers($school_id)
  {
    $sql = "SELECT COUNT(*) AS total FROM users WHERE school_id=:school_id AND (role='teacher' OR role='admin')";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':school_id', $school_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return (int)$result['total'];
  }

  // Count parents
  public function countParents($school_id)
  {
    $sql = "SELECT COUNT(*) AS total 
            FROM users u WHERE u.role='parent' AND u.school_id=:school_id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':school_id', $school_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return (int)$result['total'];
  }
}
