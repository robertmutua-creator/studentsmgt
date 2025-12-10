<?php
require_once __DIR__ . "/../../../components/header.php"; ?>
<div class="container my-4">
  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <h5 class="mb-4 text-center">Update Teacher <?= $teacher['name'] ?> Details</h5>
      <?php require_once __DIR__ . "/../../../components/alerts.php"; ?>
    </div>
    <form action="/studentsmgt/admins/teachers/update" method="post">
      <div class="mb-3">
        <label for="code" class="form-label">Code</label>
        <input type="text" name="code" id="code" placeholder="Enter Code e.g TSC No/National ID" required autocomplete="off" class="form-control" value="<?= $teacher['code'] ?>">
      </div>
      <div class="mb-3">
        <label for="name" class="form-label">Full Name</label>
        <input type="text" name="name" id="name" placeholder="Enter Name E.g Robert Mutua" required autocomplete="off" class="form-control" value="<?= $teacher['name'] ?>">
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" name="email" id="email" placeholder="Enter email e.g robert@mail.com" required autocomplete="off" class="form-control" value="<?= $teacher['email'] ?>">
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">New Password</label>
        <input type="password" name="password" id="password" placeholder="Leave blank to retain old password" autocomplete="off" class="form-control">
      </div>

      <div class="mb-4">
        <label for="role" class="form-lable">Role</label>
        <select name="role" id="role" class="form-select">
          <option value="teacher" <?php if ($teacher['role'] === "teacher") {
                                    echo "selected";
                                  } ?>>Teacher</option>
          <option value="admin" <?php if ($teacher['role'] === "admin") {
                                  echo "selected";
                                } ?>>Admin</option>
        </select>
      </div>
      <div class="d-grid">
        <input type="hidden" name="id" value="<?= $teacher['id'] ?>">
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </div>
    </form>
  </div>

</div>
<?php
require_once __DIR__ . "/../../../components/footer.php"; ?>