<?php
require_once __DIR__ . "/../../components/header.php";
?>
<div class="container my-4">

  <!-- Page title -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Assign Parent to Student</h4>
    <a href="/studentsmgt/teachers/students/manage?id=<?= $studID ?>" class="btn btn-sm btn-outline-secondary">
      ← Back to Student
    </a>
  </div>

  <!-- Assign Parent Form -->
  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <form action="/studentsmgt/teachers/students/assign" method="post" class="row g-3 align-items-center">
        <input type="hidden" name="student_id" value="<?= $studID; ?>">

        <div class="col-md-8">
          <label for="parent_id" class="form-label small text-muted">Select Parent</label>
          <select name="parent_id" id="parent_id" class="form-select form-select-sm" required>
            <option value="" disabled selected>-- Choose a parent --</option>
            <?php foreach ($parents as $parent): ?>
              <option value="<?= $parent['id']; ?>">
                <?= htmlspecialchars($parent['name']); ?> (<?= htmlspecialchars($parent['email']); ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <input type="hidden" name="student_id" value="<?= $studID ?>">
        <div class="col-md-4 d-flex align-items-end">
          <button type="submit" class="btn btn-primary w-100">Assign Parent</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require_once __DIR__ . "/../../components/footer.php"; ?>