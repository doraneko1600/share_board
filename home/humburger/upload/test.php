<!-- ファイル容量が過多であったときの確認用（開発中） -->
<?php
require_once('functions.php');

$pdo = connectDB();

print_r($_FILES);
$name = $_FILES['image']['name'];
$type = $_FILES['image']['type'];
$content = file_get_contents($_FILES['image']['tmp_name']);
$size = $_FILES['image']['size'];
echo "$name<br>";
echo "$type<br>";
//echo "$content<br>";
echo "$size<br>";
try {
    $sql = 'INSERT INTO images(image_name, image_type, image_content, image_size)
            VALUES (:image_name, :image_type, :image_content, :image_size)';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':image_name', $name, PDO::PARAM_STR);
    $stmt->bindValue(':image_type', $type, PDO::PARAM_STR);
    $stmt->bindValue(':image_content', $content, PDO::PARAM_LOB);
    $stmt->bindValue(':image_size', $size, PDO::PARAM_INT);
    $stmt->execute();
    echo "ok";
    print_r($stmt);
} catch (\Exception $e) {
    echo $e->getMessage();
    echo "no";
}
?>