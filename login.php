<?php require_once __DIR__.'/config.php';
$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
  $username=trim($_POST['username']??'');
  $password=$_POST['password']??'';
  if($username===''||$password==='') $errors[]='Username and password required';
  if(!$errors){
    $stmt=$conn->prepare('SELECT id,username,email,student_id,password_hash,role FROM users WHERE username=?');
    $stmt->bind_param('s',$username);
    $stmt->execute();
    $result=$stmt->get_result();
    $user=$result->fetch_assoc();
    if($user && password_verify($password,$user['password_hash'])){
      $_SESSION['user']=['id'=>$user['id'],'username'=>$user['username'],'role'=>$user['role']];
      redirect('/cat_24rp08520/dashboard.php');
    } else {
      $errors[]='Wrong username or password';
    }
  }
}
include __DIR__.'/partials/header.php'; ?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <h3>Login</h3>
    <?php if($errors): ?><div class="alert alert-danger"><?php echo implode('<br>',array_map('htmlspecialchars',$errors)); ?></div><?php endif; ?>
    <form method="post" class="needs-validation" novalidate>
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input name="username" class="form-control" required>
        <div class="invalid-feedback">Username required</div>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
        <div class="invalid-feedback">Password required</div>
      </div>
      <button class="btn btn-primary">Login</button>
    </form>
  </div>
</div>
<script>
(() => { 'use strict'; const forms = document.querySelectorAll('.needs-validation'); Array.from(forms).forEach(form => { form.addEventListener('submit', event => { if (!form.checkValidity()) { event.preventDefault(); event.stopPropagation(); } form.classList.add('was-validated'); }, false);});})();
</script>
<?php include __DIR__.'/partials/footer.php'; ?>