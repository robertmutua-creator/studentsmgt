<?php
require_once __DIR__ . "/../../../components/header.php";
?>

<div class="container my-4">
  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <h5 class="mb-4 text-center">Parent Registration</h5>
      <?php require_once __DIR__ . "/../../../components/alerts.php"; ?>
    </div>

    <form action="/studentsmgt/admins/parents/store" method="post">
      <div class="mb-3">
        <label for="name" class="form-label">Parent Name</label>
        <input type="text" name="name" id="name" placeholder="Enter Parent Name" required class="form-control">
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Parent Email</label>
        <input type="email" name="email" id="email" placeholder="Enter Parent Email" required class="form-control">
      </div>

      <div class="mb-3">
        <label for="phone" class="form-label">Phone Number</label>
        <input type="text" name="phone_number" id="phone" placeholder="Enter Phone Number" class="form-control">
      </div>

      <div class="mb-4">
        <label for="token" class="form-label">Push Token (Optional)</label>
        <input type="text" name="push_token" id="token" placeholder="Push Token" class="form-control">
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Register Parent</button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . "/../../../components/footer.php"; ?>