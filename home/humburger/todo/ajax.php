<?php
require("select.php");

$post_json = $_POST["ajax"];
if($post_json == "js-sort-priority"){
  // 優先度順に並べる処理
  $sql = "SELECT * FROM todo ORDER BY rank DESC";
  $sort = select($sql);
}elseif($post_json == "js-sort-limit"){
  // 期限順に並び替える
  $sql = "SELECT * FROM todo ORDER BY period DESC";
  $sort = select($sql);
}else{
  exit;
}

echo($sort);

?>