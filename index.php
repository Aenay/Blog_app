<?php 
require 'config/config.php';
session_start();
if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])){
  header('Location: login.php');
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Blog</title>

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
  <div class="content-wrapper" style="margin-left: 0% !important;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <h1 style="text-align: center;">Blog site</h1>
      </div><!-- /.container-fluid -->
    </section>

    <section>
    <?php 
    if(!empty($_GET['pageno'])){
      $pageno = $_GET['pageno'];
  }else{
      $pageno= 1;
  }
  $numOfrecs = 6;
  $offset = ($pageno - 1 )* $numOfrecs;

  if(empty($_POST['search'])){
      $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
      $stmt->execute();
      $rawResult = $stmt->fetchAll();
      $total_pages = ceil(count($rawResult) / $numOfrecs); 


      $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$numOfrecs");
      $stmt->execute();
      $result = $stmt->fetchAll();
  }else{
      $searchKey = $_POST['search'];
      $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC");
      $stmt->execute();
      $rawResult = $stmt->fetchAll();
      $total_pages = ceil(count($rawResult) / $numOfrecs); 


      $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfrecs");
      $stmt->execute();
      $result = $stmt->fetchAll();
  }
    
    ?>

    <!-- Main content -->
    <div class="row">
    <?php 
    if ($result) {
        $i = 1;
        foreach ($result as $value) {
    ?>
        <div class="col-md-4">
            <!-- Box Comment -->
            <div class="card card-widget">
              <div class="card-header">
                <div style="text-align:center !important; float: none;" class="card-title">
                  <h4><?php echo escape($value['title']) ?></h4>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <a href="blogdetail.php?id=<?php echo $value['id'];?>">
                  <img class="img-fluid pad" src="admin/images/<?php echo $value['image']?>" style="height: 300px;">
                </a> 
              </div>
              <!-- /.card-body -->
              
            </div>
            <!-- /.card -->
          </div>
    <?php 
        $i++;
        }
    }
    ?>
   
    </section>
    <div>
      <nav aria-label="Page navigation example" style="float: right;">
        <ul class="pagination">
            <li class="page-item"><a  class="page-link" href="?pageno=1">First</a></li>
            <li class="page-item <?php if( $pageno <= 1){ echo 'disabled';}?>">
                <a  class="page-link" href="<?php if($pageno <= 1){ echo '#';}else{ echo "?pageno=".($pageno-1);}?>">Previous</a></li>
            <li class="page-item"><a  class="page-link" href="#"><?php echo $pageno;?></a></li>
            <li class="page-item <?php if( $pageno >= $total_pages ){ echo 'disabled';}?>">
                <a  class="page-link" href="<?php if($pageno >= $total_pages){ echo '#';}else{ echo "?pageno=".($pageno+1);}?>">Next</a></li>
            <li class="page-item"><a  class="page-link" href="?pageno = <?php echo $total_pages?>">Last</a></li>
        </ul>
      </nav>
    </div><br><br>
        
  </div>
    <!-- /.content -->

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer" style="margin-left: 0px !important">
    <div class="float-right d-none d-sm-block">
      <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
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
