define(function(require, exports, module) {
	var $ = require('jquery');
    //artDialog组件
    require('artDialog');	
    // 懒加载组件 
    var lazyload = require('lazyload');	  

    function appendLoading(obj){
    	$('body').append('<div id="'+obj+'" class="m-loading"><div class="inside"><img src="/Public/themes/ykt/images/loading_2.gif"/></div></div>')
    }
    exports.appendLoading = appendLoading;

    function faLoading(obj,container){
    	$(container).empty().append('<div id="'+obj+'" class="fa fa-spinner fa-spin m-fa-loading"></div>');
    }      
    exports.faLoading = faLoading;
    //登录提示
    function LoginDialog(_url,callback){
    	
	    var LoginDialog = dialog({
	        fixed: true, 
	        okValue: '发 送',
	        innerHTML:
				' <div class="loginTip-wrap">'
				+'	<div class="hd">用户登录</div>'
				+'		<div class="bd">'
				+'		    <form id="formlogin">'
				+'		        <ul>'
				+'	            <li>'
				+'	                <div class="label">手机/昵称：</div><span id="loginname_error" class="error"></span>'
				+'	                <input id="J-item" name="loginname" class="u-input-text radius" type="text" placeholder="例:2233@example.com"/>'
				+'	            </li>'
				+'	            <li class="mt-15">'
				+'	                <div class="label">密码：</div><span id="loginpwd_error" class="error"></span>'
				+'	                <input id="J-pwd" class="u-input-text radius" type="password" name="loginpwd"/>'
				+'	                <div class="forget">忘记密码?</div>'
				+'	            </li>  '
	 			+'	            <li class="r-form-list show-tips" id="J-show-tips">'
	            +'                  <p id="J-tips"></p>'
	            +'              </li>'				                         
				+'	        </ul>'
				+'	        <input id="J-loginsubmit" class="login-btn u-btn u-btn-primary" type="button" value="登录"/>'
				+'	        <div class="register">没有账号？<a href="#">马上注册</a></div>'
				+'	    </form>	'	 
				// +'		<div class="other-login-hd">第三方登录</div>'
				// +'		<div class="other-login-bd clearfix">'
				// +'			<a href="#"></a>' 
				// +'			<a class="qq" href="#"></a>'
				// +'			<a class="weixin" href="#"></a>'
				// +'		</div>'	    
				+'	</div>'
				+'	<div i="close" class="close"></div>'
				+'</div>'
	        ,
	        ok: function () {alert('成功')}
	    });
		LoginDialog.showModal();

		var _userItem = $('#J-item');
		var _userPwd = $('#J-pwd');
		var _tips = $('#J-tips');
		_url ? _url : _url = ''; 

		checkAccount(_userItem, _userPwd, _tips,_url,callback);

		focusBlur(".u-input-text,.u-textarea");
	}
	// LoginDialog.showModal();
	exports.LoginDialog = function(_url,callback){
		LoginDialog(_url,callback);
	};	

    // 检测登录
    function checkAccount(a,b,c,_url,callback){

    	$('#J-loginsubmit').on('click',function(){

		    var a_val = $.trim(a.val());
		    var b_val = $.trim(b.val());	
		    var _this = $(this);

	        if(a_val == '' || b_val == '' ){
	            c.show().text('账号或密码不能为空');
	        }else{
	        	_this.addClass('u-disabled').val('登录中');
	             $.ajax({ 
	                 url:'/Index/Login/doLogin',
	                 type:'POST',
	                 data:{'item':a_val,'pwd':b_val,jumpUrl:_url},
	                 dataType:'json',
	                 success:function(data){
						if(data.code){ 
							callback ? callback() : window.location= data.jumpUrl;
						}else{
							c.show().text(data.msg);
							_this.removeClass('u-disabled').val('登录');
						}
	                 }
	             });
	       }
	       $('.r-f-input').on('blur',function(){

			    var a_val = $.trim(a.val());
			    var b_val = $.trim(b.val());	

		        if(a_val == '' || b_val == '' ){
		            c.show().text('账号或密码不能为空');
		        }else{
		        	c.hide() 
		        }
	       })	       
    	})

    	returnLogin('.loginTip-wrap','#J-loginsubmit')
    }      
    exports.checkAccount = checkAccount;  

	/*
	 *图片上传
	 *btn         上传按钮
	 *txt         当前状态
	 *inputVal    容器
	 *type        titleImg:标题图，background：创作环境, tool:原料工具, step:步骤
	 *picWidth    截取宽度
	 *picHeight   截取高度
	 *callback    回调函数
	 */
	exports.AjaxUploadPic = function(btn,txt,inputVal,imgContainer,type,picWidth,picHeight,callback){
		var button = $(btn); 
		var confirmdiv = $(txt);
	    var fileType = "pic",fileNum = "one"; 
	    new AjaxUpload(button,{
	        action: yktGlobal.controller+'/upload/w/'+picWidth+'/h/'+picHeight,
	        name: yktGlobal.controller,
	        onSubmit : function(file, ext){ 
	            if(fileType == "pic"){
	                if (ext && /^(jpg|png|jpeg|gif|JPG)$/.test(ext)){
	                    this.setData({'info': '文件类型为图片'});
	                } else {
	                     confirmdiv.text('文件格式错误，请上传格式为.png .jpg .jpeg 的图片').show();
	                     return false;               
	                }
	            }           
	            if(fileNum == 'one') this.disable();
	        },
	        onComplete: function(file,res){
               if(res.indexOf('-')>0){
               		callback ? callback(res,btn,txt,inputVal,imgContainer,type,picWidth,picHeight) : '';     				
                    this.enable(); 
                    confirmdiv.hide();
               }else{
                     confirmdiv.text(res).show();
                     this.enable();
                     return false;
               }
            }
	    });
	}	

	//回车确定
	function returnLogin(obj,btn){
		$(obj).keypress(function(e) {
			if (e.which == 13) {
				$(btn).click();
			}
			})
		}

		exports.returnLogin = returnLogin

		/*表单得到失去焦点*/
		function focusBlur(obj) {
			$(obj).focus(function() {
				$(this).addClass("focus").removeClass("u-inputError");
			});
			$(obj).blur(function() {
			$(this).removeClass("focus");
		});
	}	
	exports.focusBlur = focusBlur
 
	function isEmail(email) { 
		return email.length > 6 && /^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/.test(email);
	}
	exports.isEmail = isEmail

	function isMobile(phone) { 
		return /^1[3,5,8]\d{9}$/.test(phone);
	}
	exports.isMobile = isMobile

	function isLogin(){
		var _data;
		$.ajax({
			url: '/Index/Login/isLogin',
			async:false, 
			dataType: 'json'
		})
		.done(function(data) {
			_data = data.status;
		})	
		return _data;
	}
	exports.isLogin = isLogin

	//高亮
	function highLight(){
		$('.J-active').on('click',function(){
			$(this).addClass('active').siblings().removeClass('active');
		}) 		
	}
	exports.highLight = highLight   


     //全选 
    function selectAll(obj,name,callback){
		$(obj).on('click',function(){
			$(name).each(function(i,e){
				$(this).prop("checked",$(obj).prop("checked"));
			});
			callback ? callback() : '';
		})

		$(name).click(function(){
			var _items=$(name);
			$(obj).prop('checked',_items.length==_items.filter(':checked').length);
			callback ? callback() : '';
		})
		
     }
	exports.selectAll = function(obj,name,callback){
		selectAll(obj,name,callback);
	};	

	/*右侧栏-滑动*/
	exports.toolbar = function(){
		// 右侧栏-高度 
		function currentHeight(obj){
			var $cHeight = $(document).height();
			$(obj).css('height',$cHeight);
		}
		currentHeight('#J-toolbar');		
		if(yktGlobal.uid){
			$('body').click(function(){
				$('#J-toolbar').animate({ 
					right:0
				},500)
			});	
			$('#J-toolbar').click(function(event){
				$('#J-toolbar').animate({ 
					right:230
				},500)
				if(event.stopPropagation){
					event.stopPropagation();
				}else if(window.event){
					window.event.cancelBubble = true;
				} 
			});
			$('#J-user').click(function(event){
				if(event.stopPropagation){
					event.stopPropagation();
				}else if(window.event){
					window.event.cancelBubble = true;
				} 
			});
			$('#J-toolbar .cart').click(function(event){
				if(event.stopPropagation){
					event.stopPropagation();
				}else if(window.event){
					window.event.cancelBubble = true;
				} 
			});
			/*右侧栏-标签*/
			$('.toolbar-list .list-hd').mouseover(function(){
				$(this).children('.J-list-show').stop(false,false);
				$(this).children('.J-list-show').show();
				$(this).children('.J-list-show').animate({
					right:'40px',
					opacity:'1'
					
				},300);
			});
			$('.toolbar-list .list-hd').mouseout(function(){
				$(this).children('.J-list-show').stop(false,false);
				$(this).children('.J-list-show').animate({
					right:'80px',
					opacity:'0'
				},300);
				$(this).children('.J-list-show').hide();
			});	

			// 右侧栏-动态信息
			$('.J-toolbar-list-hd').click(function(){
				$('.list-bd').hide();
				$('.J-toolbar-list-hd').removeClass('active');
				$(this).addClass('active');
				$(this).siblings('.J-toolbar-list-bd').show();
			});
			$('.J-toolbar-dynamic-title').mouseover(function(){
				$('.J-toolbar-dynamic-info').hide();
				$('.J-toolbar-dynamic-title').removeClass('active');
				$(this).addClass('active');
				$(this).children('.J-toolbar-dynamic-info').show();
			});

			// 全选
			// selectAll("#J-checkedAll","[name=cartCheckbox]:checkbox")
		} else{

			$('.toolbar-list').on('click',function(){
				LoginDialog()
			})
		}
	}

	//动态加载用户
	$('#J-header-user').load('/Index/Login/info.html');

	//懒加载
	// $("img.m-lazy").lazyload({ 
	//     effect : "fadeIn"
	// });
	$(window).on('scroll', function(){
		$('img.m-lazy').each(function () {
			var ost_top = $(this).offset().top,
			_top = 10,
			defHeight = $(window).height() + $(window).scrollTop() + _top,
			dataLazy = $(this).attr("data-original");

			if ( defHeight >= ost_top && dataLazy) {
				$(this).attr("src", dataLazy).removeAttr("data-original").hide().fadeIn(800).on('error',function () {
					$(this).attr('src','');
				});
			}   
		});
	}); 
	$('body,html').animate({ 'scrollTop': '1px' }, 100);
	
	//登录才能操作
	$('.J-logined').on('click',function(event){

		$.ajax({
			url: '/Index/Login/isLogin',
			async:false, 
			dataType: 'json'
		})
		.done(function(data) {
			if(!data.status){
				LoginDialog();					
				return false;
			}
		})
	})

	/*返回顶端*/
	var backToTopEle1 = $('#J-backtop');
	backToTopEle1.bind("click",function() {
		$("html,body").animate({scrollTop:"0"},200);
		return false;
	});
	
	var backToTopFun1 = function() {
		var st1 = $(document).scrollTop(), winh = $(window).height();
		(st1 > 500)? backToTopEle1.css('visibility','visible'):backToTopEle1.css('visibility','hidden');
		if (!window.XMLHttpRequest) {
			backToTopEle1.css("top", st1 + winh - 60);    
		}
	};
	$(window).bind("scroll", backToTopFun1);
	backToTopFun1();

    //页面滚动到什么高度
    exports.animateTo = function(p,time){
        $('body,html').animate({ 'scrollTop': p + 'px' }, time);
    }

	$.event.special.valuechange = {

	  teardown: function (namespaces) {
	    $(this).unbind('.valuechange');
	  },

	  handler: function (e) {
	    $.event.special.valuechange.triggerChanged($(this));
	  },

	  add: function (obj) {
	    $(this).on('keyup.valuechange cut.valuechange paste.valuechange input.valuechange', obj.selector, $.event.special.valuechange.handler)
	  },

	  triggerChanged: function (element) {
	    var current = element[0].contentEditable === 'true' ? element.html() : element.val()
	      , previous = typeof element.data('previous') === 'undefined' ? element[0].defaultValue : element.data('previous')
	    if (current !== previous) {
	      element.trigger('valuechange', [element.data('previous')])
	      element.data('previous', current)
	    }
	  }
	}

    $.yktTab =function(tabBar,tabCon,class_name,tabEvent,i){
        var $tab_menu=$(tabBar);
        // 初始化操作
        $tab_menu.removeClass(class_name);
        $(tabBar).eq(i).addClass(class_name);
        $(tabCon).hide();
        $(tabCon).eq(i).show();
        
        $tab_menu.on(tabEvent,function(){
            $tab_menu.removeClass(class_name);
            $(this).addClass(class_name);
            var index=$(this).index();
            $(tabCon).hide();
            $(tabCon).eq(index).show();
        });
    }

});