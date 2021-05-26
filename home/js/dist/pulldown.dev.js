"use strict";

$(function () {
  $('#makerPulldown').change(function () {
    var num = $('#makerPulldown').val();
    console.log(num);
    console.log(this); // マスタデータの取得

    $.ajax({
      type: "post",
      url: "./ajax_team.php",
      data: {
        id: $("#makerPulldown").val()
      }
    }).done(function (res) {
      // 選択されたオプションのkey番号を取得
      console.log(num);
      $("#msg_team").text(JSON.parse(res));
      /*
      // 選択値以外に class="hide" を追加
      $(".targetTd p[id != 'note+num']").addClass('hide');
         // 取得したkey番号の class="hide" を削除
      $("p#note"+num).removeClass('hide');
      */
    }).fail(function () {
      console.log(num); // 取得エラー

      console.log("jqXHR          : " + jqXHR.status); // HTTPステータスが取得

      console.log("textStatus     : " + textStatus); // タイムアウト、パースエラー

      console.log("errorThrown    : " + errorThrown.message); // 例外情報

      console.log("URL            : " + url);
      alert('取得エラー');
    }).always(function () {// 後処理(処理することが在れば)
      //console.log("ajax通信に失敗しました");
    });
  });
});