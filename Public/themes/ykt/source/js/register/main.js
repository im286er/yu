define(function(require, exports, module) {
	var $ = require('jquery');
	var ykt = require('common');
	require('validate');

	// 表单得到失去焦点
	ykt.focusBlur(".u-input-text,.u-textarea");

	 // 动态获取宽度 
	function resizehandler(){	
		var sourceWidth = $(window).width(); 
		var photoWidth = sourceWidth - 490
		 // $('#J-photo-wrap').css('width',photoWidth+'px'); 
		$('#J-photo-wrap').animate({ 
			width:photoWidth+'px'
		},100)	
	}
		
	function throttle(method,context){
		clearTimeout(method.tId);
		method.tId=setTimeout(function(){ 
			method.call(context);
		},100);
	} 
	
	var registSendOps = {
		url:'/Index/Regist/sendSMSCode'
	}
	var forgetSendOps = {
		url:'/Index/Login/sendSMSCode'
	}
	// window.onresize=function(){
	// 	throttle(resizehandler,window);
	// }; 

	//回车登录
	ykt.returnLogin('#formlogin','#J-loginsubmit');

	function sendCode(ops){
		$('#J-code-btn').on('click',function(){
			var _mobile = $('input[name="mobile"]').val();
			var that = $(this);

			if(!ykt.isMobile(_mobile)){
	            dialog({
	                title:'消息',
	                content:'手机号码错误',
	                okValue:'确定',   
	                fixed:true,
	                ok:function(){}                 
	            }).width(200).showModal();              
				return false;
			}
			$.ajax({
				url: ops.url,
				type: 'POST',
				dataType: 'json',
				data: {mobile: _mobile},
			})
			.done(function() {
	            countDown(function() {
	                that.text('发送激活码').removeClass('disabled').prop('id','J-code-btn');
	            })

	            function countDown(callback) {
	            	$('#J-code-btn').off('click');
	                var count = 120;

	                (function fcount() {
	                    if(count < 0) return callback();
	                    that.text('重新获取('+ count-- + ')');
	                    that.addClass('disabled').prop('id','');;
	                    setTimeout(fcount, 1000);
	                })();
	            }
			})
		})
	}

	if(yktGlobal.pageName == 'register'){
		sendCode(registSendOps)
		/* validate验证 */
		$("#J-registerForm").validate({
			rules: {
				mobile: {
					required: true,
					isMobile: true,
					remote:{
						url: "checkUserItemUnique",   
						type: "post",            
						dataType: "json",        
						data: {               
							mobile: function() {
								return $("#mobile").val();
							}
						}
					} 								
				},
				sms_code:{
					required:true,
                    remote:{
						url: "checkSMSCode",   
						type: "post",            
						dataType: "json",        
						data: {               
							sms_code: function() {
								return $("#sms_code").val();
							}
						}
					} 
				},
				username:{
					required: true,
					stringCheck:true,  
					minlength: 3,
					maxlength: 15,
					remote:{
						url: "checkUserItemUnique",   
						type: "post",            
						dataType: "json",   
						data: {               
							username: function() {
								return $("#username").val();
							}
						}
					}  								
				},
				password: {
					required: true,
					minlength: 5,
					maxlength: 20
				},
				confirm_password: {
					required: true,
					equalTo: "#password"
				},
				agree:{
					required: true
				}
			},
			messages:{
				mobile:{
					remote:"手机已经被注册!"
				},			
				username:{
					remote:"用户名已被注册!"
				},
			},						
			errorPlacement: function(error, element) {
				error.appendTo( element.siblings('.error'));
			}							
		});				

		//表单提交
		$.validator.setDefaults({ 									
			submitHandler:function(form){ 
				form.submit();
			} 
		});	
	} else if(yktGlobal.pageName == 'login'){
		var _userItem = $('#J-item');
		var _userPwd = $('#J-pwd');
		var _tips = $('#J-tips');
		
		ykt.checkAccount(_userItem, _userPwd, _tips);
	}else if(yktGlobal.pageName == 'retrieve'){
		/* validate验证 */
		$("#J-retrieve-form").validate({
			rules: {
				mobile: { 
					required: true,
					isMobile: true,								
				},
				sms_code:{
					required:true,				
				}, 
				password: {
					required: true,
					minlength: 5,
					maxlength: 20
				},
				confirm_password: {
					required: true,
					equalTo: "#password"
				},
			},
			errorPlacement: function(error, element) {
				error.appendTo( element.siblings('.error'));
			}							
		});		
		//表单提交
		$.validator.setDefaults({ 									
			submitHandler:function(form){
				form.submit();
			} 
		});	
		sendCode(forgetSendOps)
	}
});