<?php require_once __DIR__.'/config.php';
if(!is_logged_in()) redirect('/cat_24rp08520/login.php');
$user_id = $_SESSION['user']['id'];
// Fetch borrowed books
$stmt=$conn->prepare('SELECT bb.id, b.title, b.author, bb.borrowed_at, bb.returned_at FROM borrowed_books bb JOIN books b ON b.book_id=bb.book_id WHERE bb.user_id=? ORDER BY bb.borrowed_at DESC');
$stmt->bind_param('i',$user_id);
$stmt->execute();
$borrows=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
include __DIR__.'/partials/header.php'; ?>
<h3>Dashboard</h3>
<p class="text-muted">Welcome, <?php echo htmlspecialchars($_SESSION['user']['username']); ?>.</p>
<div class="card">
  <div class="card-header">My Borrowed Books</div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead><tr><th>Title</th><th>Author</th><th>Borrowed At</th><th>Status</th><th>Action</th></tr></thead>
        <tbody>
          <?php if(!$borrows): ?>
            <tr><td colspan="4" class="text-center p-3">No borrowed books yet.</td></tr>
          <?php else: foreach($borrows as $r): ?>
            <tr>
              <td><?php echo htmlspecialchars($r['title']); ?></td>
              <td><?php echo htmlspecialchars($r['author']); ?></td>
              <td><?php echo htmlspecialchars($r['borrowed_at']); ?></td>
              <td><?php echo $r['returned_at']?'<span class="badge bg-secondary">Returned</span>':'<span class="badge bg-success">Borrowed</span>'; ?></td>
              <td>
                <?php if(!$r['returned_at']): ?>
                  <a class="btn btn-sm btn-outline-success" href="/cat_24rp08520/return.php?id=<?php echo (int)$r['id']; ?>" onclick="return confirm('Mark as returned?')">Return</a>
                <?php else: ?>
                  —
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php include __DIR__.'/partials/footer.php'; ?>
