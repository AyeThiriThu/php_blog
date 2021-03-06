<?php  
session_start();
require 'config/config.php';
require 'config/common.php';

if(!empty($_POST)){
  if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || strlen($_POST['password'])<4){
    if(empty($_POST['name'])){
      $nameError='Name cannot be null';
    }
    if(empty($_POST['email'])){
      $emailError='Email cannot be null';
    }
    if(empty($_POST['password'])){
      $passwordError='Password cannot be null';
    }
    if(strlen($_POST['password'])<4){
      $passwordError="Password should be at least 4 characters!";
    }
  }else{
    $name=$_POST['name'];
    $email=$_POST['email'];
    $pass=password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt=$pdo->prepare("SELECT * from users WHERE email=:email");
    $stmt->bindValue(":email",$email);
    $stmt->execute();
    $result=$stmt->fetch(PDO::FETCH_ASSOC);
    //print_r($result);
    if($result){
      echo "<script>alert('This email already exits.Use another email to register!');</script>";
    }else{
      $stmt=$pdo->prepare("INSERT INTO users(name,email,password,role) VALUES(:name,:email,:password,0)");
      $stmt->bindValue(':name',$name);
      $stmt->bindValue(':email',$email);
      $stmt->bindValue(':password',$pass);
      $result=$stmt->execute();
      if($result){
        $_SESSION['role']=0;
        echo "<script>alert('Successfully Registered! You can now login!');window.location.href='login.php';</script>";
      }

    }    
  }
  
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Blog | Register</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>Blog</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Register New Account</p>

      <form action="register.php" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Name" name="name">
          
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <p style="color:red"><?php echo empty($nameError) ? '' : '*'.$nameError; ?></p>
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email" name="email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>  
        <p style="color:red"><?php echo empty($emailError) ? '' : '*'.$emailError; ?></p>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name="password"> 
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <p style="color:red"><?php echo empty($passwordError) ? '' : '*'.$passwordError; ?></p>
        <div class="row">
          <div class="container">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
            <a href="login.php" class="text-center btn btn-default btn-block">Login</a>
          </div>
          <!-- /.col -->
        </div>
      </form>
     
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
