<?php include __DIR__.'/partials/header.php'; ?>
<div class="p-5 mb-4 bg-white rounded-3 shadow-sm">
  <div class="container">
    <h1 class="display-6">Welcome to RP Karongi Library</h1>
    <p class="lead">Register, login, browse books, and borrow online.</p>
    <a class="btn btn-primary" href="/cat_24rp08520/books.php">Browse Books</a>
  </div>
</div>
<div class="row g-3">
  <div class="col-md-4">
    <div class="card h-100"><div class="card-body">
      <h5 class="card-title">Register</h5>
      <p>Create your student account to start borrowing.</p>
      <a href="/cat_24rp08520/register.php" class="btn btn-outline-primary btn-sm">Get started</a>
    </div></div>
  </div>
  <div class="col-md-4">
    <div class="card h-100"><div class="card-body">
      <h5 class="card-title">Books</h5>
      <p>Search and filter available books. Admins can manage stock.</p>
      <a href="/cat_24rp08520/books.php" class="btn btn-outline-primary btn-sm">View books</a>
    </div></div>
  </div>
  <div class="col-md-4">
    <div class="card h-100"><div class="card-body">
      <h5 class="card-title">Borrow</h5>
      <p>Borrow a book and track it on your dashboard.</p>
      <a href="/cat_24rp08520/login.php" class="btn btn-outline-primary btn-sm">Login</a>
    </div></div>
  </div>
</div>
<?php include __DIR__.'/partials/footer.php'; ?>
