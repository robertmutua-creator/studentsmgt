<?php

use Studentsmgt\Controllers\AttendanceLogsController;
use Studentsmgt\Controllers\AuthController;
use Studentsmgt\Controllers\ClassesController;
use Studentsmgt\Controllers\ParentsController;
use Studentsmgt\Controllers\StudentParentMappingController;
use Studentsmgt\Controllers\StudentsController;
use Studentsmgt\Controllers\UsersController;
use Studentsmgt\Views\AdminsView;
use Studentsmgt\Views\TeachersView;

require_once __DIR__ . '/../bootstrap.php';


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$method = $_SERVER['REQUEST_METHOD'];
$base_url = '/studentsmgt';
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = substr($request_uri, strlen($base_url));
$path = trim($path, '/');

if ($path === '') {
  $path = 'login';
}

switch ($path) {
  case 'login':
    if ($method === 'POST') {
      $auth = new AuthController();
      $auth->login();
    } else {
      require_once __DIR__ . '/../app/Views/auth/login.php';
    }
    break;
  case 'logout':
    $auth = new AuthController();
    $auth->logout();
    break;
  case 'admins':
    $view = new AdminsView();
    $view->dashboard();
    break;
  case 'teachers':
    $view = new TeachersView();
    $view->dashboard();
    break;

  // classes routes
  case 'admins/classes':
    $classes = new AdminsView();
    $classes->loadModules('classes');
    break;
  case 'admins/classes/create':
    $classes = new ClassesController();
    $classes->create();
    break;
  case 'admins/classes/store':
    $classes = new ClassesController();
    $classes->store();
    break;
  case 'admins/classes/edit':
    $classes = new ClassesController();
    $classes->edit();
    break;
  case 'admins/classes/update':
    $classes = new ClassesController();
    $classes->update();
    break;
  case 'admins/classes/delete':
    $classes = new ClassesController();
    $classes->destroy();
    break;

  // Students routes
  case 'admins/students':
    $students = new StudentsController();
    $students->index();
    break;
  case 'admins/students/create':
    $students = new StudentsController();
    $students->create();
    break;
  case 'admins/students/store':
    $students = new StudentsController();
    $students->store();
    break;
  case 'admins/students/edit':
    $students = new StudentsController();
    $students->edit();
    break;
  case 'admins/students/update':
    $students = new StudentsController();
    $students->update();
    break;
  case 'admins/students/delete':
    $students = new StudentsController;
    $students->delete();
    break;

  // Teachers routes
  case 'admins/teachers':
    $teachers = new UsersController();
    $teachers->listTeachers();
    break;
  case 'admins/teachers/create':
    $teachers = new UsersController();
    $teachers->createTeacher();
    break;
  case 'admins/teachers/store':
    $teachers = new UsersController();
    $teachers->storeTeacher();
    break;
  case 'admins/teachers/edit':
    $teacher = new UsersController();
    $teacher->editTeacher();
    break;
  case 'admins/teachers/update':
    $teacher = new UsersController();
    $teacher->storeTeacher();
    break;
  case 'admins/teachers/delete':
    $teacher = new UsersController();
    $teacher->delete();
    break;

  // Parents routes
  case 'admins/parents':
    $parents = new ParentsController();
    $parents->listParents();
    break;
  case 'admins/parents/create':
    $parents = new ParentsController;
    $parents->createParent();
    break;
  case 'admins/parents/store':
    $parents = new ParentsController();
    $parents->storeParent();
    break;
  case 'admins/parents/edit':
    $parent = new ParentsController();
    $parent->editParent();
    break;
  case 'admins/parent/update':
    $parent = new ParentsController();
    $parent->storeParent();
    break;
  case 'admins/parents/delete':
    $parent = new ParentsController();
    $parent->delete();
    break;
  case 'admins/students/upload':
    $students = new StudentsController();
    $students->upload();
    break;

  // Teachers
  case 'teachers/students/manage':
    $students = new StudentsController();
    $students->manage();
    break;
  case 'teachers/students/assign-parent':
    $map = new StudentParentMappingController();
    $map->mapForm();
    # code...
    break;
  case 'teachers/students/assign':
    $assign = new StudentParentMappingController();
    $assign->assign();
    break;
  case 'teachers/students/remove-parent':
    $remove = new StudentParentMappingController();
    $remove->remove();
    break;
  case 'teachers/students/track':
    if ($method === 'POST') {
      $submit = new AttendanceLogsController();
      $submit->trackMovementSubmit();
    } else {
      $track = new TeachersView();
      $track->movementForm();
    }
    break;



  case 'test':
    require_once __DIR__ . '/../test.php';
    break;


  default:
    http_response_code(404);
    echo "<h1>404 - Page Not Found</h1>";
    break;
}
