<?php
require_once __DIR__ . "/../../../components/header.php";
?>

<div class="container my-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Class Registration</h5>
    <a href="/studentsmgt/admins/classes" class="btn btn-sm btn-secondary">Back</a>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <?php require_once __DIR__ . "/../../../components/alerts.php"; ?>
    </div>

    <form action="/studentsmgt/admins/classes/store" method="post">
      <!-- Class Name -->
      <div class="mb-3">
        <label for="name" class="form-label">Class Name</label>
        <input type="text"
          name="name"
          id="name"
          placeholder="Enter Class Name e.g Form 3 East"
          required
          autocomplete="off"
          class="form-control">
      </div>

      <!-- Class Teacher -->
      <div class="mb-4">
        <label for="teacher" class="form-label">Class Teacher (Optional)</label>
        <select name="teacherid" id="teacher" class="form-select">
          <?php if (!empty($teachers)): ?>
            <?php foreach ($teachers as $teacher): ?>
              <option value="<?= $teacher['id'] ?>"><?= htmlspecialchars($teacher['name']) ?></option>
            <?php endforeach; ?>
          <?php else: ?>
            <option value="">No teacher found.</option>
          <?php endif; ?>
        </select>
      </div>

      <!-- Save Button -->
      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . "/../../../components/footer.php"; ?>