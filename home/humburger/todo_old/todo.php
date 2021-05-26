<?php require 'header.php'; ?>

<?php

/*データベース取得*/
require_once '../../../config.php';
/*      ↑1回だけデータ取得*/
require_once '../../../function.php';
require_once('../humburger.php');

if (!isset($_SESSION)) {
    session_start();
}
$pdo = new PDO(DSN, DB_USER, DB_PASS);
$email = isset($_SESSION['EMAIL']) ? $_SESSION['EMAIL'] : "";
$sql = "select distinct team from team_members where email=\"" . $email . "\"";

$plan = $pdo->query($sql);

// 初期値
$i = $select_team = $complete = "0";
$rank = $period = "not";
$_SESSION['session_team'] = $select_team;

// ソートからのpost内容取得
if (isset($_REQUEST['sort'])) {
    $select_team = $_REQUEST['select_team'];
    $rank = $_REQUEST['rank'];
    $period = $_REQUEST['period'];
    $_SESSION['session_team'] = $select_team;
}

// ソート用if文
if ($select_team !== "0") {
    // チームが選ばれた状態
    if ($period === "not" && $rank !== "not") {
        // 期限が選択されてない
        if ($rank === "ASC") {
            // 優先度の昇順
            $sort_sql = "select * from todo where team=\"" . $select_team . "\" ORDER BY rank ASC, created DESC";
        } else {
            // 優先度の降順
            $sort_sql = "select * from todo where team=\"" . $select_team . "\" ORDER BY rank DESC, created DESC";
        }
    } else if ($rank === "not" && $period !== "not") {
        // 優先度が選択されてない
        if ($period === "DESC") {
            $sort_sql = "select * from todo where team=\"" . $select_team . "\" ORDER BY period DESC, created DESC";
        } else {
            $sort_sql = "select * from todo where team=\"" . $select_team . "\" ORDER BY period ASC, created DESC";
        }
    } else if ($period === "not" && $rank === "not") {
        // 両方とも選択されない
        $sort_sql = "select * from todo where team=\"" . $select_team . "\" ORDER BY created DESC";
    } else {
        if ($period === "DESC") {
            // 優先度の選択
            if ($rank === "ASC") {
                // 優先度の昇順
                $sort_sql = "select * from todo where team=\"" . $select_team . "\" ORDER BY period DESC, rank ASC, created DESC";
            } else {
                // 優先度の降順
                $sort_sql = "select * from todo where team=\"" . $select_team . "\" ORDER BY period DESC, rank DESC, created DESC";
            }
        } else {
            // 期限の選択
            if ($rank === "ASC") {
                // 期限の昇順
                $sort_sql = "select * from todo where team=\"" . $select_team . "\" ORDER BY period ASC, rank ASC, created DESC";
            } else {
                // 期限の降順
                $sort_sql = "select * from todo where team=\"" . $select_team . "\" ORDER BY period ASC, rank DESC, created DESC";
            }
        }
    }
} else {
    // チームが選ばれてない状態
    if ($period === "not" && $rank !== "not") {
        // 期限が選択されてない
        if ($rank === "ASC") {
            // 優先度の昇順
            $sort_sql = "select * from todo where email=\"" . $email . "\" ORDER BY rank ASC, created DESC";
        } else {
            // 優先度の降順
            $sort_sql = "select * from todo where email=\"" . $email . "\" ORDER BY rank DESC, created DESC";
        }
    } else if ($rank === "not" && $period !== "not") {
        // 優先度が選択されてない
        if ($period === "DESC") {
            $sort_sql = "select * from todo where email=\"" . $email . "\" ORDER BY period DESC, created DESC";
        } else {
            $sort_sql = "select * from todo where email=\"" . $email . "\" ORDER BY period ASC, created DESC";
        }
    } else if ($period === "not" && $rank === "not") {
        // 両方とも選択されない
        $sort_sql = "select * from todo where email=\"" . $email . "\" ORDER BY created DESC";
    } else {
        if ($period === "DESC") {
            // 優先度の選択
            if ($rank === "ASC") {
                // 優先度の昇順
                $sort_sql = "select * from todo where email=\"" . $email . "\" ORDER BY period DESC, rank ASC, created DESC";
            } else {
                // 優先度の降順
                $sort_sql = "select * from todo where email=\"" . $email . "\" ORDER BY period DESC, rank DESC, created DESC";
            }
        } else {
            // 期限の選択
            if ($rank === "ASC") {
                // 期限の昇順
                $sort_sql = "select * from todo where email=\"" . $email . "\" ORDER BY period ASC, rank ASC, created DESC";
            } else {
                // 期限の降順
                $sort_sql = "select * from todo where email=\"" . $email . "\" ORDER BY period ASC, rank DESC, created DESC";
            }
        }
    }
}

// リストを更新した時に表示チームを維持する
if ($_SESSION['session_team'] === "0") {
    $sort_sql = "select * from todo where email=\"" . $email . "\" ORDER BY created DESC";
} else {
    $session_team = $_SESSION['session_team'];
    $sort_sql = "select * from todo where team=\"" . $session_team . "\" ORDER BY created DESC";
    $select_team = $session_team;
}

while ($row = $plan->fetch(PDO::FETCH_ASSOC)) {
    foreach ($row as $select) {
        $pull[$i] = h(trim(urldecode(mb_convert_encoding($select, 'utf-8', 'auto'))));
    }
    $i++;
}

if (isset($_REQUEST['complete'])) {
    $complete = "1";
} else {
    $complete = "0";
}

if (isset($_REQUEST['command'])) {
    switch ($_REQUEST['command']) {
        case 'insert': //追加
            if (
                empty($_REQUEST['team']) ||
                empty($_REQUEST['task']) ||
                empty($_REQUEST['details']) ||
                empty($_REQUEST['period']) ||
                empty($_REQUEST['rank'])
            )
                break;
            $sql = $pdo->prepare('insert into todo set email=?, team=?, task=?, details=?, period=?, rank=?');
            $sql->execute(
                [$email, $_REQUEST['team'], $_REQUEST['task'], $_REQUEST['details'], $_REQUEST['period'], $_REQUEST['rank']]
            );
            break;
        case 'update': //更新
            if (
                empty($_REQUEST['team']) ||
                empty($_REQUEST['task']) ||
                empty($_REQUEST['details']) ||
                empty($_REQUEST['period']) ||
                empty($_REQUEST['rank'])
            )
                break;
            $sql = $pdo->prepare('update todo set team=?, task=?, details=?, period=?, rank=?, complete=? where id=?');
            $sql->execute(
                [$_REQUEST['team'], $_REQUEST['task'], $_REQUEST['details'], $_REQUEST['period'], $_REQUEST['rank'], $complete, $_REQUEST['id']]
            );

            break;
        case 'delete': //削除
            $sql = $pdo->prepare('delete from todo where id=?');
            $sql->execute([$_REQUEST['id']]);
            break;
    }
}

//トークンの生成
$_SESSION["chkno"] = $chkno = get_csrf_token();

echo '<div class="margin">';

foreach ($pdo->query($sort_sql) as $row) {

    echo <<<HTML
    
    <form class="block" action="#" method="post">
        <input type="hidden" name="command" value="update">
        <input type="hidden" name="id" value="{$row['id']}">
    
    <div id="scroll">
      <div class="box">
HTML;
    /*チームの選択*/
    echo '<p class="team"><select name="team">';
    echo '<option value="' . $row['team'] . '">' . $row['team'] . '</option>';
    foreach ($pull as $k => $v) {
        echo '<option value="' . $v . '">' . $v . '</option>';
    }
    echo '</select></p>';

    echo <<<HTML
  <!-- タスク出力 -->
        <p><input type="text" name="task" height="40px" width="100px" value="{$row['task']}"></p>
        
  <!-- 詳細出力 -->
        <p><textarea name = "details" rows = "5" style = "font-family:sans-serif">{$row['details']}</textarea></p>

  <!-- 期限出力 -->
        <p><input type="date" name="period" style = "font-family:sans-serif" value="{$row['period']}"></p>
HTML;
    /*優先度出力*/
    echo '<div class=rank>';
    if ($row['rank'] == "3") {
        echo '<label><input type="radio" name="rank" value="3" checked="checked">高</label>';
        echo '<label><input type="radio" name="rank" value="2">中</label>';
        echo '<label><input type="radio" name="rank" value="1">低</label>';
        echo '<span class = "high">';
        echo '&#9785;';
        echo '</span>';
    } else if ($row['rank'] == "2") {
        echo '<label><input type="radio" name="rank" value="3">高</label>';
        echo '<label><input type="radio" name="rank" value="2" checked="checked">中</label>';
        echo '<label><input type="radio" name="rank" value="1">低</label>';
        echo '<span class = "mid">';
        echo '&#9786;';
        echo '</span>';
    } else {
        echo '<label><input type="radio" name="rank" value="3">高</label>';
        echo '<label><input type="radio" name="rank" value="2">中</label>';
        echo '<label><input type="radio" name="rank" value="1" checked="checked">低</label>';
        echo '<span class = "low">';
        echo '&#9787;';
        echo '</span>';
    }

    echo '</div>';

    echo '<div class = "complete">';
    echo '<label>';
    if ($row['complete'] == "0") {
        echo '<input type="checkbox" class="check" name="complete">完了';
    } else {
        echo '<input type="checkbox" class="check" name="complete" checked="checked">完了';
    }
    echo '</label>';
    echo '</div>';

    echo <<<HTML
        <div class="change">
            <input type="submit" value="更新">

    </form>
        
        <form action="#" method="post">
          <input type="hidden" name="command" value="delete">
          <input type="hidden" name="id" value="{$row['id']}">
          <input class="sample" type="submit" value="削除">
        </div>
        </form>
      </div>
    </div>  
        <hr>
HTML;
}
echo '</div>';
?>


<div class="show">
    <h4>ソート中の内容</h4>
    <p>チーム：
        <?php
        if ($select_team == "0") {
            $select_team = "あなたの予定";
            echo $select_team;
        } else {
            echo $select_team;
        }
        ?>
    </p>
    <p>期限：
        <?php
        if ($period = "ASC") {
            $period = '近い';
            echo $period;
        } else if ($period = "DESC") {
            $period = '遠い';
            echo $period;
        } else {
            $period = '変更しない';
            echo $period;
        }
        ?>
    </p>
    <p>優先度：
        <?php
        if ($period = "ASC") {
            $period = '低い';
            echo $period;
        } else if ($period = "DESC") {
            $period = '高い';
            echo $period;
        } else {
            $period = '変更しない';
            echo $period;
        }
        ?>
    </p>

</div>
<div class="todo-title">
    <p>チーム名</p>
    <p>タスク名</p>
    <p>詳細</p>
    <p>期限</p>
    <p>優先度</p>
</div>

<!-- 入力欄 -->
<form class="footer" action="#" method="post">
    <input type="hidden" name="command" value="insert">
    <div class="input">
        <p>チーム名
            <br><select name="team">
                <?php
                foreach ($pull as $k => $v) {
                    echo '<option value="' . $v . '">' . $v . '</option>' . "\n";
                }
                ?>
            </select>
        </p>

        <p>内容
            <br><input type="text" name="task" width="100px">
        </p>

        <p>詳細
            <br><textarea name="details" rows="3" width="100px" style="font-family:sans-serif" placeholder="ここに入力してください"></textarea>
        </p>

        <p>期限
            <br><input type="date" name="period" width="100px" style="font-family:sans-serif">
        </p>

        <p>優先度
            <br><input type="radio" name="rank" value="3" checked="checked">高
            <input type="radio" name="rank" value="2">中
            <input type="radio" name="rank" value="1">低
        </p>
        <input name="chkno" type="hidden" value="<?php echo $chkno; ?>">
        <p><input type="submit" value="追加"></p>

        <a href="../../index.php" class="home" style="text-decoration:none;">ホームに戻る</a>
    </div>
</form>

<!-- 表示方法選択部分 -->
<form class="sort" method="post">
    <input type="hidden" name="sort" value="sort">
    <select name="select_team">
        <option value="0">ToDo</option>
        <!-- kに添え字、vにチームが入っている -->
        <?php
        foreach ($pull as $k => $v) {
            echo '<option value="' . $v . '">' . $v . '</option>' . "\n";
        }
        ?>
    </select>
    <br>
    <p class="line">期限順に並べる
        <br><br>
        <input type="radio" name="period" value="DESC" checked="checed">遠い
        <input type="radio" name="period" value="ASC">近い
        <br>
        <input type="radio" name="period" value="not">変更しない
    </p>
    <br>
    <p class="line">優先度順に並べる
        <br><br>
        <input type="radio" name="rank" value="ASC" checked="checed">低い
        <input type="radio" name="rank" value="DESK">高い
        <br>
        <input type="radio" name="rank" value="not">変更しない
    </p>
    <br><input type="submit" name="select" value="選択">
    <p class="statement"><br><br>※ToDoを選択すると<br>あなた自身が登録した<br>予定を見ることが<br>できます。</p>
</form>

<?php require 'footer.php'; ?>
