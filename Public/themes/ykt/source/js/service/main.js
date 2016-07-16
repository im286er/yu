define(function(require, exports, module) {
	var $ = require('jquery');
	var ykt = require('common');
	require('flexslider');
 
	/*右侧栏-滑动*/
	// common.toolbar();
	// input框
	ykt.focusBlur(".u-input-text, .u-textarea");   

	$('#J-slidedown').flexslider({
		animation: "slide"
	});
	// $('#J-aside-slidedown').flexslider({
	// 	animation: "slide",
	// 	itemWidth: 265,
	// 	directionNav:false
	// });		
	
});