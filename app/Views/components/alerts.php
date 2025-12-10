<?php if (isset($_SESSION['error'])): ?>
  <div class="alert alert-danger" role="alert">
    <?= $_SESSION['error'] ?>
  </div>
<?php elseif (isset($_SESSION['success'])): ?>
  <div class="alert alert-success" role="alert">
    <?= $_SESSION['success'] ?>
  </div>
<?php endif; ?>
<?php unset($_SESSION['success']);
unset($_SESSION['error']); ?>