$(function () {
    $('[name=team]').ready(function () {
            var num = $('[name=team]').val();
            console.log(num);
            //プライベートチャットを開いた時点での送る処理
            $.ajax({
                type: "post",//送る方法
                url: "./ajax.php", //正しく送れたら、ajax.phpへ
                data: {
                    team: num//送る内容
                }
            })
            //送った結果を受け取ってその結果を表示する（ajax.phpのechoの内容が(res)に送られる）
            .done(function (res) {
                // ajaxがok
                $('.name option').remove();//chat-input.phpのclass="name"の中のoptionタグを消している
                console.log(num);
                console.log(this);
                console.log(res);
                let msg = JSON.parse(res);//人が見てもわかる内容に戻す(msg)に入れる
                console.log(msg);
                //チームメンバー不在時の処理
                if (msg == null) {
                    $('.name').append($('<option>').text("メンバー不在").attr('value', -1));
                    $('.name').css('color', '#ff0000');
                }
                // $("#msg").text(JSON.parse(res));
                $.each(msg, function (msg,id) {
                    $('.name').append($('<option>').text(id).attr('value', msg));
                    console.log(msg);
                });
            })
            //結果が帰ってこなかった時と、送れなかった時の処理
            .fail(function () {
                // 取得エラー
                alert('エラー');
                console.log(num);
                console.log(this);
                console.log(res);
                /*
				console.log("jqXHR          : " + jqXHR.status); // HTTPステータスが取得
        		console.log("textStatus     : " + textStatus);    // タイムアウト、パースエラー
        		console.log("errorThrown    : " + errorThrown.message); // 例外情報
				console.log("URL            : " + url);
				*/
            })
            //毎回どんな処理になっても動く
            .always(function () {
            })
    });
    return false;
});