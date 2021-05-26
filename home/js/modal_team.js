$(function(){
	var winScrollTop;
	$('.widelink_team').each(function(){
		$(this).on('click',function(){
			var num = $(this).text();
			// var num = $(this).val();
			console.log(num);
			console.log(this);
				//モーダルを開く処理
				winScrollTop = $(window).scrollTop();
				var target = $(this).data('target');
				var modal = document.getElementById(target);
				$(modal).fadeIn();
				    return false;
		});
	});
	$('.js-modal-close').on('click',function(){
		$('.js-modal').fadeOut();
		$('body,html').stop().animate({scrollTop:winScrollTop}, 100);
	return false;
	}); 
});