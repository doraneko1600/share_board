<?php
require_once 'functions.php';

$pdo = connectDB();

$sql = 'SELECT * FROM images WHERE id = :id LIMIT 1';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', (int)$_GET['id'], PDO::PARAM_INT);
$stmt->execute();
$image = $stmt->fetch();
header('Content-type: ' .$image['image_name']);
header('Content-type: ' . $image['image_type']);
//header('Content-type: ' . $image['image_content']);
echo $image['image_content'];

unset($pdo);
exit();
?>