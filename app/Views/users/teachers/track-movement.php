<?php require_once __DIR__ . "/../../components/header.php"; ?>
<div class="container my-4">
  <h4>Track Student Movement</h4>
  <?php require_once __DIR__ . "/../../components/alerts.php"; ?>

  <!-- Class & Movement Type (GET filter) -->
  <form method="get" class="row g-3 mb-3">
    <div class="col-md-4">
      <label class="form-label">Select Class</label>
      <select name="classid" class="form-select" onchange="this.form.submit()">
        <?php foreach ($classes as $c): ?>
          <option value="<?= $c['id'] ?>" <?= $selectedClassId == $c['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($c['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">Movement Type</label>
      <select name="movementType" class="form-select" onchange="this.form.submit()">
        <option value="">-- All --</option>
        <option value="in" <?= ($movementType === 'in') ? 'selected' : '' ?>>In</option>
        <option value="out" <?= ($movementType === 'out') ? 'selected' : '' ?>>Out</option>
      </select>
    </div>
  </form>

  <!-- Students Form (POST) -->
  <form method="post" action="/studentsmgt/teachers/students/track">
    <input type="hidden" name="classid" value="<?= $selectedClassId ?>">
    <input type="hidden" name="movementType" value="<?= $movementType ?>">

    <!-- General Reason -->
    <div class="mb-3">
      <label class="form-label">General Reason (optional)</label>
      <input type="text" name="general_reason" class="form-control" placeholder="Reason for all selected students">
    </div>

    <!-- Students Table -->
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Admission No</th>
          <th>Name</th>
          <th>Status</th>
          <th>Select</th>
          <th>Reason</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($students as $i => $student):
          $lastStatus = strtoupper($lastStatusMap[$student['id']] ?? 'OUT');
          $canSelect = ($movementType === 'in' && $lastStatus === 'OUT')
            || ($movementType === 'out' && $lastStatus === 'IN')
            || ($movementType === '');
        ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($student['adm_no']) ?></td>
            <td><?= htmlspecialchars($student['name']) ?></td>
            <td>
              <span class="badge <?= $lastStatus === 'IN' ? 'bg-success' : 'bg-secondary' ?>">
                <?= $lastStatus ?>
              </span>
            </td>
            <td>
              <input type="checkbox" name="students[]" value="<?= $student['id'] ?>" <?= $canSelect ? '' : 'disabled' ?>>
            </td>
            <td>
              <input type="text" name="reason[<?= $student['id'] ?>]" class="form-control form-control-sm" <?= $canSelect ? '' : 'disabled' ?> placeholder="Optional reason">
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <button type="submit" class="btn btn-primary mt-3">Submit Movement</button>
  </form>
</div>
<?php require_once __DIR__ . "/../../components/footer.php"; ?>