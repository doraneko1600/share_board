<?php
session_start();
require_once('../../../function.php');
require_once('header.php');
require_once('functions.php');
require_once('../humburger.php');

$pdo = connectDB();

$email = isset($_SESSION['EMAIL']) ? $_SESSION['EMAIL'] : "";

if (isset($_REQUEST['team_select'])) {
    $_SESSION['team_select'] = $_REQUEST['team_select'];
}
$team = $_SESSION['team_select'];

session_write_close(); // セッションクローズ

if (!isset($_REQUEST['upload'])) {
    // ファイルを取得
    $sql = "SELECT * FROM images WHERE team= \"" . $team . "\" ORDER BY created DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $images = $stmt->fetchAll();
} else {
    // 画像を保存
    if (!empty($_FILES['image']['name'])) {
        $name = $_FILES['image']['name'];
        $type = $_FILES['image']['type'];
        $content = file_get_contents($_FILES['image']['tmp_name']);//postされたものを保存用に変換する
        $size = $_FILES['image']['size'];
        try {
            $sql = 'INSERT INTO images(team, email, image_name, image_type, image_content, image_size)
                    VALUES (:team, :email, :image_name, :image_type, :image_content, :image_size)';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':team', $team, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':image_name', $name, PDO::PARAM_STR);
            $stmt->bindValue(':image_type', $type, PDO::PARAM_STR);
            $stmt->bindValue(':image_content', $content, PDO::PARAM_LOB);
            $stmt->bindValue(':image_size', $size, PDO::PARAM_INT);
            $stmt->execute();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    unset($pdo);
    header('Location:upload.php');
    exit();
}

$sql = "select distinct team from team_members where email=\"" . $email . "\"";
$upload = $pdo->query($sql);

unset($pdo);

$i = 0;
while ($row = $upload->fetch(PDO::FETCH_ASSOC)) {
    foreach ($row as $select) {
        $pull[$i] = h(trim(urldecode(mb_convert_encoding($select, 'utf-8', 'auto'))));
    }
    $i++;
}

?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 border-right">
            <h3><?php echo $team ?>の共有ファイル</h3>
            <ul class="list-unstyled">
                <?php for ($i = 0; $i < count($images); $i++) : ?>
                    <li class="media mt-5">
                        <!-- PDFの表示用 -->
                        ・<?php if (strrchr($images[$i]['image_name'], '.') === ".pdf") {  ?>
                            <a href="pdf.php?id=<?= $images[$i]['id']; ?>" target="new">
                                PDFを表示する
                            </a>
                            <!-- ZIPのダウンロード用 -->
                        ・<?php } else if (strrchr($images[$i]['image_name'], '.') === ".zip") { ?>
                            <a href="zip.php?id=<?= $images[$i]['id']; ?>&name=<?= $images[$i]['image_name']; ?>" target="new">
                                ZIPファイルをダウンロードする
                            </a>
                        <?php } else { ?>
                            <!-- 画像の表示用 -->
                            <img src="image.php?id=<?= $images[$i]['id']; ?>" width="100px" height="auto" class="mr-3">
                        <?php } ?>
                        <!-- 削除 -->
                        <div class="media-body">
                            <h5><?= $images[$i]['image_name']; ?> (<?= number_format($images[$i]['image_size'] / 1000, 2); ?> KB)</h5>
                            <a href="javascript:void(0);" onclick="var ok = confirm('削除しますか？'); if (ok) location.href='delete.php?id=<?= $images[$i]['id']; ?>'">
                                <i class="far fa-trash-alt"></i> 削除
                            </a>
                        </div>
                    </li>
                <?php endfor; ?>
            </ul>
        </div>
        <div class="col-md-4 pt-4 pl-4">
            <!-- post先をtest.phpに変更することでpostされる内容が確認できる -->
            <form method="post" action="#" enctype="multipart/form-data">
                <input type="hidden" name="upload">
                <div class="form-group">
                    <p>保存できるものは「画像」「pdf」「zip」の三種類です。</p>
                    <label>・アップロードするファイルを選択<br></label>
                    <input type="file" name="image" required>
                </div>
                <p><label>※アップロード出来るファイルサイズは<span>1GB</span>以内です。</label></p>
                <button type="submit" class="btn btn-primary">保存</button>
            </form>
            <br>
            
            <br>
            <!-- 表示するチームの変更 -->
            <p>・表示するチームの選択</p>
            <form class="team_select" action="upload.php" method="post">
                <select name="team_select">
                    <?php
                    foreach ($pull as $v) {
                        echo '<option value="' . $v . '">' . $v . '</option>' . "\n";
                    }
                    ?>
                </select>
                <button type="submit">表示する</button>
            </form>
        </div>
    </div>
</div>
<p>
    <a  class="home" href="../../index.php" style="text-decoration:none;">ホームに戻る</a>
</p>
<?php require_once('footer.php'); ?>