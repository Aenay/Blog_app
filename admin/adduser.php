<?php 
session_start();
require '../config/config.php';
require '../config/common.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
  echo "<script>alert('Access Denied! Admins only.'); window.location.href='../index.php';</script>";
}

if($_POST){
  if(empty($_POST['name']) || empty($_POST['email']) || empty($_FILES['image']) || empty($_POST['password'])){
    if(empty($_POST['name'])){
      $nameError = 'Name cannot be null';
    }
    if(empty($_POST['email'])){
      $emailError = 'Email cannot be null';
    }
    if(empty($_FILES['image'])){
      $imageError = 'Image cannot be null';
    }
    if(empty($_POST['password'])){
        $passwordError = 'Password cannot be null';
    }
  }else{
    $file= 'images/'.($_FILES['image']['name']);
    $imageType=pathinfo($file,PATHINFO_EXTENSION);

    if($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg'){
      echo "<script>alert('Image must be png,jpg,jpeg')</script>";
    }else{
      $name = $_POST['name'];
      $email = $_POST['email'];
      $image = $_FILES['image']['name'];
      move_uploaded_file($_FILES['image']['tmp_name'],$file);
      $password  = password_hash($_POST['password'],PASSWORD_DEFAULT);

      $stmt = $pdo->prepare("INSERT INTO users(name,email,image,password)VALUES(:name,:email,:image,:password)");
      $result = $stmt->execute(
        array(':name'=>$name,':email'=>$email,':image'=>$image,':password'=>$password)
      );
      if($result){
        echo "<script>alert('Successfully added');window.location.href='user.php'</script>";
      }

    }
  }
}

?>


<?php  include('header.php')?>


            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="card">
                        <div class="card-body">
                        <form class="" action="adduser.php" method="post" enctype="multipart/form-data">
                          <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <div class="form-group">
                                <label for="">Name</label> <p style="color: red;"><?php echo empty($nameError) ?'' : $nameError ?></p>
                                <input type="text" class="form-control" name="name" value="">
                            </div>
                            <div class="form-group">
                                <label for="">Email</label><br> <p style="color: red;"><?php echo empty($emailError) ?'' : $emailError ?></p>
                                <input type="text" class="form-control" name="email" value="">
                            </div>
                            <div class="form-group">
                                <label for="">Password</label><br><p style="color: red;"><?php echo empty($passwordError) ?'' : $passwordError ?></p>
                                <input type="text" class="form-control" name="password" value="">
                            </div>
                            <div class="form-group">
                                <label for="">Image</label><br><p style="color: red;"><?php echo empty($imageError) ?'' : $imageError ?></p>
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