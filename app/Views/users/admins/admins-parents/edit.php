<?php
require_once __DIR__ . "/../../../components/header.php";
?>

<div class="container my-4">
  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <h5 class="mb-4 text-center">Edit Parent</h5>
      <?php require_once __DIR__ . "/../../../components/alerts.php"; ?>
    </div>

    <form action="/studentsmgt/admins/parents/store" method="post">
      <input type="hidden" name="id" value="<?= $parent['id'] ?>">

      <div class="mb-3">
        <label for="name" class="form-label">Parent Name</label>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($parent['name']) ?>" required class="form-control">
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Parent Email</label>
        <input type="email" name="email" id="email" value="<?= htmlspecialchars($parent['email']) ?>" required class="form-control">
      </div>

      <div class="mb-3">
        <label for="phone" class="form-label">Phone Number</label>
        <input type="text" name="phone_number" id="phone" value="<?= htmlspecialchars($parent['phone_number'] ?? '') ?>" class="form-control">
      </div>

      <div class="mb-4">
        <label for="token" class="form-label">Push Token (Optional)</label>
        <input type="text" name="push_token" id="token" value="<?= htmlspecialchars($parent['push_token'] ?? '') ?>" class="form-control">
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Update Parent</button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . "/../../../components/footer.php"; ?>