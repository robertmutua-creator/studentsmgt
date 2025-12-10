<?php

namespace Studentsmgt\Views;

use Studentsmgt\Models\AttendanceLogs;
use Studentsmgt\Models\Classes;
use Studentsmgt\Models\Schools;
use Studentsmgt\Models\Students;

class TeachersView
{
  private $schoolid;
  private $teacherid;
  private Schools $schools;
  private Students $students;
  private Classes $classes;
  private AttendanceLogs $logs;
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
    $this->schoolid = $_SESSION['school_id'];
    $this->teacherid = $_SESSION['user_id'];
    $this->schools = new Schools();
    $this->students = new Students();
    $this->classes = new Classes();
    $this->logs = new AttendanceLogs();
  }
  public function dashboard()
  {
    // Get class id from POST or GET
    $classid = intval($_POST['classid'] ?? $_GET['classid'] ?? 0);

    $school   = $this->schools->getById($this->schoolid);
    $classes  = $this->classes->getByTeacher($this->teacherid);
    $students = [];

    if ($classid <= 0 && !empty($classes)) {
      $classid = $classes[0]['id'];
      $students = $this->students->getByClass($classid);
    }

    if ($classid > 0) {
      $students = $this->students->getByClass($classid);
    }

    $selectedClassId = $classid;
    require_once __DIR__ . "/../Views/users/teachers/index.php";
  }

  public function movementForm()
  {
    $classid = intval($_GET['classid'] ?? $_POST['classid'] ?? 0);
    $movementType = $_GET['movementType'] ?? $_POST['movementType'] ?? '';

    $school   = $this->schools->getById($this->schoolid);
    $classes  = $this->classes->getByTeacher($this->teacherid);
    $students = [];
    $lastStatusMap = [];

    if ($classid <= 0 && !empty($classes)) {
      $classid = $classes[0]['id'];
    }

    if ($classid > 0) {
      $allStudents = $this->students->getByClass($classid);
      $lastStatusMap = $this->logs->getLastStatusByClass($classid);

      if ($movementType === 'in') {
        $students = array_filter($allStudents, fn($s) => strtoupper($lastStatusMap[$s['id']] ?? 'OUT') === 'OUT');
      } elseif ($movementType === 'out') {
        $students = array_filter($allStudents, fn($s) => strtoupper($lastStatusMap[$s['id']] ?? 'OUT') === 'IN');
      } else {
        $students = $allStudents;
      }
    }

    $selectedClassId = $classid;
    require_once __DIR__ . "/../Views/users/teachers/track-movement.php";
  }
}
