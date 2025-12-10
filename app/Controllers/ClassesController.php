<?php

namespace Studentsmgt\Controllers;

use Studentsmgt\Models\Classes;
use Studentsmgt\Models\Schools;
use Studentsmgt\Models\Users;

class ClassesController
{
  private $model;
  private $school_id;
  private Schools $schoolDetails;
  private Users $teacherDetails;
  public function __construct()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    if (!isset($_SESSION['login_status']) || $_SESSION['login_status'] === 'fail' || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
      $_SESSION['error'] = 'You must be logged in!';
      header("Location:/studentsmgt/login");
      exit;
    }
    $this->model = new Classes();
    $this->school_id = $_SESSION['school_id'] ?? null;
    $this->schoolDetails = new Schools();
    $this->teacherDetails = new Users();
  }

  public function index()
  {
    $classes = $this->model->getBySchool($this->school_id);
  }

  public function edit()
  {
    $school_id = $this->school_id;
    $school = $this->schoolDetails->getById($school_id);
    $id = intval($_POST['id'] ?? 0);
    $c = $this->model->getById($id);
    $teachers = $this->teacherDetails->getTeachersBySchool($school_id);
    require_once __DIR__ . '/../Views/users/admins/admins-classes/edit.php';
  }

  public function create()
  {
    $school_id = $this->school_id;
    $school = $this->schoolDetails->getById($school_id);
    $teachers = $this->teacherDetails->getTeachersBySchool($school_id);
    require_once __DIR__ . "/../Views/users/admins/admins-classes/create.php";
  }
  public function store()
  {
    $school_id = $this->school_id;
    $name = ucwords(strtolower(trim($_POST['name'] ?? '')));
    $teacher_id = intval($_POST['teacherid'] ?? 0);

    if ($school_id <= 0 || $name == '') {
      $_SESSION['error'] = 'Compulsory fields are required!';
      header("Location:/studentsmgt/admins/classes/create");
      exit;
    }

    $saved = $this->model->create($school_id, $name, $teacher_id ?: null);
    if ($saved) {
      $_SESSION['success'] = 'Record saved!';
    } else {
      $_SESSION['error'] = 'Record not saved!';
    }
    header('Location:/studentsmgt/admins/classes');
    exit;
  }

  public function update()
  {
    $id = intval($_POST['id'] ?? 0);
    $school_id = $this->school_id;
    $name = ucwords(strtolower(trim($_POST['name'] ?? '')));
    $teacher_id = intval($_POST['teacherid'] ?? 0);

    if ($id <= 0 || $school_id <= 0 || $name == '') {
      $_SESSION['error'] = 'Compulsory fields are required!';
      header("Location:/studentsmgt/admins/classes");
      exit;
    }

    $updated = $this->model->update($id, $school_id, $name, $teacher_id ?: null);
    if ($updated) {
      $_SESSION['success'] = 'Record updated!';
    } else {
      $_SESSION['error'] = 'Record not updated!';
    }
    header('Location:/studentsmgt/admins/classes');
    exit;
  }

  public function destroy()
  {
    $id = intval($_POST['id'] ?? 0);
    if ($id > 0) {
      $deleted = $this->model->delete($id);
      $_SESSION['success'] = 'Record deleted!';
      header('Location:/studentsmgt/admins/classes');
      exit;
    } else {
      $_SESSION['error'] = 'ID is missing!';
      header('Location:/studentsmgt/admins/classes');
    }
  }
}
