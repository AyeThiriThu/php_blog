<?php 
session_start();
if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
   header('Location: login.php');
  }
  require 'config/config.php';
  $id=$_GET['id'];
  $stmt=$pdo->prepare('SELECT * FROM posts WHERE id='.$_GET['id']);
  $stmt->execute();
  $result=$stmt->fetchAll();
  // print "<pre>";
  // print_r($result);
  // exit();
  
  $stmt1=$pdo->prepare('SELECT * FROM comments WHERE post_id='.$_GET['id']);
  $stmt1->execute();
  $showcomment=$stmt1->fetchAll();
  if($showcomment){
    $auId=$showcomment[0]['author_id'];
    $austmt=$pdo->prepare("SELECT * FROM users WHERE id=".$auId);
    $austmt->execute();
    $auResult=$austmt->fetchAll();  
  }
  

  if($_POST){
    $comment=$_POST['comment'];
    $stmt=$pdo->prepare("INSERT INTO comments(content,author_id,post_id) VALUES(:content,:author_id,:post_id);");
    $stmt->bindValue(':content',$comment);
    $stmt->bindValue(':author_id',$_SESSION['user_id']);
    $stmt->bindValue(':post_id',$_GET['id']);
    $result=$stmt->execute();

    if($result){
      header('Location: blogdetail.php?id='.$_GET['id']);
    }
  }
?>
 <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Blog Site</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="margin-left:0px !important;">
    <!-- Main content -->
    <section class="content">
      <div class="row">
          <div class="col-md-12">
            <!-- Box Comment -->
            <div class="card card-widget">
              <div class="card-header">
                <div class="card-title" style="text-align: center !important; float: none;">
                  <h4><?php echo $result[0]['title']; ?></h4>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <img class="img-fluid pad" src="images/<?php echo $result[0]['image']; ?>" alt="Photo" style="width : 100%;"><br><br>

                <p><?php echo $result[0]['content']; ?></p>
                <h3>Comments</h3>
                <hr>
              </div>
              <?php 
                foreach ($showcomment as $value) {
              ?>
               <!-- /.card-body -->
              <div class="card-footer card-comments">
                <div class="card-comment">
                  <div class="comment-text" style="margin-left:0px !important;">
                    <span class="username">
                      <?php 
                      $name=$pdo->prepare('SELECT name FROM users WHERE id='.$value['author_id']);
                      $name->execute();
                      $resultname=$name->fetch();
                      echo $resultname['name']; ?>
                      <span class="text-muted float-right"><?php echo $value['created_at']; ?></span>
                    </span><!-- /.username -->
                    <?php echo $value['content']; ?>
                  </div>
                  <!-- /.comment-text -->
                </div>
                <!-- /.card-comment -->
              </div>
              <?php
                  // code...
                }
               ?>
              <!-- /.card-footer -->
              <div class="card-footer">
                <form action="#" method="post">
                  <div class="img-push">
                    <input name="comment" type="text" class="form-control form-control-sm" placeholder="Press enter to post comment">    
                  </div>
                </form>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
        <div class="row">
          <div class="col-md-6">
            <div class="d-none d-sm-inline">
              <a href="logout.php" type="button" class="btn btn-default">LOGOUT</a>
            </div>
          </div>
          <div class="col-md-6">
            <a href="/ApBlog" type="button" class="float-right btn btn-warning">Go Back</a>
          </div>
          
        </div>
         
      </section>
    <!-- content -->
    <br>
    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer" style="margin-left: 0px !important;">
    <!-- Default to the left -->
    <strong>Copyright &copy; 2022 <span style='color:blue;'> AProgrammer</span></strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
