<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | StudMgt</title>
  <link rel="stylesheet" href="/css/bootstrap.min.css">
</head>

<body class="d-flex justify-content-center align-items-center vh-100">
  <form action="/studentsmgt/login" method="post" class="p-4 border rounded" style="min-width: 300px;">
    <h3 class="text-center mb-3">Login Page</h3>
    <?php require_once __DIR__ . '/../components/alerts.php'; ?>
    <input type="email" name="email" id="email" placeholder="Email Address ..." class="form-control mb-3">
    <input type="password" name="password" id="password" placeholder="Password ..." class="form-control mb-3">
    <button type="submit" class="btn btn-primary w-100">Login</button>
  </form>
  <script>
    // setInterval(() => {
    //   location.reload();
    // }, 1000);
  </script>
  <script src=" /studentsmgt/public/js/bootstrap.bundle.min.js"></script>
</body>

</html>