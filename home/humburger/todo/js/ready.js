var test = (set_time) => {
  // 現在日時を数値に変換
  var nowData = new Date();
  var dnumNow = nowData.getTime();
  var num = dnumNow % 100000
  var dnumNow = dnumNow - num;
  console.log(dnumNow);

  // 取得日時を数値に変換
  var targetData = new Date(set_time);
  var dnumTarget = targetData.getTime();
  console.log(dnumTarget);

  if (dnumNow < dnumTarget) {
    // 差分表示
    var subTime = dnumTarget - dnumNow;
    console.log(subTime)
      // 差分 ÷ (1000ミリ秒 × 60秒 × 60分 × 24時間)
    var rslt = Math.ceil(subTime / (1000 * 60 * 60 * 24));
    var text = '&#12304;' + rslt + '&#12305;';
  } else if (dnumNow == dnumTarget) {
    var text = '&#12304;今日が期限です!&#12305;';
  } else {
    var text = '&#12304;過ぎています!&#12305;';
  }
  return text;

}

$(document).ready(function() {
  // タスク総数カウント
  var size = $('.taskItem').length;
  console.log(size);
  $('.totalCount').text(size);

  // 期限からの日数等表示
  $('[id=js-period]').each(function() {
    var period = $(this).html();
    console.log(period);
    // 期限の後ろにテキスト追加
    $(this).append(test(period));
  })



  // 優先度を取得

  $('[id=js-rank]').each(function() {
    var rank = $(this).html();
    console.log(rank);
    // ランクを元に色付け
    $(this).append('&#12304;rank&#12305;');
  })
})