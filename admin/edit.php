<?php 
session_start();
  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
    header('Location: login.php');
  }
    if ($_SESSION['role']!=1) {
    header('Location : login.php');
  }
	require '../config/config.php';
	
	if($_POST){	
	
		$title=$_POST['title'];
		$content=$_POST['content'];
		$id=$_POST['id'];
		if($_FILES['image']['name']!=null){
			$file='../images/'.$_FILES['image']['name'];
			$imageType=pathinfo($file,PATHINFO_EXTENSION);
			if($imageType!='png' && $imageType!='jpg' && $imageType!='jpeg'){
				echo "<script>alert('Image must be png,jpg or jpeg!');</script>";
			}else{
				$image=$_FILES['image']['name'];	
		 		move_uploaded_file($_FILES['image']['tmp_name'], $file);
		 		$stmt1=$pdo->prepare("UPDATE posts SET title='$title', content='$content', image='$image' WHERE id='$id';");
		 		$result1=$stmt1->execute();
		 		if($result1){
		 			
      		header('Location: index.php?pageno='.$_GET['pageno']);
    		}	
			}
		 	
		}else{
			$stmt1=$pdo->prepare("UPDATE posts SET title='$title', content='$content' WHERE id='$id';");
			$result1=$stmt1->execute();
			if($result1){
      	header('Location: index.php?pageno='.$_GET['pageno']);
    	}
		}
		
	}
	$stmt=$pdo->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);
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
                <input type="hidden" name="id" value="<?php echo $result[0]['id']; ?>">
                <div class="form-group">
                  <label for="title">Title</label><br>
                  <input type="text" class="form-control" name="title" value="<?php echo $result[0]['title'] ?>" required>
                </div>
                <div class="form-group">
                 <label for="content">Content</label><br>
                 <textarea name="content" rows="8" class="form-control"><?php echo $result[0]['content']; ?></textarea>
                </div>
                <div class="form-group">
                  <label for="image">Image</label><br>

                  <img src="../images/<?php echo $result[0]['image']; ?>" width="150px"; height="100px" ><br><br>
                  <input type="file" name="image" ><br>
                </div>
                <div class="form-group">
                  <input type="submit" class="btn btn-success" name="submit" value="SUBMIT">
                  <a href="index.php?pageno=<?php echo $_GET['pageno']; ?>" class="btn btn-primary" style="float:right;">BACK</a>
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
