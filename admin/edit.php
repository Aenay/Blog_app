<?php 
session_start();
require '../config/config.php';
require '../config/common.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
  echo "<script>alert('Access Denied! Admins only.'); window.location.href='../index.php';</script>";
}



if($_POST){
  if(empty($_POST['title']) || empty($_POST['content']) || empty($_FILES['image'])){
    if(empty($_POST['title'])){
      $titleError = 'Title cannot be null';
    }
    if(empty($_POST['content'])){
      $contentError = 'Content cannot be null';
    }
    if(empty($_FILES['image'])){
      $imageError = 'Image cannot be null';
    }
  }else{
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    if($_FILES['image']['name'] != null){
        $file= 'images/'.($_FILES['image']['name']);
        $imageType=pathinfo($file,PATHINFO_EXTENSION);
      
        if($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg'){
          echo "<script>alert('Image must be png,jpg,jpeg')</script>";

        }else{
          $image = $_FILES['image']['name'];
          move_uploaded_file($_FILES['image']['tmp_name'],$file);
      
            $stmt = $pdo->prepare("UPDATE posts SET title='$title',content='$content',image='$image' WHERE id='$id'");
            $result = $stmt->execute();
          if($result){
            echo "<script>alert('Successfully added');window.location.href='index.php'</script>";

          }
      
        }

    }else{
        $stmt = $pdo->prepare("UPDATE posts SET title='$title',content='$content' WHERE id='$id'");
        $result = $stmt->execute();
         if($result){
                echo "<script>alert('Successfully added');window.location.href='index.php'</script>";
           }
    }

  }
    
        
}
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);
$stmt->execute();
$result = $stmt->fetchAll();
?>


<?php  include('header.php')?>


            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="card">
                        <div class="card-body">
                        <form class="" action="" method="post" enctype="multipart/form-data">
                         <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <div class="form-group">
                                <input type="hidden" name="id" value="<?php echo $result[0]['id']?>">
                                <label for="">Title</label><p style="color: red;"><?php echo empty($titleError) ?'' : $titleError ?></p>
                                <input type="text" class="form-control" name="title" value="<?php echo escape($result[0]['title'])?>" >
                            </div>
                            <div class="form-group">
                                <label for="">Content</label><br><p style="color: red;"><?php echo empty($contentError) ?'' : $contentError ?></p>
                                <textarea class="form-control" name="content" rows="8" cols="88"><?php echo escape($result[0]['content'])?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Image</label><br><p style="color: red;"><?php echo empty($imageError) ?'' : $imageError ?></p>
                                <img src="images/<?php echo $result[0]['image']?>" width="150" height="150"><br><br>
                                <input type="file" name="image" value="">
                            </div>
                            <div class="form-group">
                              <input type="submit" class="btn btn-success" name="" value="Submit">
                              <a href="index.php" class="btn btn-warning">Back</a>
                            </div>

                        </form>
                        </div>
                        <!-- /.card-body -->
                      </div>
                    </div>
                    <!-- /.card -->
                  </div>

                  </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
<?php include('footer.html')?>