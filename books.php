<?php require_once __DIR__.'/config.php';
$search=trim($_GET['q']??'');
$category=trim($_GET['category']??'');
$sql='SELECT * FROM books WHERE 1';
$params=[]; $types='';
if($search!==''){ $sql.=' AND (title LIKE ? OR author LIKE ?)'; $like='%'.$search.'%'; $params[]=$like; $params[]=$like; $types.='ss'; }
if($category!==''){ $sql.=' AND category = ?'; $params[]=$category; $types.='s'; }
$sql.=' ORDER BY created_at DESC';
$stmt=$conn->prepare($sql);
if($params){ $stmt->bind_param($types, ...$params); }
$stmt->execute();
$books=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
include __DIR__.'/partials/header.php'; ?>
<h3>Books</h3>
<?php if (is_admin()): ?>
  <div class="mb-2 text-end">
    <a href="/cat_24rp08520/admin_books.php" class="btn btn-sm btn-secondary">Add / Manage Books</a>
  </div>
<?php endif; ?>
<form class="row g-2 mb-3" method="get">
  <div class="col-md-6"><input name="q" value="<?php echo htmlspecialchars($search); ?>" class="form-control" placeholder="Search by title or author"></div>
  <div class="col-md-3"><input name="category" value="<?php echo htmlspecialchars($category); ?>" class="form-control" placeholder="Category"></div>
  <div class="col-md-3 d-grid"><button class="btn btn-primary">Search</button></div>
</form>
<div class="table-responsive">
<table class="table table-hover align-middle">
  <thead><tr><th>Title</th><th>Author</th><th>Category</th><th>Status</th><th></th></tr></thead>
  <tbody>
    <?php foreach($books as $b): ?>
      <tr>
        <td><?php echo htmlspecialchars($b['title']); ?></td>
        <td><?php echo htmlspecialchars($b['author']); ?></td>
        <td><?php echo htmlspecialchars($b['category']); ?></td>
        <td><?php echo $b['status']==='available'?'<span class="badge bg-success">Available</span>':'<span class="badge bg-warning">Borrowed</span>'; ?></td>
        <td class="text-end">
          <?php if(is_logged_in() && $b['status']==='available'): ?>
            <form method="post" action="/cat_24rp08520/borrow.php" class="d-inline">
              <input type="hidden" name="book_id" value="<?php echo $b['book_id']; ?>">
              <button class="btn btn-sm btn-outline-primary">Borrow</button>
            </form>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
</div>
<?php include __DIR__.'/partials/footer.php'; ?>
