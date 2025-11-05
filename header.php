<?php require_once __DIR__.'/../config.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RP Karongi Library</title>
  <link href="/cat_24rp08520/bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/cat_24rp08520/assets/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="/cat_24rp08520/index.php">Library</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="/cat_24rp08520/index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="/cat_24rp08520/books.php">Books</a></li>
        <li class="nav-item"><a class="nav-link" href="/cat_24rp08520/contact.php">Contact</a></li>
      </ul>
      <ul class="navbar-nav">
        <?php if (!is_logged_in()): ?>
          <li class="nav-item"><a class="nav-link" href="/cat_24rp08520/register.php">Register</a></li>
          <li class="nav-item"><a class="nav-link" href="/cat_24rp08520/login.php">Login</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="/cat_24rp08520/dashboard.php">Dashboard</a></li>
          <?php if (is_admin()): ?>
            <li class="nav-item"><a class="nav-link" href="/cat_24rp08520/admin_books.php">Manage Books</a></li>
            <li class="nav-item"><a class="nav-link" href="/cat_24rp08520/admin_users.php">Users</a></li>
          <?php endif; ?>
          <li class="nav-item"><a class="nav-link" href="/cat_24rp08520/logout.php">Logout</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container my-4">
<?php if (!empty($_SESSION['flash'])): ?>
  <div class="alert alert-info alert-dismissible fade show" role="alert">
    <?php echo htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>
