<?php
require_once __DIR__ . "/../../../components/header.php"; ?>
<div class="container my-4">
  <?php require_once __DIR__ . "/../../../components/alerts.php"; ?>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Classes</h4>
    <a href="/studentsmgt/admins/classes/create" class="btn btn-sm btn-primary">+ Create New</a>
  </div>
  <?php if (!empty($classes)): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th style="width: 3%;">#</th>
            <th style="width: auto;" class="text-center">Class Name</th>
            <th style="width: auto;" class="text-center">Class Teacher</th>
            <th style="width: 20%;" class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($classes as $key => $class): ?>
            <tr>
              <td><?= $key + 1 ?></td>
              <td><?= $class['name'] ?></td>
              <td><?= $class['teacher_name'] ?></td>
              <td class="text-center d-flex" style="gap: 5px;">
                <form action="/studentsmgt/admins/classes/edit" method="post" class="d-inline">
                  <input type="hidden" name="id" value="<?= $class['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-outline-secondary">Edit</button>
                </form>
                <form action="/studentsmgt/admins/classes/delete" method="post" class="d-inline" onsubmit="return confirm('Delete this class?');">
                  <input type="hidden" name="id" value="<?= $class['id'] ?>">
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
      <p class="mb-2 fw-semibold">No classes found.</p>
      <p class="text-muted mb-3">Start by creating your first class</p>
      <a href="/studentsmgt/admins/classes/create" class="btn btn-sm btn-primary">Create Class</a>
    </div>
  <?php endif; ?>
</div>
<?php
require_once __DIR__ . "/../../../components/footer.php"; ?>