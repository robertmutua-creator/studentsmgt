<?php
require_once __DIR__ . "/../../../components/header.php"; ?>
<div class="container my-4">
  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <h5 class="mb-4 text-center">Teacher Registration</h5>
      <?php require_once __DIR__ . "/../../../components/alerts.php"; ?>
    </div>
    <form action="/studentsmgt/admins/teachers/store" method="post">
      <div class="mb-3">
        <label for="code" class="form-label">Code</label>
        <input type="text" name="code" id="code" placeholder="Enter Code e.g TSC No/National ID" required autocomplete="off" class="form-control">
      </div>
      <div class="mb-3">
        <label for="name" class="form-label">Full Name</label>
        <input type="text" name="name" id="name" placeholder="Enter Name E.g Robert Mutua" required autocomplete="off" class="form-control">
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" name="email" id="email" placeholder="Enter email e.g robert@mail.com" required autocomplete="off" class="form-control">
      </div>
      <input type="hidden" name="role" value="teacher">
      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>

</div>
<?php
require_once __DIR__ . "/../../../components/footer.php"; ?>