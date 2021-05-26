$(function () {
    $('[name=team]').change(function () {
            var num = $('[name=team]').val();
            console.log(num);
            $.ajax({
                type: "post",
                url: "./ajax.php" //送信先は新しく作る。役割は写真参照	
                    ,
                data: {
                    team: num
                }
            }).done(function (res) {
                // ajaxがok
                $('.name option').remove();
                console.log(num);
                console.log(this);
                console.log(res);
                let msg = JSON.parse(res);
                console.log(msg);
                if(msg == null){
                    $('.name').append($("<option>").text("メンバー不在").attr('value', -1));
                    $('.name').css('color','#ff0000');
                }
                // $("#msg").text(JSON.parse(res));
                $.each(msg, function (id,msg) {
                    $('.name').append($('<option>').text(msg).attr('value', id));
                     $('.name').css('color', '#000000');
                    console.log(msg);
                });
            }).fail(function () {
                // 取得エラー
                alert('エラー');
                console.log(num);
                console.log(this);
                /*
				console.log("jqXHR          : " + jqXHR.status); // HTTPステータスが取得
        		console.log("textStatus     : " + textStatus);    // タイムアウト、パースエラー
        		console.log("errorThrown    : " + errorThrown.message); // 例外情報
				console.log("URL            : " + url);
				*/
            }).always(function () {

            })
    });
    return false;
});