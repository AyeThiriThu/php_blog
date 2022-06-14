<?php
  session_start();
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location: login.php');
  }
    if ($_SESSION['role']!=1) {
    header('Location : login.php');
  }
  require '../config/config.php';
   if ($_POST) {
      // print_r($_POST);
      // exit();
      $name=$_POST['name'];
      $email=$_POST['email'];
      $password=$_POST['password']; 
      if(empty($_POST['admin'])){
          $role='0';
      }else{
          $role='1';
      }  
        
      $stmtforemail=$pdo->prepare('SELECT * FROM users WHERE email=:email');
      $stmtforemail->bindValue(':email',$email);
      $stmtforemail->execute();
      $sameemail=$stmtforemail->fetch(PDO::FETCH_ASSOC);
      if(empty($sameemail)){
        $stmt=$pdo->prepare('INSERT INTO users(name,email,password,role) VALUES (:name,:email,:password,:role);');
        $stmt->bindValue(':name',$name);
        $stmt->bindValue(':email',$email);
        $stmt->bindValue(':password',$password);
        $stmt->bindValue(':role',$role);
        $result=$stmt->execute();
        if($result){
          echo "<script>alert('Successfully Added!'); </script>";
        }  
      }else{
        echo "<script>alert('User already exits.Please use another email!');window.location.href='users.php';</script>";
      }
      
    } 
?>
 <?php include('header.php'); ?>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
         <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <form action="addusers.php" method="post">
                <div class="form-group">
                  <label for="name">Name</label><br>
                  <input type="text" class="form-control" name="name" required>
                </div>
                <div class="form-group">
                 <label for="email">Email</label><br>
                 <input type="email" class="form-control" name="email" required>
                </div>
                <div class="form-group">
                 <label for="password">Password</label><br>
                 <input type="password" class="form-control" name="password" required>
                </div>
                <div class="form-group">
                 <label for="admin">Admin</label><br>
                 <input type="checkbox" name="admin">
                </div>
                <div class="form-group">
                  <input type="submit" class="btn btn-success" name="submit" value="SUBMIT">
                  <a href="users.php" class="btn btn-primary" style="float:right;">BACK</a>
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






