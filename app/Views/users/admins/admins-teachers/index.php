<?php
require_once __DIR__ . "/../../../components/header.php"; ?>
<div class="container my-4">
  <?php require_once __DIR__ . "/../../../components/alerts.php"; ?>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Teachers</h4>
    <a href="/studentsmgt/admins/teachers/create" class="btn btn-sm btn-primary">+ New Teacher</a>
  </div>
  <?php if (!empty($teachers)): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th class="text-center">Code</th>
            <th class="text-center">Name</th>
            <th class="text-center">Email</th>
            <th class="text-center">Role</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($teachers as $key => $teacher): ?>
            <tr>
              <td><?= $key + 1 ?></td>
              <td><?= $teacher['code'] ?></td>
              <td><?= $teacher['name'] ?></td>
              <td><?= $teacher['email'] ?></td>
              <td><?= $teacher['role'] ?></td>
              <td class="text-center d-flex" style="gap: 5px;">
                <form action="/studentsmgt/admins/teachers/edit" method="post" class="d-inline">
                  <input type="hidden" name="id" value="<?= $teacher['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-outline-secondary">Edit</button>
                </form>
                <form action="/studentsmgt/admins/teachers/delete" method="post" class="d-inline" onsubmit="return confirm('Delete this teacher?');">
                  <input type="hidden" name="id" value="<?= $teacher['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="text-center py-5 border rounded bg-light">
      <p class="mb-2 fw-semibold">No teachers found.</p>
      <p class="text-muted mb-3">Start by registering your first teacher.</p>
      <a href="/studentsmgt/admins/teachers/create" class="btn btn-sm btn-primary">New Teacher</a>
    </div>
  <?php endif; ?>
</div>
<?php
require_once __DIR__ . "/../../../components/footer.php"; ?>