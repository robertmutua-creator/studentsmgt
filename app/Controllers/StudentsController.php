<?php

namespace Studentsmgt\Controllers;

use Studentsmgt\Models\Classes;
use Studentsmgt\Models\Schools;
use Studentsmgt\Models\StudentParentMapping;
use Studentsmgt\Models\Students;
use PhpOffice\PhpSpreadsheet\IOFactory;

class StudentsController
{
  private $studentModel;
  private Schools $schools;
  private $school_id;
  private Classes $classes;
  private StudentParentMapping $spm;

  public function __construct()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    if (!isset($_SESSION['login_status']) || $_SESSION['login_status'] === 'fail' || !isset($_SESSION['user_role'])) {
      $_SESSION['error'] = 'You must be logged in!';
      header("Location:/studentsmgt/login");
      exit;
    }
    $this->studentModel = new Students();
    $this->school_id = $_SESSION['school_id'] ?? null;
    $this->schools = new Schools();
    $this->classes = new Classes();
    $this->spm = new StudentParentMapping();
  }

  // Handle create student
  public function store()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $adm_no = $_POST['adm_no'] ?? null;
      $name = $_POST['name'] ?? null;
      $dob = $_POST['date_of_birth'] ?? null;
      $current_class_id = $_POST['current_class_id'] ?? null;
      $school_id = $this->school_id;

      $result = $this->studentModel->create($adm_no, $name, $dob, $current_class_id, $school_id);

      if ($result) {
        $_SESSION['success'] = "Student created successfully!";
      } else {
        $_SESSION['error'] = "Failed to create student.";
      }

      header("Location: /studentsmgt/admins/students");
      exit;
    }
  }

  // Handle update student
  public function update()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $id = $_POST['id'] ?? null;
      $adm_no = $_POST['adm_no'] ?? null;
      $name = $_POST['name'] ?? null;
      $dob = $_POST['date_of_birth'] ?? null;
      $current_class_id = $_POST['current_class_id'] ?? null;
      $school_id = $this->school_id;

      $result = $this->studentModel->update($id, $adm_no, $name, $dob, $current_class_id, $school_id);

      if ($result) {
        $_SESSION['success'] = "Student updated successfully!";
      } else {
        $_SESSION['error'] = "Failed to update student.";
      }

      header("Location: /studentsmgt/admins/students");
      exit;
    }
  }

  // Handle delete student
  public function delete()
  {
    $id = $_POST['id'] ?? null;
    $result = $this->studentModel->delete($id);

    if ($result) {
      $_SESSION['success'] = "Student deleted successfully!";
    } else {
      $_SESSION['error'] = "Failed to delete student.";
    }

    header("Location: /studentsmgt/admins/students");
    exit;
  }

  public function index()
  {
    $school_id = $this->school_id;
    $school = $this->schools->getById($school_id);
    $students = $this->studentModel->getAllBySchool($school_id);
    require_once __DIR__ . "/../Views/users/admins/admins-students/index.php";
  }

  public function edit()
  {
    $id = $_POST['id'];
    $school = $this->schools->getById($this->school_id);
    $student = $this->studentModel->getById($id);
    $classes = $this->classes->getBySchool($this->school_id);
    require_once __DIR__ . "/../Views/users/admins/admins-students/edit.php";
  }

  public function create()
  {
    $classes = $this->classes->getBySchool($this->school_id);
    $school = $this->schools->getById($this->school_id);
    require_once __DIR__ . "/../Views/users/admins/admins-students/create.php";
  }

  public function manage()
  {
    $studID = intval($_POST['student_id'] ?? 0);
    $school   = $this->schools->getById($this->school_id);
    if ($studID <= 0) {
      $_SESSION['error'] = "No student selected.";
      header("Location:/studentsmgt/teachers");
      exit;
    }
    $student = $this->studentModel->getById($studID);
    if (!$student) {
      $_SESSION['error'] = "Student not found.";
      header("Location:/studentsmgt/teachers");
      exit;
    }
    $parents = $this->spm->getParentsByStudent($studID);
    $selectedStudentId = $studID;
    require_once __DIR__ . "/../Views/users/teachers/manage-students.php";
  }

  public function upload()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $_SESSION['error'] = "Invalid request method.";
      header("Location: /studentsmgt/admins/students");
      exit;
    }

    if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {
      $_SESSION['error'] = "Please select a valid Excel file.";
      header("Location: /studentsmgt/admins/students");
      exit;
    }

    $fileTmpPath = $_FILES['excel_file']['tmp_name'];

    try {
      $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fileTmpPath);
      $sheet = $spreadsheet->getActiveSheet();
      $rows = $sheet->toArray();
    } catch (\Exception $e) {
      $_SESSION['error'] = "Failed to read Excel file: " . $e->getMessage();
      header("Location: /studentsmgt/admins/students");
      exit;
    }

    // Build class name => id map
    $classes = $this->classes->getBySchool($this->school_id);
    $classMap = [];
    foreach ($classes as $c) {
      $classMap[strtolower($c['name'])] = $c['id'];
    }

    $successCount = 0;
    $failCount = 0;
    $unknownClasses = [];

    // Process rows (assuming header is first row)
    foreach ($rows as $i => $row) {
      if ($i === 0) continue; // skip header row

      [$adm_no, $name, $dob, $className] = $row;

      $classKey = strtolower(trim($className));
      $classId = $classMap[$classKey] ?? null;

      if (!$classId) {
        $unknownClasses[] = $className;
        $failCount++;
        continue;
      }

      $result = $this->studentModel->create(
        trim($adm_no),
        trim($name),
        trim($dob),
        $classId,
        $this->school_id
      );

      if ($result) $successCount++;
      else $failCount++;
    }

    $message = "$successCount student(s) uploaded successfully.";
    if ($failCount > 0) {
      $message .= " $failCount failed.";
      if (!empty($unknownClasses)) {
        $message .= " Unknown classes: " . implode(", ", array_unique($unknownClasses)) . ".";
      }
    }

    $_SESSION['success'] = $message;
    header("Location: /studentsmgt/admins/students");
    exit;
  }
}
