<?php require_once __DIR__ . '/../../components/header.php'; ?>
<div class="container my-4">
  <h2 class="mb-4">Summary</h2>
  <div class="row text-center">
    <div class="col">
      <span class="fw-bold">Classes:</span>
      <span class="text-muted"><?= $classCount ?></span>

    </div>

    <div class="col">
      <span class="fw-bold">Students:</span>
      <span class="text-muted"><?= $studentsCount ?></span>

    </div>


    <div class="col">
      <span class="fw-bold">Teachers:</span>
      <span class="text-muted"><?= $teachersCount ?></span>
    </div>


    <div class="col">
      <span class="fw-bold">Parents:</span>
      <span class="text-muted"><?= $parentsCount ?></span>
    </div>
  </div>
  <hr>
</div>
<?php require_once __DIR__ . "/../../components/footer.php"; ?>