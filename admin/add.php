<?php 
require '../config/config.php';
require '../config/common.php';
session_start();

// Redirect if not admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    echo "<script>alert('Access Denied! Admins only.'); window.location.href='../index.php';</script>";
    exit;
}

$titleError = $contentError = $imageError = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $image = $_FILES['image'];

    if (empty($title)) {
        $titleError = 'Title cannot be null';
    }
    if (empty($content)) {
        $contentError = 'Content cannot be null';
    }
    if (empty($image['name'])) {
        $imageError = 'Image cannot be null';
    }

    // Image validation
    if (!empty($image['name'])) {
        $allowedExtensions = ['png', 'jpg', 'jpeg'];
        $fileExtension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedExtensions)) {
            $imageError = "Image must be PNG, JPG, or JPEG.";
        }
    }

    // Proceed if no errors
    if (empty($titleError) && empty($contentError) && empty($imageError)) {
        $imagePath = 'images/' . basename($image['name']);
        move_uploaded_file($image['tmp_name'], $imagePath);

        // Insert into database using prepared statements
        $stmt = $pdo->prepare("INSERT INTO posts (title, content, image, author_id) VALUES (:title, :content, :image, :author_id)");
        $result = $stmt->execute([
            ':title' => htmlspecialchars($title, ENT_QUOTES, 'UTF-8'),
            ':content' => htmlspecialchars($content, ENT_QUOTES, 'UTF-8'),
            ':image' => $image['name'],
            ':author_id' => $_SESSION['user_id']
        ]);

        if ($result) {
            echo "<script>alert('Successfully added');window.location.href='index.php'</script>";
        }
    }
}
?>

<?php include('header.php'); ?>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="add.php" method="post" enctype="multipart/form-data">
                            <!-- CSRF Token -->
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                            <div class="form-group">
                                <label for="title">Title</label>
                                <p style="color: red;"><?php echo $titleError; ?></p>
                                <input type="text" class="form-control" name="title">
                            </div>
                            <div class="form-group">
                                <label for="content">Content</label><br>
                                <p style="color: red;"><?php echo $contentError; ?></p>
                                <textarea class="form-control" name="content" rows="8"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="image">Image</label><br>
                                <p style="color: red;"><?php echo $imageError; ?></p>
                                <input type="file" name="image">
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-success" value="Submit">
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

<?php include('footer.html'); ?>
