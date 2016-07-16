define(function(require, exports, module) {
	var $ = require('jquery');
	var common = require('../../common/common');
	
 
	/*右侧栏-滑动*/
	common.toolbar();
	// input框
	common.focusBlur(".u-input-text, .u-textarea");   

	// 搜索
	$('#J-search-form').attr('action','/Index/Search/'+searchType+'.html')
});