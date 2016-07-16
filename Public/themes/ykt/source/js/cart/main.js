define(function(require, exports, module) {

	var $ = require('jquery');
	var ykt = require('common');
	var globalChangeVal; 
	//全选数组
	var globalGpidArr = [];
	//结算开关
	var globalSureFlag = true;
	//artDialog组件
    require('artDialog');
	$(function(){

		//全选回调
		function checkAllCallback(){
			var _num = 0;
			$("input[name='gpid[]']").each(function(i,e){
				var _checked = $(this).prop("checked");
				if(_checked){
					var _gpid = $(this).closest('.J-order-list').attr('data-gpid');
					globalGpidArr.push(_gpid);
					_num++;
				}
			});
			if(globalGpidArr.length){
				$.ajax({
					url: yktGlobal.controller+'/cal',
					type: 'post',
					dataType: 'json',
					data: {gpid:globalGpidArr},
				})
				.done(function(data) {
					$('#J-total-amount').text(_num);
					$('#J-total-price').text('￥'+data.total_price);
					globalSureFlag = true;
				})
			}else{
				$('#J-total-amount').text(0);
				$('#J-total-price').text('￥0');
				globalSureFlag = false;
			}
			// 重设
			globalGpidArr = [];
		}
		//改变数量回调
		function changeCallback(){
			$('.J-amount-input').val(globalChangeVal); 
		}

        //结算检测登录回调
        function isLoginCallback(){
            $('#J-sure-btn').closest('form').attr('action','/Index/Order/genernate.html').submit();
        }
		function isLoginMergeCallback(){   
			$('.ui-popup-backdrop').remove();
			$('.ui-popup-show').remove();
			var _gpidArr = [];

			ykt.appendLoading('J-html-loading');

			$("input[name='gpid[]']").each(function(i,e){
				var _checked = $(this).prop("checked");
				var _gpid;
				if(_checked){
					_gpid = $(this).closest('.J-order-list').attr('data-gpid');
					_gpidArr.push(_gpid);
				}

			});

			$.ajax({
				url: '/Index/Order/merge',
				type: 'POST', 
				dataType: 'html',
				data: {gpid: _gpidArr},
			})
			.done(function(data) {
				setTimeout(function(){
		            dialog({
		                title: '合并订单',
		                content: data,          
		                okValue: '确定',
						cancelValue:'取消',
		                fixed: true, 
		                ok: function () {
		                	var oForm = $('#J-form-merge');
		                	oForm.html ? oForm.submit() : '' ;
		                }, 
		                cancel:function(){

		                }
		            }).width(800).showModal();	
		            ykt.highLight();
		            $('#J-html-loading').remove();
	        	},500)
			})
		}

        //判断库存
        function estimateStock(val,stock,callback){
        	let stockFlag;
        	let stockTips;
			//判断库存
			if(val > stock){
				stockTips='库存不足';
				stockFlag=true;
			}else if(val < 1){
				stockTips='商品不能少于1';
				stockFlag=true;
			}else if(isNaN(val)){
				stockTips='请输入正确的数字';
				stockFlag=true;				
			}else{
                stockFlag=false;		
			}
			if(stockFlag){
                dialog({
                    title: '提示',
                    content: stockTips,          
                    okValue: '确定',
                    fixed: true, 
                    ok: function () {} 
                }).width(200).showModal();	
                callback ? callback() : '';
                return false;	
			}
			return true;
		}

	    // 改变input商品数量
	    function numInputChange(){

	    	var _thisVal; //保存当前值

		    $('.J-amount-input').on('focus',function(){
		    	_thisVal = $(this).val();
		    	globalChangeVal = _thisVal;

		    })

			$('.J-amount-input').on('change',function(){

				var _this = $(this); 
				var _changeVal = parseInt($(this).val());	
				var _parentsList = $(this).closest('.J-order-list');
				var _gpid = _parentsList.attr('data-gpid');
				// var _attr = $(this).siblings('.J-order-attr').val();
				var _tSum = _parentsList.find('.J-item-price .num');   //小计			
				var _stock = parseInt(_parentsList.attr('data-stock'));

				if(!estimateStock(_changeVal,_stock,changeCallback)){
					return false;
				}

				$.ajax({ 
					url: yktGlobal.controller+'/adjust',
					type: 'POST',
					data: {gpid:_gpid,num:_changeVal}
				})	        	
				.done(function(data) {
					if(data.code){
						_tSum.text(data.item_price); 
						$('#J-total-price').text('￥'+data.total_price);
					}else{
		                dialog({ 
		                    title: '提示',
		                    content: data.msg,          
		                    okValue: '确定',
		                    fixed: true, 
		                    ok: function () {
		                       
		                    } 
		                }).width(200).showModal();	
		                _this.val(_thisVal); 
					}
				})
	
			})    
		}

	    // 改变商品数量
	    function numChange(obj,type){
			$(obj).on('click',function(){
				
				var _obj = $(this).siblings('.J-amount-input');
				var _parentsList = $(this).closest('.J-order-list');
				var _numVal = parseInt(_obj.val());			
				var _gpid = _parentsList.attr('data-gpid');
				var _stock = parseInt(_parentsList.attr('data-stock'));
				// var _attr = $(this).siblings('.J-order-attr').val();
				var _tSum = _parentsList.find('.J-item-price .num');   //小计
				var stockTips = ''
				var stockFlag = false;

				type == 'increase' ? _numVal++ : _numVal--;
				
				if(!estimateStock(_numVal,_stock)){
					return false;
				}

				$.ajax({
					url: yktGlobal.controller+'/'+type,
					type: 'POST',
					data: {gpid:_gpid}
					 
				}) 
				.done(function(data) {
					if(data.code){
						_obj.val(_numVal);
						_tSum.text(data.item_price);
						$('#J-total-price').text('￥'+data.total_price); 

					}else{
		                dialog({
		                    title: '提示',
		                    content: data.msg || '数量错误',          
		                    okValue: '确定',
		                    fixed: true, 
		                    ok: function () {
		                       
		                    } 
		                }).width(200).showModal();					
					}
				})		
			})
		}

		numInputChange();
		numChange('.J-amount-increase','increase');
		numChange('.J-amount-decrease','reduce');

		//检测登录状态
		$('#J-sure-btn').on('click',function(){ 
			if(globalSureFlag){
				var _this = $(this);
		        $.ajax({
		            url: '/Index/Login/isLogin',
		            async:false, 
		            dataType: 'json'
		        })
		        .done(function(data) {
		            if(!data.status){ 
		                ykt.LoginDialog('/Index/Order/genernate.html',isLoginCallback);                     
		                event.preventDefault();
		            }else{
		            	_this.closest('form').submit();     
		            }
		        })	
	        }else{
                dialog({
                    title: '提示',
                    content: '请勾选你需要结算的宝贝',          
                    okValue: '确定',
                    fixed: true, 
                    ok: function () {
                       
                    } 
                }).width(200).showModal();	
	        }	
		})

		//删除商品
		$('.J-delete').on('click',function(){
			var _this = $(this); 
			var _gpid = $(this).parents('.J-order-list').attr('data-gpid');
			// var _attr = $(this).closest('.J-order-list').find('.J-order-attr').val();

			$.ajax({
				url: yktGlobal.controller+'/remove',
				type: 'POST',
				data: {gpid:_gpid}
			})
			.done(function(data) { 
				if(data.code){
					if(data.cart_num){
						_this.closest('.J-order-list').remove();
						$('#J-total-price').text('￥'+data.total_price);
						$('#J-total-amount').text(data.cart_num);
					}else{
						window.location.reload();
					}	
				}else{
	                dialog({
	                    title: '提示',
	                    content: data.msg,          
	                    okValue: '确定',
	                    fixed: true, 
	                    ok: function () {
	                       
	                    } 
	                }).width(200).showModal();	
				}
			})			
		})

		// 清空购物车
		$('#J-clearAll').on('click',function(){
			$.ajax({
				url: yktGlobal.controller+'/clear'
			})		 
			.done(function(data) {
				window.location.reload();
			})		
		})

		//合拼订单
		$('#J-merge-btn').on('click',function(){
			if(globalSureFlag){
				let _this = $(this); 
		        $.ajax({
		            url: '/Index/Login/isLogin',
		            async:false, 
		            dataType: 'json'
		        })
		        .done(function(data) {
		            if(!data.status){ 
		                ykt.LoginDialog('/Index/Order/genernate.html',isLoginMergeCallback);                     
		                event.preventDefault();
		            }else{
		            	isLoginMergeCallback(); 
		            }
		        })
	        }else{
                dialog({
                    title: '提示',
                    content: '请勾选你需要结算的宝贝',          
                    okValue: '确定',
                    fixed: true, 
                    ok: function () {
                       
                    } 
                }).width(200).showModal();	
	        }	
		})
		
		//全选
		ykt.selectAll("#J-checkbox-all","input[name='gpid[]']",checkAllCallback)
	})
});