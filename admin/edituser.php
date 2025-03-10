<?php 
session_start();
require '../config/config.php';
require '../config/common.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
  echo "<script>alert('Access Denied! Admins only.'); window.location.href='../index.php';</script>";
}



if($_POST){
  if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || strlen($_POST['password'] < 4)){
    if(empty($_POST['name'])){
      $titleError = 'Name cannot be null';
    }
    if(empty($_POST['email'])){
      $contentError = 'Email cannot be null';
    }
    if(empty($_POST['password'])){
        $imageError = 'Password cannot be null';
    }
    if(strlen($_POST['password'] < 4 )){
        $passwordError = 'Password should be 4 characters at least ';
    }
  }else{
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password  = password_hash($_POST['password'],PASSWORD_DEFAULT);
    
    if($_FILES['image']['name'] != null){
        $file= 'images/'.($_FILES['image']['name']);
        $imageType=pathinfo($file,PATHINFO_EXTENSION);
      
        if($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg'){
          echo "<script>alert('Image must be png,jpg,jpeg')</script>";

        }else{
          $image = $_FILES['image']['name'];
          move_uploaded_file($_FILES['image']['tmp_name'],$file);
      
            $stmt = $pdo->prepare("UPDATE users SET name='$name',email='$email',image='$image',password = '$password' WHERE id='$id'");
            $result = $stmt->execute();
          if($result){
            echo "<script>alert('Successfully added');window.location.href='index.php'</script>";

          }
      
        }

    }else{
        $stmt = $pdo->prepare("UPDATE users SET name='$name',email='$email',password= '$password' WHERE id='$id'");
        $result = $stmt->execute();
         if($result){
                echo "<script>alert('Successfully added');window.location.href='user.php'</script>";
           }
    }

  }
    
        
}
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=".$_GET['id']);
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
                                <label for="">Name</label><p style="color: red;"><?php echo empty($nameError) ?'' : $nameError ?></p>
                                <input type="text" class="form-control" name="name" value="<?php echo escape($result[0]['name'])?>" >
                            </div>
                            <div class="form-group">
                                <label for="">Email</label><br><p style="color: red;"><?php echo empty($emailError) ?'' : $emailError ?></p>
                                <input class="form-control" name="email" value="<?php echo escape($result[0]['email'])?>">
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="id" value="<?php echo $result[0]['password']?>">
                                <label for="">Password</label><p style="color: red;"><?php echo empty($passwordError) ?'' : $passwordError ?></p>
                                <input class="form-control" name="password" value="" >
                            </div>
                            <div class="form-group">
                                <label for="">Image</label><br><p style="color: red;">
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