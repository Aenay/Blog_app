<?php 
session_start();

require '../config/config.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    echo "<script>alert('Access Denied! Admins only.'); window.location.href='../index.php';</script>";
}

?>

<?php include ('header.php') ?>

            <!-- Main Content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <a href="adduser.php" class="btn btn-success">New User</a>
                                </div>
                                <div class="card-body">
                                    <?php 
                                    if(!empty($_GET['pageno'])){
                                        $pageno = $_GET['pageno'];
                                    }else{
                                        $pageno= 1;
                                    }
                                    $numOfrecs = 5;
                                    $offset = ($pageno - 1 )* $numOfrecs;

                                    if(empty($_POST['search'])){
                                        $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC");
                                        $stmt->execute();
                                        $rawResult = $stmt->fetchAll();
                                        $total_pages = ceil(count($rawResult) / $numOfrecs); 
    
    
                                        $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC LIMIT $offset,$numOfrecs");
                                        $stmt->execute();
                                        $result = $stmt->fetchAll();
                                    }else{
                                        $searchKey = $_POST['search'];
                                        $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$searchKey%' ORDER BY id DESC");
                                        $stmt->execute();
                                        $rawResult = $stmt->fetchAll();
                                        $total_pages = ceil(count($rawResult) / $numOfrecs); 
    
    
                                        $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfrecs");
                                        $stmt->execute();
                                        $result = $stmt->fetchAll();
                                    }
                                    ?>

                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px">#</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Password</th>
                                                <th style="width: 40px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            if ($result) {
                                                $i = 1;
                                                foreach ($result as $value) {
                                            ?>
                                                <tr>
                                                    <td><?php echo $i; ?></td>
                                                    <td><?php echo $value['name']; ?></td>
                                                    <td><?php echo substr($value['email'], 0, 50); ?></td>
                                                    <td><?php echo substr($value['password'], 0, 50); ?></td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <div class="container">
                                                                <a href="edituser.php?id=<?php echo $value['id']; ?>" class="btn btn-warning">Edit</a>
                                                            </div>
                                                            <div class="container">
                                                              <a href="userdelete.php?id=<?php echo $value['id']; ?>" onclick="return confirm('Are you sure you want to delete this content')" class="btn btn-danger">Delete</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php 
                                                $i++;
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-wrapper -->

        <?php include('footer.html'); ?>
    </div>
</body>
</html>
