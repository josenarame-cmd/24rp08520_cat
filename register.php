<?php require_once __DIR__.'/config.php';
$errors=[]; $success='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $username=trim($_POST['username']??'');
  $email=trim($_POST['email']??'');
  $student_id=trim($_POST['student_id']??'');
  $password=$_POST['password']??'';
  if($username==='') $errors[]='Username required';
  if(!filter_var($email,FILTER_VALIDATE_EMAIL)) $errors[]='Valid email required';
  if($student_id==='') $errors[]='Student ID required';
  if(strlen($password)<6) $errors[]='Password must be at least 6 characters';
  if(!$errors){
    $stmt=$conn->prepare('SELECT id FROM users WHERE username=? OR email=?');
    $stmt->bind_param('ss',$username,$email);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows>0){ $errors[]='Username or email already exists'; }
    $stmt->close();
  }
  if(!$errors){
    $hash=password_hash($password,PASSWORD_DEFAULT);
    $role='student';
    $stmt=$conn->prepare('INSERT INTO users(username,email,student_id,password_hash,role) VALUES(?,?,?,?,?)');
    $stmt->bind_param('sssss',$username,$email,$student_id,$hash,$role);
    $stmt->execute();
    $success='Registration successful. Please login.';
  }
}
include __DIR__.'/partials/header.php'; ?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <h3>Student Registration</h3>
    <?php if($errors): ?><div class="alert alert-danger"><?php echo implode('<br>',array_map('htmlspecialchars',$errors)); ?></div><?php endif; ?>
    <?php if($success): ?><div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
    <form method="post" class="needs-validation" novalidate>
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input name="username" class="form-control" required>
        <div class="invalid-feedback">Please enter username</div>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
        <div class="invalid-feedback">Please enter valid email</div>
      </div>
      <div class="mb-3">
        <label class="form-label">Student ID</label>
        <input name="student_id" class="form-control" required>
        <div class="invalid-feedback">Student ID required</div>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" minlength="6" required>
        <div class="invalid-feedback">Min 6 characters</div>
      </div>
      <button class="btn btn-primary">Register</button>
    </form>
  </div>
</div>
<script>
(() => { 'use strict'; const forms = document.querySelectorAll('.needs-validation'); Array.from(forms).forEach(form => { form.addEventListener('submit', event => { if (!form.checkValidity()) { event.preventDefault(); event.stopPropagation(); } form.classList.add('was-validated'); }, false);});})();
</script>
<?php include __DIR__.'/partials/footer.php'; ?>