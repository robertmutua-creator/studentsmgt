<?php
require_once __DIR__ . "/../../components/header.php";
?>

<div class="container my-4">
  <?php require_once __DIR__ . "/../../components/alerts.php"; ?>
  <!-- Class selector -->
  <div class="card mb-4 shadow-sm">
    <div class="card-body">
      <form method="post" class="mb-3">
        <div class="row g-2 align-items-end">
          <div class="col-md-4">
            <label class="form-label">Select Class</label>
            <select name="classid" class="form-select" required>
              <option value="">-- Choose Class --</option>
              <?php foreach ($classes as $class): ?>
                <option value="<?= $class['id']; ?>"
                  <?= ($selectedClassId == $class['id']) ? 'selected' : ''; ?>>
                  <?= htmlspecialchars($class['name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Select</button>
          </div>
        </div>
      </form>

    </div>
  </div>

  <!-- Students table -->
  <?php if ($selectedClassId > 0): ?>
    <div class="card">
      <div class="card-body">
        <div class="text-muted small mb-2">
          Total Students: <?= count($students); ?>
        </div>

        <table class="table table-striped align-middle">
          <thead>
            <tr>
              <th>#</th>
              <th>Admission No</th>
              <th>Name</th>
              <th>Age</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($students)): ?>
              <?php foreach ($students as $i => $student): ?>

                <?php
                // ✅ Calculate current age
                $age = '';
                if (!empty($student['date_of_birth'])) {
                  $dob = new DateTime($student['date_of_birth']);
                  $today = new DateTime();
                  $age = $today->diff($dob)->y;
                }
                ?>

                <tr>
                  <td><?= $i + 1; ?></td>
                  <td><?= htmlspecialchars($student['adm_no']); ?></td>
                  <td><?= htmlspecialchars($student['name']); ?></td>
                  <td><?= $age !== '' ? $age . ' yrs' : '-'; ?></td>
                  <td>
                    <form method="post" action="/studentsmgt/teachers/students/manage" class="d-inline">
                      <input type="hidden" name="student_id" value="<?= $student['id']; ?>">
                      <button type="submit" class="btn btn-sm btn-outline-primary">
                        Manage
                      </button>
                    </form>
                  </td>
                </tr>

              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="text-center text-muted">No students found</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>

      </div>
    </div>
  <?php endif; ?>

</div>

<?php
require_once __DIR__ . "/../../components/footer.php";
?>