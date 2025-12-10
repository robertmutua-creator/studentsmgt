<?php
require_once __DIR__ . "/../../../components/header.php";
?>

<div class="container my-4">

  <!-- Display flash messages -->
  <?php require_once __DIR__ . "/../../../components/alerts.php"; ?>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Student Registration Form</h4>
    <a href="/studentsmgt/admins/students" class="btn btn-sm btn-secondary">Back</a>
  </div>

  <form action="/studentsmgt/admins/students/store" method="post">
    <h5 class="mb-3">Student Details</h5>
    <div class="row g-3">

      <!-- Admission Number -->
      <div class="col-md-4">
        <label for="adm_no" class="form-label">Admission Number</label>
        <input type="text"
          name="adm_no"
          id="adm_no"
          class="form-control"
          placeholder="Admission Number"
          value="<?= htmlspecialchars($_POST['adm_no'] ?? '') ?>"
          required>
      </div>

      <!-- Student Name -->
      <div class="col-md-4">
        <label for="name" class="form-label">Student Name</label>
        <input type="text"
          name="name"
          id="name"
          class="form-control"
          placeholder="Full Name"
          value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
          required>
      </div>

      <!-- Date of Birth -->
      <div class="col-md-4">
        <label for="date_of_birth" class="form-label">Date of Birth</label>
        <input type="date"
          name="date_of_birth"
          id="date_of_birth"
          class="form-control"
          value="<?= htmlspecialchars($_POST['date_of_birth'] ?? '') ?>"
          required>
      </div>

      <!-- Class Selection -->
      <div class="col-md-4">
        <label for="current_class_id" class="form-label">Class</label>
        <select name="current_class_id" id="current_class_id" class="form-select" required>
          <option value="">Select a class</option>
          <?php if (!empty($classes)): ?>
            <?php foreach ($classes as $class): ?>
              <option value="<?= $class['id'] ?>" <?= (isset($_POST['current_class_id']) && $_POST['current_class_id'] == $class['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($class['name']) ?>
              </option>
            <?php endforeach; ?>
          <?php else: ?>
            <option value="">No classes found!</option>
          <?php endif; ?>
        </select>
      </div>

    </div>

    <div class="row mt-4">

      <div class="col text-end">
        <button type="submit" class="btn btn-primary px-4">Register Student</button>
      </div>
    </div>
  </form>
</div>

<?php require_once __DIR__ . "/../../../components/footer.php"; ?>