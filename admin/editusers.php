<?php 
session_start();
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location: login.php');
  }
    if ($_SESSION['role']!=1) {
    header('Location : login.php');
  }
	require '../config/config.php';
  require '../config/common.php';
	
	if ($_POST) {
		if(empty($_POST['name']) || empty($_POST['email'])){
      if(empty($_POST['name'])){
        $nameError='Name cannot be null';
      }
      if(empty($_POST['email'])){
        $emailError='Email cannot be null';
      }
    }elseif (!empty($_POST['password']) && strlen($_POST['password'])<4) {
        $passwordError="Password should be at least 4 characters!";	
    }else{
			$id=$_POST['id'];
      $name=$_POST['name'];
      $email=$_POST['email'];
      $password=password_hash($_POST['password'],PASSWORD_DEFAULT);
      if(empty($_POST['admin'])){
          $role='0';
      }else{
          $role='1';
      }  
      $stmtforemail=$pdo->prepare('SELECT * FROM users WHERE email=:email AND id!=:id');
      $stmtforemail->bindValue(':email',$email);
      $stmtforemail->bindValue(':id',$id);
      $stmtforemail->execute();
      $sameemail=$stmtforemail->fetch(PDO::FETCH_ASSOC);
      if(empty($sameemail)){
      	if($password!=null){
      		$stmt=$pdo->prepare("UPDATE users SET name='$name',email='$email',password='$password' ,role='$role' WHERE id=".$_GET['id']);
      	}else{
      		$stmt=$pdo->prepare("UPDATE users SET name='$name',email='$email',role='$role' WHERE id=".$_GET['id']);
      	}
	      
	      $result1=$stmt->execute();
	      if($result1){
	        header('Location: users.php?pageno='.$_GET['pageno']);
	      }
    	}else{
        echo "<script>alert('User already exits.Please use another email!');</script>";
      }
    }
   		
   } 
	
	$stmt=$pdo->prepare("SELECT * FROM users WHERE id=".$_GET['id']);
	$stmt->execute();
	$result=$stmt->fetchAll();
	// print "<pre>";
	// print_r($result);
	// exit();
?>

<?php include('header.php'); ?>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
         <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="id" value="<?php echo $result[0]['id']; ?>">
                <div class="form-group">
                  <label for="name">Name</label><br>
                  <p style="color:red"><?php echo empty($nameError) ? '' : '*'.$nameError; ?></p>
                  <input type="text" class="form-control" name="name" value="<?php echo $result[0]['name'] ?>" >
                </div>
                <div class="form-group">
                 <label for="email">Email</label><br>
                 <p style="color:red"><?php echo empty($emailError) ? '' : '*'.$emailError; ?></p>
                 <input type="email" class="form-control" name="email" value="<?php echo $result[0]['email'] ?>" >
                </div>
                <div class="form-group">
                 <label for="password">Password</label><br>
                 <p style="color:red"><?php echo empty($passwordError) ? '' : '*'.$passwordError; ?></p>
                 <span style="font-size: 10px;">The user already has a password</span>
                 <input type="password" class="form-control" name="password">
                </div>
                <div class="form-group">
                 <label for="admin">Admin</label><br>
                 <input type="checkbox"  name="admin" <?php if($result[0]['role']=='1'){echo 'checked';} ?>>
                </div>
                <div class="form-group">
                  <input type="submit" class="btn btn-success" name="submit" value="SUBMIT">
                  <a href="users.php?pageno=<?php echo $_GET['pageno']; ?>" class="btn btn-primary" style="float:right;">BACK</a>
                </div>
                </form>
              </div>
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col --> 
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  
  <?php include('footer.html'); ?>
