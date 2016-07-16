define(function(require, exports, module) {
	
    var $ = require('jquery');
    var ykt = require('common'); 
	$(function(){
		//axajuplaod组件
		require('ajaxupload');   
		//artDialog组件 
		require('validate');   

		/********投稿***********/	
		if(yktGlobal.pageName == 'contribute' || yktGlobal.pageName == 'anli'){

			//上传图片回调 
			function uploadCallback(res,btn,txt,inputVal,imgContainer,type,picWidth,picHeight){
				$(inputVal).val(res);
				//标题图、创作环境
				if(type == 'titleImg' || type == 'background'){
					$(imgContainer).remove();
					$('.upload-cover-pic').append('<div class="ui-dialog-loading"></div>');
					setTimeout(function(){
						//替换默认图 
		   				let html = '<img id="J-titleImg-container" src="'+yktGlobal.upload+res+'">';	
		   				$('.upload-cover-pic').append(html);
						$(btn).siblings('.ui-dialog-loading').remove();
					},2000)
				}
			}

			//标题图
			ykt.AjaxUploadPic('#J-titleImg-button','#J-titleImg-status','#J-titleImg-inputVal','#J-titleImg-container','titleImg',640,360,uploadCallback);		

			/* validate验证 */

			$('input[name="release"]').on('click',function(){
				validateForm()
			});	

	  		function validateForm(){ 
	  			$("#J-upload-form").validate({
	  				rules: {
	  					title:{ 
	  						required: true
	  					},
	  					summary:{
	  						required: true
	  					},
	  					agreement:{
	  						required: true	
	  					},
	  					title_img: {
	  						required: true
	  					},
	  				},
	  				messages:{
	  					title:{
	  						required:"标题不能为空"
	  					},
	  					summary:{
	  						required:"简介不能为空"	
	  					},
	  					agreement:{
	  						required:"请勾选原创声明（暂不接受非原创投稿）"	
	  					}
	  				},		 				
	  				errorPlacement: function(error, element) {
	  					error.appendTo( element.siblings('.msg-span'));
	  				}, 
	  				submitHandler:function(form) {
			  			var titleImg  = $('#J-titleImg-inputVal').val();
			  			if(!titleImg){
			  				dialog({
			  					title:'消息',
			  					content:'标题图不能为空',
			  					okValue:'确定',	
			  					fixed:true,
			  					ok:function(){ykt.animateTo(0,0)}					
			  				}).width(400).show();	
			  				return false;
			  			}	
		  				dialog({
		  				    title: '消息',
		  				    content: '确认填写信息',			
		  				    okValue: '确定',
		  				    cancelValue: '取消',
		  				    cancel: function () {},
		  				    ok: function () {
		  				    	form.submit();
		  				    }
		  				}).width(400).showModal();
	  				}									
	  			});			
	  		}
		} else if(yktGlobal.pageName == 'detail') {

	        //浏览文章
	        $.ajax({
	            url: yktGlobal.controller+'/doView',
	            type: 'GET',
	            data: {aid: articleId}
	        })   
	
	        //获取评论
	        $.ajax({
	            url: '/Index/ArticleComment/getComment',
	            type: 'POST',
	            dataType: 'html',
	            data: {aid: articleId} 
	        })          
	        .done(function(data) {
	        	$(data).fadeIn().prependTo($('#J-comment-area'));

				//获取头像
				var _avatarImg = $('#J-header-avatar').prop('src');
				$('#J-comment-avatar').prop('src',_avatarImg);

				//全局变量
				var g_userId,g_myReply=false;

				//遍历有回复内容的显示出来
				$('.reply-list').each(function(){
					var _replylist = $(this).find('.inside-list').length;
					if(_replylist){
						$(this).find('.J-comment-reply-bd').show();
					} 
				});

				//表情展开
				$('#J-comment-wrap').delegate('.J-expression-btn','click',function(){
					var isOpen = $(this).hasClass('open');
					if(!isOpen){
						$(this).addClass('open');
						$(this).siblings('.J-expression-box').addClass('open');
					}else{
						$(this).removeClass('open');
						$(this).siblings('.J-expression-box').removeClass('open');
					}
				});

				//表情插入
				$('#J-comment-wrap').delegate('.J-expression-box a','click',function(){
					var _parent = $(this).closest('.J-expression-wrap')
					//文本对象
					var _siblingTextArea = _parent.siblings('.comment-textarea');
					//表情按钮
					var _expressionBtn = _parent.find('.J-expression-btn');
					var _val = $(this).text();
					var _sourceVal;

					//判断编辑框类型
					if(_siblingTextArea.prop('contenteditable') == 'true'){
						_sourceVal = _siblingTextArea.text();
						_siblingTextArea.text(_sourceVal+_val);
					}else{
						_sourceVal = _siblingTextArea.val();
						_siblingTextArea.val(_sourceVal+_val);
					}

					//移除
					_expressionBtn.removeClass('open');
					$(this).closest('.J-expression-box').removeClass('open');

				});

				//一级回复
				$('#J-comment-btn').on('click',function(){
					if(!ykt.isLogin()){
						ykt.LoginDialog()	
						return false;
					}
					var _this = $(this),
						_val = $.trim($('.comment-textarea').val()), 
						_articleId = $('#J-article-id').attr('data-articleId'),
						_userAvatar = $('#J-header-avatar').prop('src');

					if (!_val) {
						dialog({
							title:'消息',
							content:'您还没有评论，先写点什么吧',
							okValue:'确定',	
							fixed:true,
							ok:function(){}					
						}).width(400).showModal();		  			
					} else {
						$.ajax({
							url:commentUrl,
							data:{aid: _articleId, content: _val},
							type:'post',
							dataType:'json',
							success:function(data){		
								var _html = '<li class="reply-list clearfix" data-topid="'+data["id"]+'"><div class="hd"><div class="avatar"><a href="#"><img src="'+_userAvatar+'"/></a></div><div class="level">作者</div></div><div class="bd J-comment-reply-content"><div class="name">'+yktGlobal.userName+'</div><span class="time">'+data["add_time"]+'</span><div class="txt">'+_val+'</div><div class="comment-tool"><ul class="frame"><li class="comment-tool-list J-comment-like" data-switch="true">顶[<span class="num">0</span>]</li><li class="comment-tool-list J-comment-reply-hd">回复</li></ul></div><div class="inside J-comment-reply-bd clearfix"><ul class="J-second-reply-frame"></ul><div class="reply-comment fr J-reply-comment-hd">我也要回复</div><div class="J-reply-comment-bd clearfix"><div class="comment-textarea" contenteditable="true"><span class="J-reply-obj"></span></div><div class="u-btn u-btn-primary u-btn-size-1 radius fr J-second-reply">回复</div></div></div></div></li>'
								if(!$('#J-reply-frame').html()){
									$('#J-comment-area').append('<ul id="J-reply-frame" class="reply" style="display: block;"></ul>')
								}
								$(_html).fadeIn().prependTo($('#J-reply-frame'));

								$('#J-comment-textarea').val(''); 
							}
						})
					}
				});
				//二级回复
				$('#J-comment-wrap').delegate('.J-second-reply','click',function(){
					if(!ykt.isLogin()){
						ykt.LoginDialog()	
						return false;
					}				
					var _this = $(this),
						//查找二级回复数量
						replyInside = $(this).closest('.J-comment-reply-content').find('.inside-list').length,
						// 评论内容
						_val = $.trim(_this.siblings('.comment-textarea').text()),
						//文章ID
						_articleId = $('#J-article-id').attr('data-articleId'),
						//序列ID
						_top_id = $(this).parents('.reply-list').attr('data-topId'),
						// 用户头像
						_userAvatar = $('#J-header-avatar').prop('src');

						//判断二级、三级评论
						if(replyInside && g_myReply){ 		
					    		//回复人
					    	var	_to_uid = g_userId,
					    		_val = $.trim(_this.siblings('.comment-textarea').text()),
					    		ajaxData = {aid: _articleId, content: _val,top_id:_top_id,to_uid:_to_uid}						
						} else {
							//二级评论ID
							ajaxData = {aid: _articleId, content: _val,top_id:_top_id}
						}

						if (!_val) {
							dialog({
								title:'消息',
								content:'您还没有评论，先写点什么吧',
								okValue:'确定',	
								fixed:true,
								ok:function(){}					
							}).width(400).showModal();		  			
						} else {
							$.ajax({
								url:commentUrl,
								type:'POST',
								data:ajaxData,
								dataType:'json',
								success:function(data){	
									_this.parents('.J-comment-reply-bd').find('.J-second-reply-frame').append('<li class="inside-list clearfix animate"><div class="inside-l"><div class="inside-avatar"><a href="#"><img src="'+_userAvatar+'"></a></div></div><div class="bd J-comment-reply-inside-content"><div class="name">'+yktGlobal.userName+'</div><span class="time">'+data["add_time"]+'</span><div class="txt">'+_val+'</div><div class="comment-tool"><ul class="frame"><li class="comment-tool-list J-comment-reply-inside-hd">回复</li></ul></div></div></li>');
									_this.parents('.J-comment-reply-bd').find('.comment-textarea').empty();		
									_this.parents('.J-reply-comment-bd').hide();

								}
							})
						} 
				});

				// 点三级回复
				$('#J-comment-wrap').delegate('.J-comment-reply-inside-hd','click',function(){
					if(!ykt.isLogin()){
						ykt.LoginDialog()	
						return false;
					}					
					g_myReply = true;           
					var name = $(this).parents('.J-comment-reply-inside-content').find('.name').text();
					// $('.J-reply-comment-bd').hide();
					$(this).parents('.J-comment-reply-bd').children('.J-reply-comment-bd').show();
					$(this).parents('.J-comment-reply-bd').find('.comment-textarea').empty();
					$(this).parents('.J-comment-reply-bd').find('.comment-textarea').append('<span class="J-reply-obj">回复@'+name+'：</span>');

					var _this = $(this);
					g_userId = _this.parents('.inside-list').find('.inside-avatar').attr('data-userid');
				});	

				//点击二级回复
				$('#J-comment-wrap').delegate('.J-comment-reply-hd','click',function(){
					if(!ykt.isLogin()){
						ykt.LoginDialog()	
						return false;
					}
					//隐藏其他回复框
					$(this).closest('.reply-list').siblings('.reply-list').find('.J-comment-reply-bd').hide();

					var _replayFrame = $(this).parents('.J-comment-reply-content').find('.J-comment-reply-bd'),
						isHidenVal = _replayFrame.is(":visible");

						if(isHidenVal){
							_replayFrame.hide();
						} else {
							_replayFrame.show();
						}

					//二级回复数量
					var replyInside = $(this).closest('.J-comment-reply-content').find('.inside-list').length;
					if(!replyInside){
						$(this).parents('.J-comment-reply-content').find('.J-reply-comment-hd').hide().end()
																   .find('.J-reply-comment-bd').show();	 
					}	
				});

				// 点击我也要回复
				$('#J-comment-wrap').delegate('.J-reply-comment-hd','click',function(){
					if(!ykt.isLogin()){
						ykt.LoginDialog()	
						return false;
					}					
					g_myReply = false;
					$(this).nextAll('.J-reply-comment-bd').show().find('.J-reply-obj').remove();	
				});	

				//顶
				$('#J-comment-wrap').delegate('.J-comment-like','click',function(){
					var _this = $(this);
					var _id = _this.closest('.reply-list').attr('data-topid')
					var _num = _this.find('.num');
					var _numVal = _num.text();
					// 开关
					var _switch = _this.attr('data-switch');

					if(_switch == 'true'){
						_numVal++;

						$.ajax({
							url:likeUrl,
							data:{id:_id},
							type:'post',
							success:function(data){
								_num.text(_numVal);
								_this.css('color','#00aeb6')
								_this.attr('data-switch','false')
							}
						})
					}
				});

				/*外分页
				* type: 1->获取当前数字 2->获取属性data-order
				*/
				function pageControl(obj,type){		
					$('#J-comment-wrap').delegate(obj,'click',function(){
						var _order = $(this).attr('data-order');
						var _num;				
						//1.中间数字，2获取 data-page页数
						if(type == 1){
							_num = $(this).text();
						}else{
							_num = $(this).attr('data-page');
						}
						var _articleId = $('#J-article-id').attr('data-articleId');

						$.ajax({
							url:pageUrl,
							type:'post', 
							data:{
								aid:_articleId,
								currentPage:_num,
								order:_order
							},
							dataType: 'html',
							success:function(data){
								$('#J-comment-area').empty();
								$('#J-comment-area').append(data);
							}
						})
					})	
				}

				pageControl('.J-paginator',1);
				// 上下一页
				pageControl('.paginator-item-next',2);
				pageControl('.paginator-item-prev',2);

				pageControl('.J-comment-type',2);

				// 内分页
				$('#J-comment-wrap').delegate('.J-inside-paginator','click',function(){
					var _this = $(this);
					//点击的页码
					var _num = $(this).text();
					var _top_id = $(this).parents('.reply-list').attr('data-topId');
					// 内层评论数
					var _amount = $(this).parents('.J-second-reply-page').find('.J-reply-num').text();

					$.ajax({
						url:insidePageUrl,
						type:'post',
						data:{
							top_id:_top_id,
							reply_num:_amount,
							p:_num
						},
						dataType: 'html',
						success:function(data){

							_this.closest('.J-second-reply-page').empty().append(data); 
						}
					})
				})	
	        })

		    //收藏
		    $('#J-collect').click(function(){     
		        if(!ykt.isLogin()){
		        	ykt.LoginDialog();
		        	return false;
		        } 

		        var _switch = $(this).attr('data-switch');
		        var _this = $(this)
		             
		        if(_switch == 'true'){

		            $.ajax({
		                url: yktGlobal.controller+'/doCollect',
		                type: 'POST',
		                dataType: 'json',
		                data: {aid: articleId,cat_id:catId,type:'+'}
		            })            
	                .done(function(data) {
	                    if(data.code == 1){
	                        _this.addClass('active').attr('data-switch','false').find('p').html('收藏成功');
	                    }else if(data.code == 2){
	                        _this.addClass('active').attr('data-switch','false').find('p').html('已收藏过');
	                    }
	                })
	            }
		    })

		    //点赞
		    $('#J-like').click(function(){
		        if(!ykt.isLogin()){
		        	ykt.LoginDialog();
		        	return false;
		        }      
		        var _switch = $(this).attr('data-switch');
		        
		        if(_switch == 'true'){
		            $.ajax({
		                url: yktGlobal.controller+'/doView',
		                type: 'POST',
		                dataType: 'json',
		                data: {aid: articleId}
		            })            
		            $(this).addClass('active').attr('data-switch','false').find('p').html('已点赞');
		        }
		    })

			//显示评论		
			$('#J-open-comment').on('click',function(){
				var _isDisplay = $(this).css('display');
				if(_isDisplay == 'block'){
					$(this).hide();
					$('#J-comment-wrap').fadeIn();
				}
			}) 

			$('#J-open-comment').hide();
			$('#J-comment-wrap').fadeIn();

		}
	})
});