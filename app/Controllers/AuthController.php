<?php

namespace Studentsmgt\Controllers;

use Studentsmgt\Models\Users;

class AuthController
{
  private $usersModel;

  public function __construct()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    $this->usersModel = new Users();
  }

  public function login()
  {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $user = $this->usersModel->getByEmail($email);

    if ($user === null) {
      $_SESSION['login_status'] = 'fail';
      $_SESSION['error'] = 'Invalid email or password.';
      header("Location:/studentsmgt/login");
      exit;
    }

    if (password_verify($password, $user['password'])) {
      $_SESSION['login_status'] = 'success';
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_name'] = $user['name'];
      $_SESSION['user_role'] = $user['role'];
      $_SESSION['school_id'] = $user['school_id'];
      $_SESSION['path'] = $user['role'] . "s";
      header("Location:/studentsmgt/" . $_SESSION['path']);
      exit;
    } else {
      $_SESSION['login_status'] = 'fail';
      $_SESSION['error'] = 'Invalid email or password';
      header("Location:/studentsmgt/login");
      exit;
    }
  }

  public function logout()
  {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
      );
    }
    session_destroy();
    header("Location:/studentsmgt/login");
    exit;
  }
}
