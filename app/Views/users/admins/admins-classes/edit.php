<?php
require_once __DIR__ . "/../../../components/header.php"; ?>
<div class="container my-4">
  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <h5 class="mb-4 text-center">Class Registration</h5>
      <?php require_once __DIR__ . "/../../../components/alerts.php"; ?>
    </div>
    <form action="/studentsmgt/admins/classes/update" method="post">
      <div class="mb-3">
        <label for="name" class="form-label">Class Name</label>
        <input type="text" name="name" id="name" placeholder="Enter Class Name e.g Form 3 East" required autocomplete="off" class="form-control" value="<?= $c['name'] ?>">
        <input type="hidden" name="id" value="<?= $c['id'] ?>">
      </div>
      <div class="mb-4">
        <label for="teacher" class="form-lable">Class Teacher (Optional)</label>
        <select name="teacherid" id="teacher" class="form-select">
          <?php if (!empty($teachers)): ?>
            <?php foreach ($teachers as $teacher): ?>
              <option value="<?= $teacher['id'] ?>" <?php if ($teacher['id'] === $c['current_teacher_id']) {
                                                      echo "selected";
                                                    } ?>><?= $teacher['name'] ?></option>
            <?php endforeach; ?>
          <?php else: ?>
            <option value="">No teacher found.</option>
          <?php endif; ?>
        </select>
      </div>
      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>

</div>
<?php
require_once __DIR__ . "/../../../components/footer.php"; ?>