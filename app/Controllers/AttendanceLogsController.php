<?php

namespace Studentsmgt\Controllers;

use Studentsmgt\Models\AttendanceLogs;

class AttendanceLogsController
{
  private AttendanceLogs $model;

  public function __construct()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    if (
      !isset($_SESSION['login_status']) || $_SESSION['login_status'] === 'fail'
      || ($_SESSION['user_role'] ?? '') !== 'teacher'
    ) {
      $_SESSION['error'] = 'You must be logged in!';
      header("Location:/studentsmgt/login");
      exit;
    }

    $this->model = new AttendanceLogs();
  }

  public function trackMovementSubmit()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $_SESSION['error'] = "Invalid request method.";
      header("Location: /studentsmgt/teachers/students/track");
      exit;
    }

    $classId = intval($_POST['classid'] ?? 0);
    $movementType = strtoupper($_POST['movementType'] ?? '');
    $selectedStudents = $_POST['students'] ?? [];
    $generalReason = trim($_POST['general_reason'] ?? '');
    $individualReasons = $_POST['reason'] ?? [];

    if (!$classId || !in_array($movementType, ['IN', 'OUT']) || empty($selectedStudents)) {
      $_SESSION['error'] = "Please select class, movement type, and at least one student.";
      header("Location: /studentsmgt/teachers/students/track?classid=$classId&movementType=$movementType");
      exit;
    }

    $teacherId = $_SESSION['user_id'];
    $academicYear = date('Y');

    $successCount = 0;
    $failCount = 0;

    foreach ($selectedStudents as $studentId) {
      $studentId = intval($studentId);
      $reason = trim($individualReasons[$studentId] ?? '');
      if ($reason === '' && $generalReason !== '') {
        $reason = $generalReason;
      }

      $result = $this->model->create(
        $academicYear,
        $studentId,
        $classId,
        $teacherId,
        $movementType,
        $reason
      );

      if ($result) {
        $successCount++;
      } else {
        $failCount++;
      }
    }

    $_SESSION['success'] = "$successCount student(s) updated successfully." . ($failCount ? " $failCount failed." : '');
    header("Location: /studentsmgt/teachers/students/track?classid=$classId&movementType=$movementType");
    exit;
  }
}
