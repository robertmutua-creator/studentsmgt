<?php

namespace Studentsmgt\Views;

use Studentsmgt\Models\AttendanceLogs;
use Studentsmgt\Models\Classes;
use Studentsmgt\Models\Schools;
use Studentsmgt\Models\Students;
use Studentsmgt\Models\Users;

class AdminsView
{
  private Classes $classes;
  private Students $students;
  private Users $users;
  private AttendanceLogs $logs;
  private $schoolid;
  private Schools $schoolDetails;
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
    $this->schoolDetails = new Schools();
    $this->classes = new Classes();
    $this->students = new Students();
    $this->users = new Users();
    $this->logs = new AttendanceLogs();
    $this->schoolid = $_SESSION['school_id'];
  }

  public function dashboard()
  {
    $school = $this->schoolDetails->getById($this->schoolid);
    $studentsCount = $this->students->countStudents($this->schoolid) ?? 'Loading ...';
    $studArray = [0, 5, 25, 50, 100, 200, 500, 1000];
    $classCount = $this->classes->countClasses($this->schoolid) ?? 'Loading ...';
    $classArray = [0, 5, 10, 15, 20, 50, 100];
    $teachersCount = $this->users->countTeachers($this->schoolid) ?? 'Loading ...';
    $teacherArray = [0, 10, 20, 50, 100, 200];
    $parentsCount = $this->users->countParents($this->schoolid) ?? 'Loading ...';
    $parentArray = [0, 10, 25, 50, 100, 200, 500, 1000];
    require_once __DIR__ . '/../Views/users/admins/index.php';
  }

  public function loadModules($module)
  {
    $school = $this->schoolDetails->getById($this->schoolid);
    $schoolid = $this->schoolid;
    $allowedModules = ['classes', 'students', 'teachers', 'parents', 'attendance'];
    if (!in_array($module, $allowedModules)) {
      $_SESSION['error'] = ucwords($module) . " not ready!";
      header("Location:/studentsmgt/admins");
      exit;
    }

    switch ($module) {
      case 'classes':
        $classes = $this->classes->getBySchool($schoolid);
        require_once __DIR__ . "/../Views/users/admins/admins-classes/index.php";
        break;
      case 'students':
        $students = $this->students->getAllBySchool($this->schoolid);
        require_once __DIR__ . "/../Views/users/admins/admins-students/index.php";
        break;
      case 'teachers':
        $teachers = $this->users->getTeachersBySchool($this->schoolid);
        require_once __DIR__ . "/../Views/users/admins/admins-teachers/index.php";
        break;
      case 'parents':
        $parents = $this->users->getParentsBySchool($this->schoolid);
        require_once __DIR__ . "/../Views/users/admins/admins-parents/index.php";
        break;

      default:
        $_SESSION['error'] = "Module not ready!";
        header("Location:/studentsmgt/admins");
        exit;
        break;
    }
  }
}
