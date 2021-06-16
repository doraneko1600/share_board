<?php
/**
 * dbからselectした内容の表示(関数化)
 */
if (!isset($_SESSION)) {
    session_start();
}

require_once('../../../config.php');
require_once('../../../function.php');
function select($sql)
{
  // dbから出力
  $pdo = new PDO(DSN, DB_USER, DB_PASS);
  $email = isset($_SESSION['EMAIL']) ? $_SESSION['EMAIL'] : "";
  // $sql = "select distinct team from team_members where email=\"" . $email . "\"";

  $select = $pdo->prepare($sql);
  $select = $pdo->query($sql);
  // print_r($select);
  foreach ($select as $row) {
    $select_email = $row["email"];
    $select_team = $row["team"]; // チーム
    $select_task = $row["task"]; // 内容
    $select_period = $row["period"]; // 期限
    $select_rank = $row["rank"]; //優先度
    $html = <<< HTML
			<ul class="mb-4 p-0">
				<li class="card mb-2 taskItem is-2">
					<div class="py-1 px-3">
						<p class="font-weight-bold my-0 taskContent">[{$select_team}] {$select_task}</p>
					</div>
					<div class="taskStatus px-3 small">
						<div class="row justify-content-between align-items-center py-1 px-3">
							<div class="">
								<dl class="d-inline-block mb-0 mr-3">
									<dt class="d-inline-block">優先度</dt>
									<dd class="d-inline-block" id="js-rank">{$select_rank}</dd>
								</dl>
								
								<dl class="d-inline-block mb-0">
									<dt class="d-inline-block">期限</dt>
									<dd class="d-inline-block" id="js-period">{$select_period}</dd>
								</dl>
								
							</div>
							<div class="">
								<ul class="list-inline">
									<li class="list-inline-item">
										<label class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input js-completeItem"  id="js-done">
											<span class="custom-control-indicator border border-secondary"></span>
											<span class="custom-control-description">完了</span>
										</label>
									</li>
									<li class="list-inline-item">
										<button class="btn btn-secondary btn-sm js-editItem" id="js-edit">編集</button>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</li>
			</ul>
	HTML;
    echo $html;
  }
}

function team()
{
  // dbに登録されてるチームを表示
  $pdo = new PDO(DSN, DB_USER, DB_PASS);
  $email = isset($_SESSION['EMAIL']) ? $_SESSION['EMAIL'] : "";
  // echo $email;
  $team_sql = "select distinct team from team_members where email=\"" . $email . "\"";
  $plan = $pdo->query($team_sql);
  $i = 0;
  $pull[0] = null;
  while ($row = $plan->fetch(PDO::FETCH_ASSOC)) {
    foreach ($row as $select) {
      $pull[$i] = h(trim(urldecode(mb_convert_encoding($select, 'utf-8', 'auto'))));
    }
    $i++;
  }
  foreach ($pull as $k => $v) {
    echo '<option value="' . $v . '">' . $v . '</option>' . "\n";
  }
}
