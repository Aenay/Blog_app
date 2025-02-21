<?php 
require '../config/config.php';

$stmt = $pdo->prepare("DELETE FROM posts WHERE id=".$_GET['id']);
$result = $stmt->execute();

header('Location: index.php');

?>