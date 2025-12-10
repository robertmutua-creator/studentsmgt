<?php
require_once __DIR__ . "/../../components/header.php";
?>
<div class="container my-4">

  <!-- Page title -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Student Profile</h4>
    <a href="/studentsmgt/teachers" class="btn btn-sm btn-outline-secondary">
      ← Back
    </a>
  </div>

  <!-- Student details -->
  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h6 class="text-muted mb-3">Student Information</h6>

      <div class="row g-3">
        <div class="col-md-4">
          <div class="small text-muted">Admission Number</div>
          <div class="fw-semibold"><?= htmlspecialchars($student['adm_no']); ?></div>
        </div>

        <div class="col-md-4">
          <div class="small text-muted">Full Name</div>
          <div class="fw-semibold"><?= htmlspecialchars($student['name']); ?></div>
        </div>

        <div class="col-md-4">
          <div class="small text-muted">Date of Birth</div>
          <div class="fw-semibold"><?= htmlspecialchars($student['date_of_birth']); ?></div>
        </div>

        <div class="col-md-4">
          <div class="small text-muted">Age</div>
          <div class="fw-semibold">
            <?php
            $dob = new DateTime($student['date_of_birth']);
            $today = new DateTime();
            echo $dob->diff($today)->y . ' years';
            ?>
          </div>
        </div>

        <div class="col-md-4">
          <div class="small text-muted">Class</div>
          <div class="fw-semibold"><?= htmlspecialchars($student['class_name'] ?? 'N/A'); ?></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Parents section -->
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="text-muted mb-0">Parents / Guardians</h6>
        <!-- Assign Parent Button -->
        <form action="/studentsmgt/teachers/students/assign-parent" method="post">
          <input type="hidden" name="student_id" value="<?= $student['id']; ?>">
          <button type="submit" class="btn btn-sm btn-primary">+ Assign Parent</button>
        </form>
        <!-- <a href="/studentsmgt/teachers/students/assign-parent?id=
          class="btn btn-sm btn-primary"> -->

        </a>
      </div>

      <?php if (!empty($parents)): ?>
        <div class="table-responsive">
          <table class="table table-sm table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($parents as $i => $parent): ?>
                <tr>
                  <td><?= $i + 1; ?></td>
                  <td><?= htmlspecialchars($parent['name']); ?></td>
                  <td><?= htmlspecialchars($parent['email']); ?></td>
                  <td><?= htmlspecialchars($parent['phone_number'] ?? '-'); ?></td>
                  <td>
                    <form action="/studentsmgt/teachers/students/remove-parent" method="post" class="d-inline">
                      <input type="hidden" name="student_id" value="<?= $student['id']; ?>">
                      <input type="hidden" name="parent_id" value="<?= $parent['user_id']; ?>">
                      <button type="submit" class="btn btn-sm btn-outline-danger"
                        onclick="return confirm('Remove this parent from student?');">
                        Remove
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="text-muted small">
          No parents linked to this student.
        </div>
      <?php endif; ?>
    </div>
  </div>

</div>
<?php
require_once __DIR__ . "/../../components/footer.php";
?>