<?php 
require '../config/config.php';

$stmt = $pdo->prepare("DELETE FROM users WHERE id=".$_GET['id']);
$result = $stmt->execute();

header('Location: user.php');

?>