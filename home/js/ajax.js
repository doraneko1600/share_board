$(function(){
	var winScrollTop;
	$('.widelink').each(function(){
		$(this).on('click',function(){
			var num = $(this).text();
			console.log(num);
			console.log(this);
			$.ajax({
				  type: "post"
				, url: "./ajax.php"//送信先は新しく作る。役割は写真参照	
				, data: { id: num }
			}).done(function(res){
				// ajaxがok
				console.log(num);
				console.log(this);
				console.log(res);
				let msg = JSON.parse(res);
				// $("#msg").text(JSON.parse(res));
				$("#msg").html(msg);
			}).fail(function(){
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
			}).always(function(){
				
			})
		});
	});
	return false; 
});