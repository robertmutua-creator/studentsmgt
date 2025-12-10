<?php

namespace Studentsmgt\Controllers;

use Studentsmgt\Models\Schools;
use Studentsmgt\Models\Users;

class ParentsController
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

  /** List all parents */
  public function listParents()
  {
    $school = $this->schoolDetails->getById($this->school_id);
    $parents = $this->usersModel->getParentsBySchool($this->school_id);
    require_once __DIR__ . "/../Views/users/admins/admins-parents/index.php";
  }

  /** Show create parent form */
  public function createParent()
  {
    $school = $this->schoolDetails->getById($this->school_id);
    require_once __DIR__ . "/../Views/users/admins/admins-parents/create.php";
  }

  /** Show edit parent form */
  public function editParent()
  {
    $school = $this->schoolDetails->getById($this->school_id);
    $id = intval($_POST['id'] ?? 0);
    if ($id <= 0) {
      $_SESSION['error'] = "Invalid ID";
      header("Location:/studentsmgt/admins/parents");
      exit;
    }
    $parent = $this->usersModel->getById($id);
    require_once __DIR__ . "/../Views/users/admins/admins-parents/edit.php";
  }

  /** Store or update parent */
  public function storeParent()
  {
    $id = intval($_POST['id'] ?? 0); // if updating
    $name = ucwords(strtolower(trim($_POST['name'] ?? '')));
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? $email; // default password
    $phone = trim($_POST['phone_number'] ?? '');
    $token = trim($_POST['push_token'] ?? '');

    if (!$name || !$email) {
      $_SESSION['error'] = "Name and email are required";
      header($id ? "Location:/studentsmgt/admins/parents/edit" : "Location:/studentsmgt/admins/parents/create");
      exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $_SESSION['error'] = "Invalid email";
      header($id ? "Location:/studentsmgt/admins/parents/edit" : "Location:/studentsmgt/admins/parents/create");
      exit;
    }

    $saved = $this->usersModel->save(
      $this->school_id,
      $name,
      $email,
      'parent',
      $password,
      $id ?: null,
      null,
      $phone,
      $token
    );

    if ($saved) {
      $_SESSION['success'] = $id ? "Parent updated successfully!" : "Parent registered successfully! Default password: email address";
    } else {
      $_SESSION['error'] = $id ? "Parent update failed!" : "Parent registration failed!";
    }

    header("Location:/studentsmgt/admins/parents");
    exit;
  }

  /** Delete parent */
  public function delete()
  {
    $id = intval($_POST['id'] ?? 0);
    if ($id <= 0) {
      $_SESSION['error'] = "Invalid ID";
      header("Location:/studentsmgt/admins/parents");
      exit;
    }

    $ok = $this->usersModel->delete($id);
    if ($ok) {
      $_SESSION['success'] = "Parent deleted successfully!";
    } else {
      $_SESSION['error'] = "Parent deletion failed!";
    }

    header("Location:/studentsmgt/admins/parents");
    exit;
  }
}
