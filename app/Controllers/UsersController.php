<?php

namespace Studentsmgt\Controllers;

use Studentsmgt\Models\Schools;
use Studentsmgt\Models\Users;

class UsersController
{
  private Users $usersModel;
  private Schools $schoolDetails;
  private int $school_id;

  public function __construct()
  {
    if (session_status() === PHP_SESSION_NONE) session_start();

    if (
      !isset($_SESSION['login_status']) || $_SESSION['login_status'] === 'fail' ||
      !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin'
    ) {
      $_SESSION['error'] = 'You must be logged in!';
      header("Location:/studentsmgt/login");
      exit;
    }

    $this->usersModel = new Users();
    $this->school_id = $_SESSION['school_id'] ?? 0;
    $this->schoolDetails = new Schools();
  }

  /** List all teachers */
  public function listTeachers()
  {
    $school = $this->schoolDetails->getById($this->school_id);
    $teachers = $this->usersModel->getTeachersBySchool($this->school_id);
    require_once __DIR__ . "/../Views/users/admins/admins-teachers/index.php";
  }

  /** Show create teacher form */
  public function createTeacher()
  {
    $school = $this->schoolDetails->getById($this->school_id);
    require_once __DIR__ . "/../Views/users/admins/admins-teachers/create.php";
  }

  /** Show edit teacher form */
  public function editTeacher()
  {
    $id = intval($_POST['id'] ?? 0);
    if ($id <= 0) {
      $_SESSION['error'] = "Invalid ID";
      header("Location:/studentsmgt/admins/teachers");
      exit;
    }
    $school = $this->schoolDetails->getById($this->school_id);
    $teacher = $this->usersModel->getById($id);
    require_once __DIR__ . "/../Views/users/admins/admins-teachers/edit.php";
  }

  /** Store or update teacher */
  public function storeTeacher()
  {
    $id = intval($_POST['id'] ?? 0); // if updating
    $name = ucwords(strtolower(trim($_POST['name'] ?? '')));
    $email = strtolower(trim($_POST['email'] ?? ''));
    $role = 'teacher';
    $code = $_POST['code'] ?? '';
    $password = $_POST['password'] ?? $email; // default password is email

    if (!$name || !$email || !$role) {
      $_SESSION['error'] = "All fields are required";
      header($id ? "Location:/studentsmgt/admins/teachers/edit" : "Location:/studentsmgt/admins/teachers/create");
      exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $_SESSION['error'] = "Invalid email";
      header($id ? "Location:/studentsmgt/admins/teachers/edit" : "Location:/studentsmgt/admins/teachers/create");
      exit;
    }

    $saved = $this->usersModel->save(
      $this->school_id,
      $name,
      $email,
      $role,
      $password,
      $id ?: null,
      $code
    );

    if ($saved) {
      $_SESSION['success'] = $id ? "Teacher updated successfully!" : "Teacher registered successfully! Default password: email address";
    } else {
      $_SESSION['error'] = $id ? "Teacher update failed!" : "Teacher registration failed!";
    }

    header("Location:/studentsmgt/admins/teachers");
    exit;
  }

  /** Register a parent */
  public function registerParent()
  {
    $name = ucwords(strtolower(trim($_POST['name'] ?? '')));
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $phone = trim($_POST['phone_number'] ?? '');
    $token = trim($_POST['push_token'] ?? '');

    if (!$this->school_id || !$name || !$email || !$password) {
      $_SESSION['error'] = "Missing required fields";
      header("Location:/studentsmgt/admins/parents/create");
      exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $_SESSION['error'] = "Invalid email";
      header("Location:/studentsmgt/admins/parents/create");
      exit;
    }

    $saved = $this->usersModel->save(
      $this->school_id,
      $name,
      $email,
      'parent',
      $password,
      null,
      null,
      $phone,
      $token
    );

    if ($saved) {
      $_SESSION['success'] = "Parent registered successfully! Default password: email address";
    } else {
      $_SESSION['error'] = "Parent registration failed!";
    }

    header("Location:/studentsmgt/admins/parents");
    exit;
  }

  /** Delete a user */
  public function delete()
  {
    $id = intval($_POST['id'] ?? 0);
    if ($id <= 0) {
      $_SESSION['error'] = "Invalid ID";
      header("Location:/studentsmgt/admins/teachers");
      exit;
    }

    $ok = $this->usersModel->delete($id);
    if ($ok) {
      $_SESSION['success'] = "User deleted successfully!";
    } else {
      $_SESSION['error'] = "User deletion failed!";
    }

    header("Location:/studentsmgt/admins/teachers");
    exit;
  }
}
