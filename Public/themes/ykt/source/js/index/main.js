define(function(require, exports, module) {
	var $ = require('jquery');
	var ykt = require('common');
	require('flexslider');
	$(function(){ 

		$('#J-slidedown').flexslider({
			animation: "slide",
			slideshowSpeed:5000
		});  

		$('#J-recommend a').on('click',function(){
			var _id = $(this).attr('data-id');

			$(this).siblings().removeClass('active').end().addClass('active') ;
		 	//loading
	        ykt.faLoading('J-fa-loading','#J-recommend-ul')
			$.ajax({
				url: '/Index/Index/recommend',
				type: 'POST',
				data: {cat_id: _id}
			}) 
			.done(function(data) { 
				setTimeout(function(){
					$('#J-recommend-ul').empty().append(data);
					$('#J-html-loading').remove();
				},500)
			})	
		})
		// $('#J-small-slidedown').flexslider({
		// 	animation: "slide",
		// 	controlNav:false,
		// 	minItems:5,
		// 	itemWidth: 140,
		// 	slideshowSpeed:5000
		// });	

		// $('#J-aside-slidedown').flexslider({
		// 	animation: "slide",
		// 	directionNav:false
		// });

		// //默认显示第一个
		// $('.J-sub-menu:first').show().parents('.J-menu-list').addClass('active');

		// $('.J-menu-list').mouseover(function(){
		// 	$('.J-sub-menu').hide();
		// 	$(this).siblings().removeClass('active');
		// 	$(this).addClass('active').children('.J-sub-menu').show();
		// })
	});
});