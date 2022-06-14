<?php  
session_start();
require 'config/config.php';


if(!empty($_POST)){
	$email=$_POST['email'];
	$pass=$_POST['password'];
	$stmt=$pdo->prepare("SELECT * FROM users where email=:emailenter");
	$stmt->bindValue(':emailenter',$email);
	$stmt->execute();
	$result=$stmt->fetch(PDO::FETCH_ASSOC);
	// print_r($result);
	// exit();
	if($result){
		if($result['password']==$pass){
			$_SESSION['user_id']=$result['id'];
			$_SESSION['user_name']=$result['name'];
      $_SESSION['role']=0;
			$_SESSION['logged_in']=time();
			header('Location:index.php');
		}else{
			echo "<script>alert('Password Incorrect!');</script>";
		}
	}else{
		echo "<script>alert('Incorrect credentials');</script>";
	}


}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Blog | Log in</title>

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
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="login.php" method="post">
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email" name="email" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name="password" required> 
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="container">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            <a href="register.php" class="text-center btn btn-default btn-block">Register</a>
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