<?php 
require 'config/config.php';
session_start();
if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
  header('Location: login.php');
}


$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);
$stmt->execute();
$result = $stmt->fetchAll();
$blogId= $_GET['id'];

$stmtcmt = $pdo->prepare("SELECT * FROM comments WHERE post_id=$blogId");
$stmtcmt ->execute();
$cmtResult = $stmtcmt->fetchAll();


$auResult = [];
if($cmtResult){
  foreach ( $cmtResult as $key => $value){
    $authorId = $cmtResult[$key]['author_id'];
    $stmtau = $pdo->prepare("SELECT * FROM users WHERE id=$authorId");
    $stmtau ->execute();
    $auResult[] = $stmtau->fetchAll();
  }
}

if ($_POST) {
  $comment = $_POST['comment'];
  $stmt = $pdo->prepare("INSERT INTO comments(content,author_id,post_id)VALUES(:content,:author_id,:post_id)");
  $result = $stmt->execute(
    array(':content'=>$comment,':author_id'=>$_SESSION['user_id'],':post_id'=>$blogId)
   );
  if($result){
    header( 'Location: blogdetail.php?id='.$blogId);
  }
  
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Blog Details</title>

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
  <div class="">
    <!-- Content Header (Page header) -->


    <!-- Main content -->
    <div class="row">
          <div class="col-md-12">
            <!-- Box Comment -->
            <div class="card card-widget">
              <div class="card-header">
                <div style="text-align:center !important; float: none;" class="card-title">
                 
                  <h4>Blog Title</h4>
                </div>
                <!-- /.user-block -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <img class="img-fluid pad" src="admin/images/<?php echo $result[0]['image']?>">

                <p><?php echo $result[0]['content'];?></p>
                <button type="button" class="btn btn-default btn-sm"><i class="fas fa-share"></i> Share</button>
                <button type="button" class="btn btn-default btn-sm"><i class="far fa-thumbs-up"></i> Like</button>
                <span class="float-right text-muted">127 likes - 3 comments</span>
              </div>
              <!-- /.card-body -->
              <div class="card-footer card-comments">
                <div class="card-comment">
                  <!-- User image -->
                  <?php
                    if($cmtResult){
                  ?> 
                    <div class="comment-text">
                      <?php
                        foreach ($cmtResult as $key => $value){
                      ?>
                      <span class="username">
                        <?php print_r( $auResult[$key][0]['name']); ?>
                        <span class="text-muted float-right"><?php echo $value['created_at']  ?></span>
                      </span><!-- /.username -->
                      <?php echo $value['content']  ?>
                      <?php    
                        }
                      ?>
                    </div>   
                  <?php
                  }
                  ?>
                  <!-- /.comment-text -->
                </div>
                <!-- /.card-comment -->
                
              </div>
              <!-- /.card-footer -->
              <div class="card-footer">
                <form action="" method="post">
                  <img class="img-fluid img-circle img-sm" src="dist/img/user4-128x128.jpg" alt="Alt Text">
                  <!-- .img-push is used to add margin to elements next to floating images -->
                  <div class="img-push">
                    <input type="text" name="comment" class="form-control form-control-sm" placeholder="Press enter to post comment">
                  </div>
                </form>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
        </div>
    <!-- /.content -->

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer" style="margin-left: 0px !important;">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
        <a href="/blog"  class="btn btn-warning">Go Back</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2025 <a href="#">Jackae.co</a>.</strong> All rights reserved.
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
