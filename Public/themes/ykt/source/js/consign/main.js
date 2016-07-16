define(function(require, exports, module) {
	var $ = require('jquery');
	window.$ = $;
	var ykt = require('common');
	$(function(){
		require('../common/button');
		require('validate');
		//axajuplaod组件
		require('../widget/ajaxupload/ajaxupload'); 	
		require('artDialog');	 

		//上传图片回调 
		function uploadCallback(res,btn,txt,inputVal,imgContainer,type,picWidth,picHeight){

	   		var existImg = $(imgContainer).prop('src');
			$(inputVal).val(res);
			//标题图、创作环境
			if(type == 'cover'){
				$(imgContainer).remove();
				$(btn).css('background','none').prepend('<div class="ui-dialog-loading"></div>');
				setTimeout(function(){
					//替换默认图
	   				let html = '<img id="J-cover-container" class="J-cover-container" src="'+yktGlobal.upload+res+'!c160x160.jpg"/>';	
	   				$('#J-cover-button').append(html);
	   				$('#J-cover-button .msg-span').remove();
					$(btn).find('.ui-dialog-loading').remove();
				},2000)
			//套装
			}else if(type == 'suit'){
	        	var _html = '';
	        	var _name = 'suit';
	        	var _count = $(btn).attr('data-i');
	        	var _wrapObj = $('#J-'+_name+'-img-wrap-'+_count)
	        	//清空，加loading
	        	_wrapObj.empty().prepend('<div class="ui-dialog-loading"></div>');

	        	setTimeout(function(){
	            	_html='<li class="upload-pic-list"><div class="u-small-close"></div><div class="u-opacity-bg"></div><img id=J-'+_name+'-container-'+_count+' class="J-img-container" src="'+yktGlobal.upload+res+'"/></li>';
	            	
	            	_wrapObj.append(_html);
	            	//移除loading
	            	_wrapObj.find('.ui-dialog-loading').remove();
            	},2000)

				// 图片删除
				$('#J-img-wrap'+_count+' .u-small-close').on('click',function(){
					$('#J-img-wrap'+_count+' li').remove();
				});   					
			}
	                        	
		}	

		if(yktGlobal.pageName == 'apply' || yktGlobal.pageName == 'edit'){
			
		    var _groudAttr = $('#J-group-attr');        //套装配搭input
		    var _setType = $('input[name="multi"]');   //0-无搭配 1-1个私有属性 2-2个私有属性
		    var _topAttr = $('input[name="top_attr"]');		

	        //UE配置
	        UE.getEditor("editorDetail",{
	            serverUrl :'/Index/Ueditor/index.html',
	            initialFrameWidth:765,
	            initialFrameHeight:200
	        });         

	        UE.getEditor("editorParam",{
	            serverUrl :'/Index/Ueditor/index.html',
	            initialFrameWidth:765,
	            initialFrameHeight:200
	        });     
	        if(yktGlobal.pageName == 'apply'){
		        var _paramHtml = '';

					switch(parseInt(catId)){
					case 4:
					  _paramHtml = '<p>【页数】</p><p>【纸张】</p><p>【公式站】</p>';
					  break;
					case 6:
					  _paramHtml = '<p>【规格】cm循环图案</p><p>【总长】 米</p><p>【公式站】</p>';
					  break;
					case 7:
					  _paramHtml = '<p>【规格】</p><p>【材质】</p><p>【公式站】</p>';
					  break;
					}
				setTimeout(function(){
					UE.getEditor('editorParam').setContent(_paramHtml);
				},4000)
			}

			//添加限制
			function attrRule(num,multi){
				if(multi == 1){
					//单属性不能大于5个
					if(num > 20){
						dialog({
							title:'消息',
							content:'属性值不能大于20个',
							okValue:'确定',	
							fixed:true,
							ok:function(){}					
						}).width(400).show();	
						return false;
					}
					//大单属性大于3时隐藏添加行
					if(num > 3){
						$('#J-add-attr-btn').fadeOut();
					}
				}else if(multi >= 2){
					//单属性不能大于3个
					if(num > 3){
						dialog({
							title:'消息',
							content:'属性值不能大于3个',
							okValue:'确定',	
							fixed:true,
							ok:function(){}					
						}).width(400).show();	
						return false;
					}
				}
				return true;
			}

			//异步获取结果
			function attrAjax(){
				var _data = decodeURIComponent($('#J-ul-attr .input-edit').serialize());
				var _multi = $('input[name="multi"]').val();
				var _attrArr = []; 

				//获取用户填写值
				$('#J-result-wrap .J-attr-fill').each(function(i,e){
					var _mark = $(this).closest('tr').attr('attr-mark');
					var _name = $(this).prop('name').match("price|num|img");
					var _val = $(this).val();
					_attrArr.push('attr_fill['+_mark+']['+_name[0]+']='+_val);
				}) 
				_attrArr = _attrArr.join('&');
				$.ajax({
					url: '/Index/Consign/attr_form',
					type: 'post',
					data: _data+'&multi='+_multi+'&'+_attrArr
				})
				.done(function(data) {
					$('#J-result-wrap').empty().append(data);

					//绑定图片上传
					var _resultNum = $('.result-list').length;
					for(var i = 1; i <= _resultNum; i++){
					ykt.AjaxUploadPic('#J-suit-button-'+i,'#J-suit-status-'+i,'#J-suit-inputVal-'+i,'#J-suit-container-'+i,'suit',600,600,uploadCallback);
					}	
				})
			}

			$('.J-radio-hide-error').on('click',function(){
				$(this).closest('.frame').find('.msg-span').remove();
			})
			//无套装 
			$('#J-no-attr').on('click',function(){
				var no_attr_html ='<div class="m-attr-data clearfix">'
						+'	<div class="item m-tooltip-wrap">'			
						+'		<input class="u-input-text" type="text" name="stock" placeholder="请输入数量" data-rule-required="true" data-msg-required="数量不能为空" data-rule-digits="true" data-msg-digits="数量只能输入整数">'
						+'		<span class="unit">件</span>'
						+'  	<span class="tooltip J-msg-span"></span>'
						+'  </div>'
						+'	<div class="item m-tooltip-wrap">'
						+'		<input class="u-input-text" type="text" name="price" placeholder="请输入售价" data-rule-required="true" data-msg-required="售价不能为空" data-rule-number="true" data-msg-number="请输入合法的售价">'
						+'		<span class="unit">元</span>'
						+'  	<span class="tooltip J-msg-span"></span>'
						+'  </div>'
						+'</div>';

				$(this).siblings().removeClass('active').end().addClass('active');
				$('#J-attr-frame').empty().append(no_attr_html);
				
				_setType.val('0');	

				// 设置隐藏域行数
				$('input[name="row"]').val('0')			
			})
			// 多套装
			$('#J-multi-attr').on('click',function(){
			   var _html='<div class="multi-attr-content"><ul id="J-ul-attr"class="top"><li class="J-attr-row clearfix" data-row="1"><div class="J-attr-title attr-title m-tooltip-wrap"><input class="J-input-edit input-edit"type="text"name="attr_des[1][title]"placeholder="输入属性名"data-rule-required="true"data-msg-required="属性名不能为空"/><span class="tooltip J-msg-span"></span></div><div class="J-attr-val-frame attr-val"><div class="J-attr-val-list attr-val-list m-tooltip-wrap"><input class="J-input-edit input-edit"type="text"name="attr_des[1][val][1]"placeholder="输入属性值"data-rule-required="true"data-msg-required="属性值不能为空"/><i class="m-close close J-delete-attr-val">x</i><span class="tooltip J-msg-span"></span></div><a class="add-btn J-add-attr-val">+添加</a></div><i class="close-item m-close J-delete-attr-row">x</i></li></ul><a id="J-add-attr-btn"class="add-attr-btn"href="javascript:;">添加规格项目</a><div id="J-result-wrap"></div></div>';	
				//初始化行数
				_setType.val(1);
				//高亮切换
				$(this).siblings().removeClass('active').end().addClass('active');
				//多套装模板
				$('#J-attr-frame').empty().append(_html);
			})	
			//新增值 
			$("#J-attr-frame").delegate(".J-add-attr-val","click",function(){
				//获取父级li
				var _parentsLi = $(this).closest('li');
				//计算当前值数量
				var _num = _parentsLi.find('.J-attr-val-list').length + 1;
				var _multi = $('input[name="multi"]').val();

				//获取行数
				var _row = _parentsLi.attr('data-row');;
				var _html  ='<div class="J-attr-val-list attr-val-list m-tooltip-wrap">'
						   +' 	<input class="J-input-edit input-edit" type="text" name="attr_des['+_row+'][val]['+_num+']" placeholder="输入属性值" data-rule-required="true" data-msg-required="属性值不能为空"/>'
						   +'		<i class="m-close close J-delete-attr-val">x</i>'
						   +'		<span class="tooltip J-msg-span"></span>'
						   +'</div>'
				if(attrRule(_num,_multi)){
					$(this).before(_html);
				};

				attrAjax();
			})
			//删除值 
			$("#J-attr-frame").delegate(".J-delete-attr-val","click",function(){
				//获取父级li
				var _parentsLi = $(this).closest('li');
				//获取行数
				var _row = _parentsLi.attr('data-row');
				//计算当前值数量
				var _attrListNum = _parentsLi.find('.J-attr-val-list').length;

				if(_attrListNum == 1){
					dialog({
						title:'消息',
						content:'最少填写1个值',
						okValue:'确定',	
						fixed:true,
						ok:function(){}					
					}).width(400).show();	
					return false;
				}
				//删除当前值
				$(this).closest('.J-attr-val-list').remove();
				//遍历修改name值 
				_parentsLi.find('.J-attr-val-list').each(function(i,e){
					var _num = ++i;
					$(this).find('input').prop('name','attr_des['+_row+'][val]['+_num+']')
				})

				//值少于3个时显示
				_attrListNum <= 4 ? $("#J-add-attr-btn").fadeIn():'';

				attrAjax();
			})
			//增加属性
			$("#J-attr-frame").delegate("#J-add-attr-btn","click",function(){
				//计算当前值数量
				var _num = $('.J-attr-row').length + 1;
				//设置属性数量
				_setType.val(_num)
				//获取行数
				var _row = _setType.val();
				var _html  ='<li class="J-attr-row clearfix" data-row="'+_row+'">'
						   +'	<div class="J-attr-title attr-title m-tooltip-wrap">'
						   +'			<input class="J-input-edit input-edit" type="text" name="attr_des['+_row+'][title]" placeholder="输入属性名" data-rule-required="true" data-msg-required="属性名不能为空"/>'
						   +'			<span class="tooltip J-msg-span"></span>'
						   +'		</div>'
						   +'		<div class="J-attr-val-frame attr-val">'
						   +'			<div class="J-attr-val-list attr-val-list m-tooltip-wrap">'
						   +'				<input class="J-input-edit input-edit" type="text" name="attr_des['+_row+'][val][1]" placeholder="输入属性值" data-rule-required="true" data-msg-required="属性值不能为空"/>'
						   +'				<i class="m-close close J-delete-attr-val">x</i>'
						   +'				<span class="tooltip J-msg-span"></span>'
						   +'			</div>'
						   +'			<a class="add-btn J-add-attr-val">+添加</a>'
						   +'		</div>'
						   +'		<i class="close-item m-close J-delete-attr-row">x</i>'
						   +'</li>'					   

				$('#J-ul-attr').append(_html);
				//双属性以上隐藏添加按钮
				_num >=2 ? $(this).fadeOut():'';
			})
			//删除属性
			$("#J-attr-frame").delegate(".J-delete-attr-row","click",function(){
				//获取行数
				var _row = _setType.val();
				//获取父级li
				var _parentsLi = $(this).closest('li');
				//计算当前值数量
				var _attrListNum = $('.J-attr-row').length;

				if(_attrListNum == 1){
					dialog({
					title:'消息',
					content:'最少填写1行',
					okValue:'确定',	
					fixed:true,
					ok:function(){}					
					}).width(400).show();	
					return false;
				}
				//删除当前行
				_parentsLi.remove();
				//行数减少
				_row = --_row
				_setType.val(_row);
				//遍历修改name值 
				$('.J-attr-row').each(function(i,e){
					$(this).attr('data-row',_row)
					$(this).find('.J-attr-title input').prop('name','attr_des['+_row+'][title]');

					$(this).find('.J-attr-val-list').each(function(i,e){
						var _num = ++i;
						$(this).find('input').prop('name','attr_des['+_row+'][val]['+_num+']')
					})
				})
				//双属性以下显示添加按钮
				_row <2 ? $("#J-add-attr-btn").fadeIn():'';
				attrAjax();
			})
			//动态发送数据
			$("#J-attr-frame").delegate(".J-input-edit","blur",function(){
				var _val = $(this).val();
				_val? attrAjax(): '';
			})

			//绑定主图
			ykt.AjaxUploadPic('#J-cover-button','#J-cover-status','#J-cover-inputVal','#J-cover-container','cover',640,360,uploadCallback);	

			//验证
			$("#J-apply-form").validate({
				rules: {
					goods_name:{ 
						required: true,
						stringCheck:true
					},
					cp:{
						stringCheck:true	
					},
					instruction:{
						stringCheck:true
					},
					claim:{
						stringCheck:true
					},
					agreement:{
						required:true
					},
					author:{
						required:true
					}
				},
				messages:{ 
					goods_name:{
						required:"名称不能为空"
					},	
					sample:{
						required:"请选择样品提供"
					},		
					agreement:{
						required:'请勾选阅读并且同意寄售须知条款以及收费标准'
					},
					author:{
						required:"作者不能为空"
					}
				},		 				
				errorPlacement: function(error, element) {
					error.appendTo( element.siblings('.J-msg-span'));
				}, 
				submitHandler:function(form) {

					//公有属性
					var _publicArr = [];
					var _publicNum = 0;   

					var _detailImgVal ='';
					$('.J-public-attr').each(function(){

						var _label = $(this).find('.s-p-label'); 
						var _hasActive = _label.hasClass('active');   //选中项
						var _labelVal;

						if(_hasActive){
							_labelVal = $(this).find('.active input').val();     
							_publicArr[_publicNum] = _labelVal;
							_publicNum++;
						}
					})
					_publicArr = _publicArr.join();   //转为字符串	
					$('input[name="public_attr_ids"]').val(_publicArr)
					
					// 宣传图
					$('#J-detail-pic li').each(function(){
						var _img = $(this).find('img').prop('src');
						_detailImgVal += '<p><img src="'+_img+'"/></p>'
					})
					$('input[name="goods_detail"]').val(_detailImgVal);

					/**
					 * 检测主图是否空
					 */
					var _goodsImagesVal = $('input[name="goods_img"]').val();
					
					if(!_goodsImagesVal){
						$('#J-cover-container .msg-span').text('主图不能为空');
						$('body,html').animate({ 'scrollTop': '0' }, 100);
						return false;
					}		  			
	  		 
					/**
					 * 检测参数是否空
					 */
		            let paramVal = UE.getEditor('editorParam').getContent();
		           
					if(!paramVal){
						dialog({
						title:'消息',
						content:'商品参数不能为空',
						okValue:'确定',	
						fixed:true,
						ok:function(){}					
						}).width(400).show();	

						return false;		     
					}

					/**
					 * 检测详细是否空
					 */ 
					let detailVal = UE.getEditor('editorDetail').getContent();
					if(!detailVal){
						dialog({
						title:'消息',
						content:'商品详细不能为空',
						okValue:'确定',	
						fixed:true,
						ok:function(){}					
						}).width(400).show();	  
						return false;		     
					}					
					
					dialog({
						title:'消息',
						content:'确认寄售请点击提交审核',
						okValue:'提交寄售',
						cancelValue:'取消返回',
						fixed:true,
						ok:function(){form.submit()},
						cancel:function(){}
					}).width(400).showModal();		
				}									
			});				

			if(yktGlobal.pageName == 'edit'){

				//判断是否多套装
				var _isMulti = $('#J-multi-attr').hasClass('active');

				if(_isMulti){
					//获取绑定图片个数
					var _uplaodNum = $('.m-attr-data:last').find('.upload-btn').attr('data-i');
					if(_uplaodNum){
						for (var i=1;i<=_uplaodNum;i++){
							// 绑定套装图
							ykt.AjaxUploadPic('#J-suit-button-'+i,'#J-suit-status-'+i,'#J-suit-inputVal-'+i,'#J-suit-container-'+i,'suit',600,600,uploadCallback);							
						}
					}
				}		
			}
		}else if(yktGlobal.pageName == 'detail'){
			$('#J-result-wrap .input-edit').attr('readonly',true);
			//保存快递单号
			$('#J-express-save').on('click',function(){

				dialog({
					title:'消息',
					content:'确认快递单号',
					okValue:'确认',
					cancelValue:'取消',
					fixed:true,
					ok:function(){
						var _cid = $('input[name="cid"]').val();
						var _expressNum = $('input[name="express_num"]').val();
						var _expressAbbr = $('input[name="express_abbr"]').val();

						if(_expressNum && _expressAbbr){
							$.ajax({
								url: '/Index/Consign/expressFill',
								type: 'post',
								dataType: 'json',
								data: { cid: _cid,
										express_num:_expressNum,
										express_abbr:_expressAbbr
								},
							})
							.done(function(data) {
								if(data.code){
									$('#J-status').text('已经寄出货品');
									$('#J-express-frame').html('<span class="show-txt">'+_expressNum+'</span>');
								}else{
									dialog({
										title:'消息',
										content:data.msg,
										okValue:'确定',	
										fixed:true,
										ok:function(){}					
									}).width(400).show();	
								}
							})				
						}else{
							dialog({
								title:'消息',
								content:'快递公司或快递号不能为空',
								okValue:'确定',	
								fixed:true,
								ok:function(){}					
							}).width(400).show();	
							return false;
						}
					},
					cancel:function(){}
				}).width(200).showModal();	
			})
		}else if(yktGlobal.pageName == 'pre_apply'){
			var typeId;
			$('#J-apply-type li').on('click',function(){
				typeId = $(this).attr('data-id');
				$(this).addClass('active').siblings().removeClass('active');
				$('#J-next-btn').prop('href','/Index/Consign/apply/cat/'+typeId+'.html')
			})
		}
	})
});