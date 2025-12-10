<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $school['name'] ?> | <?= $task ?? 'Home' ?></title>
  <link rel="stylesheet" href="/css/bootstrap.min.css">
</head>

<body class="d-flex flex-column min-vh-100">

  <!-- Top navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
    <div class="container-fluid">
      <span class="navbar-text fw-bold">
        <?= $school['name'] ?>
      </span>

      <div class="ms-auto">
        <a href="/studentsmgt/<?= $_SESSION['path'] ?>/settings" class="me-3 text-decoration-none">Settings</a>
        <a href="/studentsmgt/logout" class="text-decoration-none">Logout</a>
      </div>
    </div>
  </nav>

  <!-- Page layout -->
  <div class="container-fluid flex-grow-1">
    <div class="row h-100">

      <!-- Sidebar -->
      <aside class="col-12 col-md-3 col-lg-2 bg-light border-end pt-3">
        <ul class="nav flex-column">

          <!-- Common -->
          <?php if ($_SESSION['user_role'] === 'admin'): ?>
            <li class="nav-item">
              <a class="nav-link" href="/studentsmgt/admins">
                Home
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/studentsmgt/admins/classes">Classes</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/studentsmgt/admins/students">Students</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/studentsmgt/admins/teachers">Teachers</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/studentsmgt/admins/parents">Parents</a>
            </li>

          <?php elseif ($_SESSION['user_role'] === 'teacher'): ?>
            <li class="nav-item">
              <a class="nav-link" href="/studentsmgt/teachers">
                Home
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/studentsmgt/teachers/students/track">
                Track Student
              </a>
            </li>
          <?php endif; ?>

        </ul>
      </aside>

      <!-- Main content area -->
      <main class="col-12 col-md-9 col-lg-10 pt-4">
        <!-- Your page content starts here -->