<?php
require_once __DIR__ . "/../../../components/header.php";
?>

<div class="container my-4">
  <?php require_once __DIR__ . "/../../../components/alerts.php"; ?>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Students</h4>
    <a href="/studentsmgt/admins/students/create" class="btn btn-sm btn-primary">+ New Student</a>
  </div>

  <?php if (!empty($students)): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-light">
          <tr>
            <th style="width: 5%;">#</th>
            <th style="width: 15%;">Adm No</th>
            <th style="width: 30%;" class="text-start">Name</th>
            <th style="width: 10%;">Age</th>
            <th style="width: 20%;" class="text-start">Class</th>
            <th style="width: 20%;">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($students as $key => $student): ?>
            <tr>
              <td><?= $key + 1 ?></td>
              <td><?= htmlspecialchars($student['adm_no']) ?></td>
              <td class="text-start"><?= htmlspecialchars($student['name']) ?></td>

              <?php
              $dob = new DateTime($student['date_of_birth']);
              $today = new DateTime('today');
              $age = $today->diff($dob)->y;
              ?>
              <td><?= $age ?></td>

              <td class="text-start"><?= htmlspecialchars($student['class_name'] ?? 'N/A') ?></td>

              <td class="d-flex justify-content-center" style="gap: 5px;">
                <!-- Edit -->
                <form action="/studentsmgt/admins/students/edit" method="post" class="d-inline">
                  <input type="hidden" name="id" value="<?= $student['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-outline-secondary">Edit</button>
                </form>

                <!-- Delete -->
                <form action="/studentsmgt/admins/students/delete" method="post" class="d-inline" onsubmit="return confirm('Delete this student?');">
                  <input type="hidden" name="id" value="<?= $student['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="text-center py-5 border rounded bg-light">
      <p class="mb-2 fw-semibold">No students found.</p>
      <p class="text-muted mb-3">Start by registering your first student</p>
      <a href="/studentsmgt/admins/students/create" class="btn btn-sm btn-primary">New Student</a>
    </div>
  <?php endif; ?>
</div>

<?php
require_once __DIR__ . "/../../../components/footer.php";
?>