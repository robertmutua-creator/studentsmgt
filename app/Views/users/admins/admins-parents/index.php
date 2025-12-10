<?php
require_once __DIR__ . "/../../../components/header.php";
?>

<div class="container my-4">
  <?php require_once __DIR__ . "/../../../components/alerts.php"; ?>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Parents</h4>
    <a href="/studentsmgt/admins/parents/create" class="btn btn-sm btn-primary">+ New Parent</a>
  </div>

  <?php if (!empty($parents)): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th class="text-center">#</th>
            <th class="text-center">Name</th>
            <th class="text-center">Email</th>
            <th class="text-center">Phone</th>
            <th class="text-center">Push Token</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($parents as $key => $parent): ?>
            <tr>
              <td class="text-center"><?= $key + 1 ?></td>
              <td><?= htmlspecialchars($parent['name']) ?></td>
              <td><?= htmlspecialchars($parent['email']) ?></td>
              <td><?= htmlspecialchars($parent['phone_number'] ?? 'N/A') ?></td>
              <td><?= htmlspecialchars($parent['push_token'] ?? 'N/A') ?></td>
              <td class="text-center d-flex justify-content-center" style="gap:5px;">
                <!-- Edit -->
                <form action="/studentsmgt/admins/parents/edit" method="post" class="d-inline">
                  <input type="hidden" name="id" value="<?= $parent['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-outline-secondary">Edit</button>
                </form>

                <!-- Delete -->
                <form action="/studentsmgt/admins/parents/delete" method="post" class="d-inline" onsubmit="return confirm('Delete this parent?');">
                  <input type="hidden" name="id" value="<?= $parent['id'] ?>">
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
      <p class="mb-2 fw-semibold">No parents found.</p>
      <p class="text-muted mb-3">Start by registering your first parent</p>
      <a href="/studentsmgt/admins/parents/create" class="btn btn-sm btn-primary">New Parent</a>
    </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . "/../../../components/footer.php"; ?>