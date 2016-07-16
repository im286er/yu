define(function(require, exports, module) {
	var $ = require('jquery');
	var ykt = require('common');
	
	/*右侧栏-滑动*/
	// ykt.toolbar();
	// input框
	ykt.focusBlur(".u-input-text, .u-textarea");   
	
	$('.J-filter-more').on('click',function(){

		var _obj = $(this).parents('dl');
		var _liNum = $(this).parents('dl').find('dd').length;

        //当前值
        var _heightVal = _obj.height();		
        var _initHeight = 40;
		var _setHeight;
		

		// if(_liNum > 6 && _liNum <= 12){
		// 	_setHeight = _initHeight*2;
		// }else if(_liNum > 12 && _liNum <= 18){
		// 	_setHeight = _initHeight*3;
		// }else if(_liNum > 18 && _liNum <= 24){
		// 	_setHeight = _initHeight*4;
		// }else if(_liNum > 24 && _liNum <= 30){
		// 	_setHeight = _initHeight*5;
		// }else{
		// 	_setHeight = 'auto'
		// }

		if(_liNum > 7){
			_setHeight = parseInt(_liNum/7) * _initHeight;
		}else{
			_setHeight = 'auto'
		}


        if(_heightVal == _initHeight){
            _obj.height(_setHeight);
        }else{
            _obj.height(_initHeight);
        }

	});
});