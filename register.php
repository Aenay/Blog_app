<?php 
session_start();
require 'config/config.php';
require 'config/common.php';

if ($_POST) {
  if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || strlen($_POST['password'] < 4) || empty($_FILES['image'])){
    if(empty($_POST['name'])){
      $nameError = 'Name cannot be null';
    }
    if(empty($_POST['email'])){
      $emailError = 'Email cannot be null';
    }
    if(empty($_POST['password'])){
      $passwordError = 'Password cannot be null';
    }
    if(strlen($_POST['password'] < 4 )){
      $passwordError = 'Password should be 4 characters at least ';
    }
    if(empty($_FILES['image'])){
      $imageError = 'Image cannot be null';
    }
  }else{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
    
    $stmt -> bindValue(':email',$email);
    $stmt -> execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($user){
        echo "<script>alert('Email Duplicated')</script>";
    }else{
      $file= 'userimages/'.($_FILES['image']['name']);
      $imageType=pathinfo($file,PATHINFO_EXTENSION);
      $image = $_FILES['image']['name'];
      move_uploaded_file($_FILES['image']['tmp_name'],$file);
      $stmt = $pdo->prepare("INSERT INTO users(name,email,image,password)VALUES(:name,:email,:image,:password)");
      $result = $stmt->execute(
        array(':name'=>$name,':email'=>$email,':image'=>$image,':password'=>$password)
      );
      if($result){
        echo "<script>alert('Successfully Register,You can now login');window.location.href='login.php'</script>";
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
  <title>Blog App | Log in</title>

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
      
      <form action="register.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
      <p style="color: red;"><?php echo empty($nameError) ?'' : $nameError ?></p>
      <div class="input-group mb-3">
          <input type="text" name="name" class="form-control" placeholder="Name">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <p style="color: red;"><?php echo empty($emailError) ?'' : $emailError ?></p>
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <p style="color: red;"><?php echo empty($passwordError) ?'' : $passwordError ?></p>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          
          <!-- /.col -->
          <div class="container">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
            <a href="login.php" class="btn btn-default btn-block">Login</a>
          </div>
          <p style="color: red;"><?php echo empty($imageError) ?'' : $imageError ?></p>
          <div class="form-group">
            <label for="">Image</label><br>
            <input type="file" name="image" value="">
          </div>
          <!-- /.col -->
        </div>
      </form>

     
      <!-- /.social-auth-links -->
      <!-- <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p> -->
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
