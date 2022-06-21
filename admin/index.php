<?php
session_start();
 // print "<pre>";
 // print_r($_SESSION);
 //  exit();
  require '../config/common.php';


  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
   header('Location: login.php');
  }
  // if($_SESSION['role']!=1){
  //   header('Location :login.php');
  // }
 
  if(!empty($_POST['search'])){
    setcookie('search',$_POST['search'],time()+(86400*30),"/");
  }else{                            //delete cookie, otherwise other page will get cookie 
    if(empty($_GET['pageno'])){
      unset($_COOKIE['search']);
      setcookie('search',null,-1,'/');
    }
  }
  require '../config/config.php';
  if(!empty($_GET['pageno'])){
    $pageno=$_GET['pageno'];
  }else{
    $pageno=1;
  }
  $numOfRecs=5;
  $offset=($pageno-1)*$numOfRecs;
  if(empty($_POST['search']) && empty($_COOKIE['search'])){
    $stmt=$pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
    $stmt->execute();
    $rawresult=$stmt->fetchAll();
    $totalpages=ceil(count($rawresult)/$numOfRecs);

    $stmt1=$pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$numOfRecs ");
    $stmt1->execute();
    $result=$stmt1->fetchAll();
    //print '<pre>';
     //print_r($result);
  }else{
    if(empty($_GET['pageno'])){
      $searchKey=$_POST['search'] ;
    }else{
      $searchKey=$_COOKIE['search'];
    }
    // $searchKey=$_POST['search'] ? $_POST['search'] : $_COOKIE['search'];
    $stmt=$pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC");
    $stmt->execute();
    $rawresult=$stmt->fetchAll();
    //print_r($rawresult);
    $totalpages=ceil(count($rawresult)/$numOfRecs);

    $stmt1=$pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfRecs ");
    $stmt1->execute();
    $result=$stmt1->fetchAll();
  }   
?>
 <?php include('header.php'); ?>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
         <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Blog Listing</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div>
                 <a href="add.php" type="button" class="btn btn-success">New Blog Post</a> 
                </div><br>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Title</th>
                      <th>Content</th>
                      <th style="width: 40px">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    $i=1;
                    if($result){
                      foreach ($result as $value) {
                        ?>
                        <tr>
                          <td><?php echo $i++; ?></td>
                          <td><?php echo escape($value['title']); ?></td>
                          <td><?php echo escape(substr($value['content'],0,50)); ?></td>
                          <td>
                          <div class="btn-group">
                            <div class="container">
                              <a href="edit.php?id=<?php echo $value['id'];?>&pageno=<?php echo $pageno ?>" type="button" class="btn btn-warning">Edit</a>
                            </div>
                            <div class="container">
                              <a href="delete.php?id=<?php echo $value['id'];?>&pageno=<?php echo $pageno ?>" type="button" class="btn btn-danger" onclick="return confirm ('Are you sure want to delete')">Delete</a>
                            </div>
                          </div>
                          </td>
                        </tr>
                        <?php
                      } 
                    }?>
                  </tbody>
                </table>
                <nav aria-label="Page navigation example" style="float:right">
                <ul class="pagination">
                  <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                  <li class="page-item <?php if($pageno<=1){echo 'disabled';} ?>">
                    <a class="page-link" href="<?php if($pageno<=1){echo '#';}else{echo '?pageno='.($pageno-1);} ?>">Previous</a>
                  </li>
                  <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
                  <li class="page-item <?php if($pageno>=$totalpages){echo 'disabled';} ?>">
                    <a class="page-link" href="<?php if($pageno>=$totalpages){echo '#';}else{echo '?pageno='.($pageno+1);} ?>">Next</a>
                  </li>
                  <li class="page-item"><a class="page-link" href="?pageno=<?php echo $totalpages; ?>">Last</a></li>  
                </ul>
              </nav>
              </div>
              <!-- /.card-body --> 
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



