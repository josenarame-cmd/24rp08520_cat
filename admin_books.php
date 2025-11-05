<?php require_once __DIR__.'/config.php';
if(!is_admin()) redirect('/cat_24rp08520/login.php');
$action=$_GET['action']??'';
$errors=[]; $success='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $title=trim($_POST['title']??'');
  $author=trim($_POST['author']??'');
  $category=trim($_POST['category']??'');
  if($title==='')$errors[]='Title required';
  if($author==='')$errors[]='Author required';
  if($category==='')$errors[]='Category required';
  if(!$errors){
    if($action==='edit'){
      $book_id=intval($_POST['book_id']??0);
      $stmt=$conn->prepare('UPDATE books SET title=?,author=?,category=? WHERE book_id=?');
      $stmt->bind_param('sssi',$title,$author,$category,$book_id);
      $stmt->execute();
      $success='Book updated';
    } else {
      $stmt=$conn->prepare('INSERT INTO books(title,author,category) VALUES(?,?,?)');
      $stmt->bind_param('sss',$title,$author,$category);
      $stmt->execute();
      $success='Book added';
    }
  }
}
if($action==='delete' && isset($_GET['id'])){
  $id=intval($_GET['id']);
  $stmt=$conn->prepare('DELETE FROM books WHERE book_id=?');
  $stmt->bind_param('i',$id);
  $stmt->execute();
  $success='Book deleted';
}
// Load edit target
$edit=null;
if($action==='edit' && isset($_GET['id'])){
  $id=intval($_GET['id']);
  $stmt=$conn->prepare('SELECT * FROM books WHERE book_id=?');
  $stmt->bind_param('i',$id);
  $stmt->execute();
  $edit=$stmt->get_result()->fetch_assoc();
}
$books=$conn->query('SELECT * FROM books ORDER BY created_at DESC')->fetch_all(MYSQLI_ASSOC);
include __DIR__.'/partials/header.php'; ?>
<h3>Manage Books</h3>
<?php if($errors): ?><div class="alert alert-danger"><?php echo implode('<br>',array_map('htmlspecialchars',$errors)); ?></div><?php endif; ?>
<?php if($success): ?><div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
<div class="row g-3">
  <div class="col-md-5">
    <div class="card">
      <div class="card-header"><?php echo $edit?'Edit Book':'Add Book'; ?></div>
      <div class="card-body">
        <form method="post" action="?action=<?php echo $edit?'edit':'add'; ?>">
          <?php if($edit): ?><input type="hidden" name="book_id" value="<?php echo $edit['book_id']; ?>"><?php endif; ?>
          <div class="mb-2"><label class="form-label">Title</label><input name="title" class="form-control" value="<?php echo htmlspecialchars($edit['title']??''); ?>" required></div>
          <div class="mb-2"><label class="form-label">Author</label><input name="author" class="form-control" value="<?php echo htmlspecialchars($edit['author']??''); ?>" required></div>
          <div class="mb-2"><label class="form-label">Category</label><input name="category" class="form-control" value="<?php echo htmlspecialchars($edit['category']??''); ?>" required></div>
          <button class="btn btn-primary"><?php echo $edit?'Update':'Add'; ?></button>
        </form>
      </div>
    </div>
  </div>
  <div class="col-md-7">
    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead><tr><th>Title</th><th>Author</th><th>Category</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
          <?php foreach($books as $b): ?>
            <tr>
              <td><?php echo htmlspecialchars($b['title']); ?></td>
              <td><?php echo htmlspecialchars($b['author']); ?></td>
              <td><?php echo htmlspecialchars($b['category']); ?></td>
              <td><?php echo htmlspecialchars($b['status']); ?></td>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-secondary" href="?action=edit&id=<?php echo $b['book_id']; ?>">Edit</a>
                <a class="btn btn-sm btn-outline-danger" href="?action=delete&id=<?php echo $b['book_id']; ?>" onclick="return confirm('Delete this book?')">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php include __DIR__.'/partials/footer.php'; ?>
