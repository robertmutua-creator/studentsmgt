<?php

namespace Studentsmgt\Controllers;

use Studentsmgt\Models\Schools;
use Studentsmgt\Models\StudentParentMapping;
use Studentsmgt\Models\Users;

class StudentParentMappingController
{
  private StudentParentMapping $model;
  private Users $parents;
  private $schoolid;
  private Schools $schools;

  public function __construct()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    if (!isset($_SESSION['login_status']) || $_SESSION['login_status'] === 'fail' || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'teacher') {
      $_SESSION['error'] = 'You must be logged in!';
      header("Location:/studentsmgt/login");
      exit;
    }
    $this->model = new StudentParentMapping();
    $this->parents = new Users();
    $this->schoolid = $_SESSION['school_id'];
    $this->schools = new Schools();
  }

  public function mapForm()
  {
    $school = $this->schools->getById($this->schoolid);
    $studID = intval($_POST['student_id'] ?? 0);
    $parents = $this->parents->getParentsBySchool($this->schoolid);
    require_once __DIR__ . "/../Views/users/teachers/student-map-parent.php";
  }

  // Assign parent to student
  public function assign()
  {
    $student_id = intval($_POST['student_id'] ?? 0);
    $parent_id  = intval($_POST['parent_id'] ?? 0);

    if ($student_id <= 0 || $parent_id <= 0) {
      $_SESSION['error'] = "Invalid student or parent ID.";
      header("Location: /studentsmgt/teachers");
      exit;
    }

    if ($this->model->exists($student_id, $parent_id)) {
      $_SESSION['error'] = "Parent already assigned to this student.";
      header("Location: /studentsmgt/teachers");
      exit;
    }

    $ok = $this->model->assignParent($student_id, $parent_id);

    if ($ok) {
      $_SESSION['success'] = "Parent assigned successfully.";
    } else {
      $_SESSION['error'] = "Failed to assign parent.";
    }

    header("Location: /studentsmgt/teachers");
    exit;
  }

  // Remove parent from student
  public function remove()
  {
    $student_id = intval($_POST['student_id'] ?? 0);
    $parent_id  = intval($_POST['parent_id'] ?? 0);

    if ($student_id <= 0 || $parent_id <= 0) {
      $_SESSION['error'] = "Invalid student or parent ID.";
      header("Location: /studentsmgt/teachers");
      exit;
    }

    if (!$this->model->exists($student_id, $parent_id)) {
      $_SESSION['error'] = "Mapping does not exist.";
      header("Location: /studentsmgt/teachers");
      exit;
    }

    $ok = $this->model->removeParent($student_id, $parent_id);

    if ($ok) {
      $_SESSION['success'] = "Parent removed successfully.";
    } else {
      $_SESSION['error'] = "Failed to remove parent.";
    }

    header("Location: /studentsmgt/teachers");
    exit;
  }

  // Optional: Methods to return arrays without redirect (e.g., for AJAX)
  public function parentsByStudent($student_id)
  {
    $student_id = intval($student_id);
    if ($student_id <= 0) {
      $_SESSION['error'] = "Invalid student ID.";
      return [];
    }
    return $this->model->getParentsByStudent($student_id);
  }

  public function studentsByParent($parent_id)
  {
    $parent_id = intval($parent_id);
    if ($parent_id <= 0) {
      $_SESSION['error'] = "Invalid parent ID.";
      return [];
    }
    return $this->model->getStudentsByParent($parent_id);
  }
}
