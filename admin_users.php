<?php require_once __DIR__.'/config.php';
if(!is_admin()) redirect('/cat_24rp08520/login.php');
$errors=[]; $success='';
$action=$_GET['action']??'';

if($_SERVER['REQUEST_METHOD']==='POST'){
  if($action==='role'){
    $id=intval($_POST['id']??0);
    $role=$_POST['role']==='admin'?'admin':'student';
    if($id>0){
      $stmt=$conn->prepare('UPDATE users SET role=? WHERE id=?');
      $stmt->bind_param('si',$role,$id);
      $stmt->execute();
      $success='Role updated';
    }
  } elseif($action==='delete'){
    $id=intval($_POST['id']??0);
    if($id>0){
      // Prevent deleting yourself by mistake
      if($id == $_SESSION['user']['id']){
        $errors[] = 'You cannot delete your own account while logged in.';
      } else {
        $stmt=$conn->prepare('DELETE FROM users WHERE id=?');
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $success='User deleted';
      }
    }
  }
}

$users=$conn->query('SELECT id,username,email,student_id,role,created_at FROM users ORDER BY created_at DESC')->fetch_all(MYSQLI_ASSOC);
include __DIR__.'/partials/header.php'; ?>
<h3>Manage Users</h3>
<?php if($errors): ?><div class="alert alert-danger"><?php echo implode('<br>',array_map('htmlspecialchars',$errors)); ?></div><?php endif; ?>
<?php if($success): ?><div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
<div class="table-responsive">
  <table class="table table-striped align-middle">
    <thead>
      <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Student ID</th>
        <th>Role</th>
        <th>Created</th>
        <th class="text-end">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($users as $u): ?>
        <tr>
          <td><?php echo (int)$u['id']; ?></td>
          <td><?php echo htmlspecialchars($u['username']); ?></td>
          <td><?php echo htmlspecialchars($u['email']); ?></td>
          <td><?php echo htmlspecialchars($u['student_id']); ?></td>
          <td>
            <form method="post" action="?action=role" class="d-flex gap-2 align-items-center">
              <input type="hidden" name="id" value="<?php echo (int)$u['id']; ?>">
              <select name="role" class="form-select form-select-sm w-auto">
                <option value="student" <?php echo $u['role']==='student'?'selected':''; ?>>student</option>
                <option value="admin" <?php echo $u['role']==='admin'?'selected':''; ?>>admin</option>
              </select>
              <button class="btn btn-sm btn-outline-primary">Update</button>
            </form>
          </td>
          <td><?php echo htmlspecialchars($u['created_at']); ?></td>
          <td class="text-end">
            <form method="post" action="?action=delete" onsubmit="return confirm('Delete this user?')" class="d-inline">
              <input type="hidden" name="id" value="<?php echo (int)$u['id']; ?>">
              <button class="btn btn-sm btn-outline-danger" <?php echo ($u['id']==$_SESSION['user']['id'])?'disabled':''; ?>>Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__.'/partials/footer.php'; ?>
