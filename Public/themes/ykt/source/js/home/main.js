define(function(require, exports, module) {
	
	var $ = require('jquery');
	//通用组件
	var ykt = require('common');
	//artDialog组件
	require('artDialog');
	//axajuplaod组件
	require('../widget/ajaxupload/ajaxupload');   	
	//验证组件
	require('validate');
	require('../common/button');
	require('datepicker'); 
	require('datepair');   

	//获取页面名
	var _pageUrl = window.location.href;
	var _pageUrlArray = _pageUrl.split('/Home/');
	_pageUrl = _pageUrlArray[1];
	// _pageUrl = _pageUrl.split('.html')
	// _pageUrl = _pageUrl[0]

	function pageControl(ajaxUrl,container){

		var _ajaxStatus = 0;  //类型ID
		var _pageNum = 1;		
		// 初始化
		$('.J-page-item').off('click');
		$('#J-page-prev').off('click');
		$('#J-page-next').off('click');

		//类型
		$('#J-type dd').off('click');
		$('#J-type dd').on('click',function(){
			//获取ID
			_ajaxStatus = $(this).attr('data-status'); 
			//改变状态
			$(this).siblings().removeClass('active').end().addClass('active');

			ajaxPage(ajaxUrl,container,_pageNum,_ajaxStatus);
		})			
	
		/*
		* 异步分页
		*	ajaxUrl    地址
		*	container  容器
		*	pageNum    页数
		*	ajaxStatus 状态
		*/
		function ajaxPage(ajaxUrl,container,pageNum,ajaxStatus){

			var scrollTop = $('.m-content').offset().top;

			ykt.faLoading('J-fa-loading',container)
			$.ajax({
				url: ajaxUrl,
				type: 'POST',
				dataType: 'html',
				data: {p:pageNum,item:ajaxStatus}
			})
			.done(function(data) {
				setTimeout(function(){
					$(container).empty().append(data);
					pageControl(ajaxUrl,container);
				},500)
			})		

			ykt.animateTo(scrollTop);
		}

		//分页
		$('.J-page-item').on('click',function(){
			//获取页数
			var _pageNum = $(this).text();
			//改变状态
			$(this).siblings().removeClass('paginator-item-selected').end().addClass('paginator-item-selected');
			ajaxPage(ajaxUrl,container,_pageNum);
		})

		//上一页
		$('#J-page-prev').on('click',function(){

			//当前对象
			var _currentObj = $('.m-paginator .paginator-item-selected');
			//当前页数
			var _currentNum = _currentObj.text();
			//大于1时
			if(_currentNum > 1){
				_currentNum--;
				ajaxPage(ajaxUrl,container,_currentNum);	
				_currentObj.removeClass('paginator-item-selected').prev().addClass('paginator-item-selected');
			}
		})

		//下一页
		$('#J-page-next').on('click',function(){

			//当前对象
			var _currentObj = $('.m-paginator .paginator-item-selected');
			//当前页数
			var _currentNum = _currentObj.text();

			//总页数
			var _totalPageNum = $('input[name="totalPageNum"]').val(); 

			//小于总数时
			if(_currentNum < _totalPageNum){
				_currentNum++;
				ajaxPage(ajaxUrl,container,_currentNum);
				_currentObj.removeClass('paginator-item-selected').next().addClass('paginator-item-selected');
			}
		})	
	}

	if(yktGlobal.pageName == 'home_avatar'){
		// 头像上传组件
		var qq= require('../common/fileuploader'); 
		require('../common/jcrop'); 
		
		// 头像上传
		var g_oJCrop = null,
		    AVATAR_WIDTH = 90,
		    AVATAR_HEIGHT = 90,
		    AVATAR_MAX_WIDTH = 500,
		    AVATAR_MAX_HEIGHT = 1000;

		$(function(){
			new qq.FileUploader({
				element: document.getElementById('upload_avatar'),
	            action: avatarUpload,
				multiple: false,
				disableDefaultDropzone: true,
				allowedExtensions: ["jpg", "jpeg", "png", "gif"], 
				uploadButtonText: '选择头像图片',
				onComplete: function(id, fileName, json) {
					if(json.success)
					{
						if(g_oJCrop!=null) g_oJCrop.destroy();
						
						$("#crop_tmp_avatar").val(json.tmp_avatar);
						$("#crop_container").show();
						$("#crop_target, #crop_preview").html('<img src="/Uploads/avatar/'+json.tmp_avatar+'">');

						$('#crop_target img').Jcrop({
							allowSelect: false,
							onChange: updatePreview,
	 						onSelect: updatePreview,
							aspectRatio: AVATAR_WIDTH/AVATAR_HEIGHT,
							minSize:[AVATAR_WIDTH, AVATAR_HEIGHT]
						},function(){
						  	g_oJCrop = this;
							
							var bounds = g_oJCrop.getBounds();
							var x1,y1,x2,y2;
							if(bounds[0]/bounds[1] > AVATAR_WIDTH/AVATAR_HEIGHT)
							{
								y1 = 0;
								y2 = bounds[1];

								x1 = (bounds[0] - AVATAR_WIDTH * bounds[1]/AVATAR_HEIGHT)/2
								x2 = bounds[0]-x1;
							}
							else
							{
								x1 = 0;
								x2 = bounds[0];
								
								y1 = (bounds[1] - AVATAR_HEIGHT * bounds[0]/AVATAR_WIDTH)/2
								y2 = bounds[1]-y1;
							}
							g_oJCrop.setSelect([x1,y1,x2,y2]);
						});
					}
					else
					{
						alert(json.description);
					}
				}
			});
		});
		
		$('#J-save-crop-btn').click(function(){
			saveCropAvatar();
		})
		
		function updatePreview(c){
		    $('#crop_x1').val(c.x);
			$('#crop_y1').val(c.y);
			$('#crop_x2').val(c.x2);
			$('#crop_y2').val(c.y2);
			$('#crop_w').val(c.w);
			$('#crop_h').val(c.h);

			if (parseInt(c.w) > 0){
				var bounds = g_oJCrop.getBounds();

				var rx = AVATAR_WIDTH / c.w;
				var ry = AVATAR_HEIGHT / c.h;
				
				$('#crop_preview img').css({
					width: Math.round(rx * bounds[0]) + 'px',
					height: Math.round(ry * bounds[1]) + 'px',
					marginLeft: '-' + Math.round(rx * c.x) + 'px',
					marginTop: '-' + Math.round(ry * c.y) + 'px'
				});
			}
		};

		function saveCropAvatar(){
			if($("#crop_tmp_avatar").val()==""){

				//artdialog
				var d = dialog({
					title: '消息',
					content: ' 您还没有上传头像',
					fixed: true,
					okValue: '确 定',

					ok: function () {}
				});

				d.width(320)
				d.showModal();	

				return false;
			}
			
			$.ajax({
				type: "POST",
				url: avatarCropUpload,
				data: $("#form_crop_avatar").serialize(),
				dataType: "json",
				success: function(json){
					if(json.success){
						$("#crop_tmp_avatar").val("");
						$("#crop_container").hide();
						$("#my_avatar").html('<img src="'+json.avatar+'">');
						$("#J-header-avatar").prop('src',json.avatar)
						//artdialog
						var d = dialog({
							title: '消息',
							content: ' 保存成功',
							fixed: true,
							okValue: '确 定',

							ok: function () {}
						});
						d.width(320)
						d.showModal();						
					}else{
						alert(json.description);
					}
				}
			});
		}
	}else if(yktGlobal.pageName == 'home_profile'){ 
        $('#J-data').datepicker({
            'format': 'yyyy-m-d', 	
            'autoclose': true,
            updateDate: function($input, dateObj){
	            var picker = $input.data('pikaday');
	            return picker.setDate(dateObj);
        	}
        });

        $('#j-profile-btn').on('click',function(){

        	var _sign = $('textarea[name="sign"]').val();
        	var _sex = $('select[name="sex"] option:selected').val();
        	var _birth = $('input[name="birth"]').val();

        	$.ajax({
        		url: '/Index/User/profileUpdate',
        		type: 'post',
        		dataType: 'json',
        		data: {sign: _sign,sex:_sex,birth:_birth}
        	})
        	.done(function(data) {
        		if(data['code']){
					//artdialog
					var d = dialog({
						title: '消息',
						content: ' 保存成功',
						fixed: true,
						okValue: '确 定',

						ok: function () {}
					});
					d.width(320)
					d.showModal();	
        		}
        	})        	
        })
	}else if(yktGlobal.pageName == 'home_collectGoods'){

		$('.J-cancelCollect').on('click',function(){
			var _obj = $(this).closest('li'); 
			var _id = _obj.attr('data-id');
			$.ajax({
				url: '/Index/Goods/doCollect',
				type: 'post',
				data: {gid: _id,type:'-'}
			})
			_obj.fadeOut();
		})
	}else if(yktGlobal.pageName == 'home_collectArticle'){

		$('.J-cancelCollect').on('click',function(){
			var _obj = $(this).closest('li'); 
			var _id = _obj.attr('data-id');
			$.ajax({
				url: '/Index/Article/doCollect',
				type: 'post',
				data: {aid: _id,type:'-'}
			})
			_obj.fadeOut();
		})		
	}else if(yktGlobal.pageName == 'home_address'){
		
		//新增收货地址
		$("#J-address-wrap").delegate("#J-add-btn","click",function(){

       		var _amount = $('.J-addr-list').length + 1;
       		var _this = $(this);

        	if(_amount <= 5){
        		consigneeDialog('/Index/Address/insert','add',_this);
            }else{
                dialog({
                    title: '提示',
                    content: '最多可创建5个',          
                    okValue: '确定',
                    fixed: true, 
                    ok: function () {} 
                }).width(200).showModal();	
            }   
		});

		//删除收货地址
		$("#J-address-wrap").delegate(".J-consignee-delete","click",function(){
            var _this = $(this);
            var _parentLi = $(this).closest('li');
            var _id = _parentLi.attr('data-id');
            var _amount = $('.J-addr-list').length - 1;

            $.ajax({
                url: '/Index/Address/del',
                data: {id: _id},
            })
            .done(function(data) { 
                _parentLi.remove();    
                $('#J-address-num').text(_amount)       
            })			
		});

		//编辑收货地址
		$("#J-address-wrap").delegate(".J-consignee-edit","click",function(){
            var _this = $(this);
            var _id = $(this).closest('li').attr('data-id');

            //重置地址
            editAddressUrl = '/Index/Address/edit/id/';
            editAddressUrl = editAddressUrl+_id
            consigneeDialog('/Index/Address/update','update',_this);
		});

		//设置默认
		$("#J-address-wrap").delegate(".J-setdefault", "click", function() {
            var _this = $(this);
            var _parentLi = $(this).closest('li');
            var _id = _parentLi.attr('data-id');

            $.ajax({
            	type: 'POST',
                url: '/Index/Address/setDefault',
                data: {id: _id},
            })
            .done(function() {  
                //对应修改默认地址
                $('.J-addr-list').each(function(index, el) {
                    var _isDefault = $(this).hasClass('setDefault');
                    //判断是否默认,
                    if(_isDefault){
                        $(this).removeClass('setDefault');
                        $(this).find('.l-top .default').remove();
                    }
                });
                _parentLi.addClass('setDefault');            
                _parentLi.find('.l-top h3').append('<span class="default">默认地址</span>');

            })   
		});

	    /*新增、编辑收货地址
	    * url     请求地址
	    * type    类型：add,update
	    * that    当前对象
	    */
	    function consigneeDialog(url,type,that){
	        //loading
	        ykt.appendLoading('J-html-loading');

	        var _url = '';
	        //根据类型判断请求添加、编辑接口
	        if(type == 'add'){
	            _url = '/Index/Address/add';
	        }else if(type == 'update'){
	            _url = editAddressUrl;
	        }
	   
	        $.ajax({
	            url: _url,
	            type: 'POST',
	            dataType: 'html'
	        })
	        .done(function(data) {
	            setTimeout(function(){
	                dialog({ 
	                    skin:'consignee-style',
	                    title: '新增收货人信息',
	                    content: data,          
	                    okValue: '保存收货人信息',
	                    fixed: true, 
	                    ok: function () {
	                        function validateForm() {  
	                            return $("#J-consignee-form").validate({
	                                errorPlacement: function(error, element) {
	                                    error.appendTo( element.siblings('.msg-span'));
	                                }
	                            }).form();  
	                        }  
	                          
	                        //验证通过后提交  
	                        if(!validateForm()){  
	                            return false;
	                        }else{
	                            var _realName = $('input[name="realname"]').val();  //收货人
	                            var _province = $("#J-ajax-province").children('option:selected').val();  //省id
	                            var _city = $("#J-ajax-city").children('option:selected').val();           //城市id
	                            var _district = $("#J-ajax-area").children('option:selected').val();           //区id 
	                            var _provinceTxt = $("#J-ajax-province").children('option:selected').text();  //省
	                            var _cityTxt = $("#J-ajax-city").children('option:selected').text();           //城市
	                            var _districtTxt = $("#J-ajax-area").children('option:selected').text();   //区
	                            var _street = $('input[name="street"]').val();           //街
	                            var _mobile = $('input[name="mobile"]').val();           //手机号码 
	                            var _hash = $('#J-consignee-form input[name="__hash__"]').val();           //令牌
	                            var _id = $('input[name="id"]').val();                   //地址id

	                            $.ajax({
	                                url: url,
	                                type:'post',
	                                dataType: 'json',
	                                data: { 
	                                    id:_id,
	                                    realname: _realName,
	                                    province: _province,
	                                    city: _city,
	                                    district: _district,
	                                    street: _street,
	                                    mobile: _mobile,
	                                    __hash__:_hash
	                                },
	                            }) 
	                            .done(function(data) {
	                                var _amount = $('.J-addr-list').length + 1;
	                                var _addId = data['id'];
	                                //新增 
	                                if(type == 'add'){
	                                    var _addHtml = '<li class="J-addr-list" data-id="'+data['id']+'"><div class="l-top"><h3>'+_realName+' '+_provinceTxt+'</h3><div class="extra"><a class="del-btn J-consignee-delete" href="javascript:;">删除</a></div></div><div class="l-content"><div class="item"><span class="label">收货人：</span><div class="addr-name txt">'+_realName+'</div></div><div class="item"><span class="label">所在地区：</span><div class="addr-info txt">'+_provinceTxt+' '+_cityTxt+' '+_districtTxt+'</div></div><div class="item"><span class="label">地址：</span><div class="addr-street txt">'+_street+'</div></div><div class="item"><span class="label">手机：</span><div class="addr-tel txt">'+_mobile+'</div></div><div class="extra"><a class="J-setdefault" href="javascript:;">设为默认地址</a><a class="J-consignee-edit" href="javascript:;">编辑</a></div></div></li>';
	                                    $('#J-consignee').append(_addHtml)
	                                    if(_amount == 1){
	                                        $('.J-addr-list').addClass('selected setDefault');
	                                        $('.J-addr-list .l-top h3').append('<span class="default">默认地址</span>');       
	                                    } 
	                                    $('#J-address-num').text(_amount)                   						
	                                //编辑
	                                }else if(type == 'update'){
	                                	var _parentLi = that.closest('.J-addr-list');
					                    var _isDefault = _parentLi.hasClass('setDefault');
					                    //判断是否默认,
					                    if(_isDefault){
					                        _parentLi.find('.l-top h3').html(_realName+' '+_provinceTxt+'<span class="default">默认地址</span>');
					                    }else{
					                    	_parentLi.find('.l-top h3').html(_realName+' '+_provinceTxt);
					                    }
	                                    
	                                    _parentLi.find('.addr-name').html(_realName);
	                                    _parentLi.find('.addr-info').html(_provinceTxt+' '+_cityTxt+' '+_districtTxt);
	                                    _parentLi.find('.addr-street').html(_street);
	                                    _parentLi.find('.addr-tel').html(_mobile);
	                                }
	                          
	                            })
	                        }  
	                    } 
	                }).width(670).showModal();

	                //城市联动
	                var _province = $("#J-ajax-province");
	                var _city = $("#J-ajax-city");
	                var _area = $("#J-ajax-area");
	                var _option_html;
	                var _currentProvinceVal = _province.children('option:selected').val();
	                var _currentCityVal = _city.children('option:selected').val();

	                function ajaxCity(target,val,type){  
	                    
	                    $.ajax({ 
	                        type:'post',
	                        dataType:'json',
	                        data:{pid: val}, 
	                        url:'/Index/Address/subArea',
	                        success:function(data){
	                            if(data){
	                                //清空
	                                target.empty();
	                                target.show();
	                                $.each(data, function(idx, el) {
	                                    _option_html='<option value='+ el.id +'>'+ el.areaname +'</option>';
	                                    $(_option_html).appendTo(target);
	                                });

	                                //当省改变时，初始化区
	                                if(type=='1'){
	                                    _currentCityVal = _city.children('option:selected').val();
	                                    ajaxCity(_area,_currentCityVal);                                
	                                }
	                            }else{
	                                if(type='1'){
	                                    _city.hide();   
	                                    _area.hide();   
	                                }else{
	                                    target.hide();
	                                }
	                            }
	                        }
	                    })
	                }          

	                // 联动初始化  
	                // ajaxCity(_city,_currentProvinceVal,'1');

	                //省
	                _province.change(function(){
	                    var _provinceVal = $(this).children('option:selected').val();
	                    ajaxCity(_city,_provinceVal,'1')
	                }); 
	                //市          
	                _city.change(function(){
	                    var _cityVal = $(this).children('option:selected').val();
	                    ajaxCity(_area,_cityVal)
	                });               
	                //移除loading
	                $('#J-html-loading').remove();

	            },500)
	        })   
	    }
	}else if(yktGlobal.pageName == 'home_account'){

		$('#J-modify-btn').on('click',function(){
			$('#J-modify_pwd').slideToggle();
		})

		//取消
		$('#J-modify-cancel').on('click',function(){
			$('#J-modify_pwd').slideUp();
		})

		$('#J-modify-submit').on('click',function(){

			/* validate验证 */
			$("#J-modify-form").validate({
				rules: {
					init_pw: {
						required: true
						// remote:{
						// 	url: "checkEmail",   
						// 	type: "post",            
						// 	dataType: "json",        
						// 	data: {               
						// 		email: function() {
						// 			return $("#email").val();
						// 		}
						// 	}
						// } 								
					},
					new_pw: {
						required: true,
						minlength: 6
					},
					renew_pw: {
						required: true,
						minlength: 6,
						equalTo: "#new_pw"
					},
					mobile: {
						required: true,
						isMobile: true
					}
				},
				messages:{
					init_pw:{
						required:"请输入原密码"
					},
					new_pw:{
						required:"请输入新密码"
					},
					renew_pw:{
						required:"请输入重复新密码"
					},
					mobile:{
						required:"请输入手机号码"	
					}
				},						
				errorPlacement: function(error, element) {
					error.appendTo( element.siblings('.error'));
				},
				submitHandler:function(form) {
					
					var _init_pw = $('input[name="init_pw"]').val();
					var _new_pw = $('input[name="new_pw"]').val();
					var _renew_pw = $('input[name="renew_pw"]').val();
					var _mobile = $('input[name="mobile"]').val();

					$.ajax({
						url: '/Index/User/pwUpdate',
						type: 'post',
						dataType: 'json',
						data: {
							init_pw: _init_pw,
							new_pw: _new_pw,
							renew_pw: _renew_pw,
							mobile: _mobile,
						}
					})
					.done(function(data) {
						var _result;
						if(data.code){
							_result = '修改成功'
							$('#J-modify_pwd').slideUp();
						}else{
							_result = data.msg;
						}

						var d = dialog({
							title: '消息',
							content: _result,
							fixed: true,
							okValue: '确 定',
							ok: function () {}
						});
						d.width(320)
						d.showModal();						
					})	
				}							
			});			
		})
	}else if(yktGlobal.pageName == 'home_auth'){

		/* validate验证 */
		$("#J-auth-form").validate({
			rules: {
				realname: {
					required: true
				},
				identity: {
					required: true
				},
				alipay_account: {
					required: true
				},
				alipay_user: {
					required: true
				},
				mobile: {
					required: true,
					isMobile: true
				}
			},
			messages:{
				realname:{
					required:"请输入真实姓名"
				},
				identity:{
					required:"请输入身份证号码"
				},
				alipay_account:{
					required:"请输入支付宝账号"
				},
				alipay_user:{
					required:"请输入支付宝户名"
				},
				mobile:{
					required:"请输入手机号码",
				}
			},						
			errorPlacement: function(error, element) {
				error.appendTo( element.siblings('.error'));
			},
			submitHandler:function(form) {
				
				var _realname = $('input[name="realname"]').val();
				var _identity = $('input[name="identity"]').val();
				var _alipay_account = $('input[name="alipay_account"]').val();
				var _alipay_user = $('input[name="alipay_user"]').val();
				var _mobile = $('input[name="mobile"]').val();
				var _hash = $('input[name="__hash__"]').val();           //令牌

				$.ajax({
					url: '/Index/User/doAuth',
					type: 'post',
					dataType: 'json',
					data: {
						realname: _realname,
						identity: _identity,
						alipay_account: _alipay_account,
						alipay_user: _alipay_user,
						mobile: _mobile,
						__hash__:_hash
					}
				})
				.done(function(data) {
					if(data.code){
						var d = dialog({
							title: '消息',
							content: '提交成功',
							fixed: true,
							okValue: '确 定',
							ok: function () {}
						});
						d.width(320)
						d.showModal();
						$('#J-auth-form').empty().append('<div class="notice">提交成功，等待审核</div>')
					}
				})	
			}							
		});			
	}else if(yktGlobal.pageName == 'home_order_goods_refund'){
		
		function AjaxUploadPic(btn,txt,inputVal,imgContainer){
		    var button = $(btn), interval;
		    var confirmdiv = $(txt);
		    var fileType = "pic",fileNum = "one"; 
		    new AjaxUpload(button,{
		        action: '/Index/Home/upload/folder/refund',
		        name: '/Index/Home', 
		        onSubmit : function(file, ext){ 
		            if(fileType == "pic"){
		                if (ext && /^(jpg|png|jpeg|gif|JPG)$/.test(ext)){
		                     this.setData({'info': '文件类型为图片'});
		                } else {
		                     confirmdiv.text('文件格式错误，请上传格式为.png .jpg .jpeg 的图片').show();
		                     return false;               
		                }
		            }           
		            confirmdiv.text('文件上传中');
		            if(fileNum == 'one')  this.disable();
		        },
		        onComplete: function(file, res){           
		                if(res.indexOf('-')>0){
		                     $(inputVal).val(res);
		                     $(imgContainer).attr("src",'/Uploads'+res).show();                
		                     this.enable();
		                }else{
		                     confirmdiv.text(res).show();
		                     this.enable();
		                     return false;
		                }  
		        }
		    });
		}

		//单图上传
		AjaxUploadPic('.J-upload-button','#J-upload-txt','#J-upload-inputVal','.J-img-container');


		$('.J-choose-goods').on('click',function(){

			var _gpid = $(this).attr('data-gpid');
            var _gid = $(this).attr('data-gid');

			$('input[name="gpid"]').val(_gpid);
            $('input[name="gid"]').val(_gid);		

			// $(this).siblings().removeClass().end().addClass('active');
		})	

		//待删除---end
		$('#J-order-servicer-btn').on('click',function(){
			$(this).submit();
		});
		//验证
		$("#J-order-service-form").validate({
			rules: {
				detail:{ 
					required: true
				},
				type:{
					required:true
				},
				description:{
					required:true
				},
				goodsGpid:{
					required:true
				},
				agree:{
					required:true
				}
			},
			messages:{
				detail:{
					required:"问题描述不能为空"
				},
				type:{
					required:'请选择方式'
				},
				description:{
					required:'请填写问题描述'
				},
				goodsGpid:{
					required:'请选择商品'
				},
				agree:{
					required:'请勾选阅读并同意《售后政策》'
				}
			},	 	 				
			errorPlacement: function(error, element) {
				error.appendTo( element.siblings('.error'));
			}, 
			submitHandler:function(form) {
				form.submit();
			}									
		});	

		$('.menu').on('click',function(){
			$(this).find('.error').remove();
		})	
		$('.choose ul').on('click',function(){
			$(this).find('.error').remove();
		})	

	}else if(yktGlobal.pageName == 'home_priMsg'){

		//已读接口
		var readUrl = '/Index/PrivateMsg/msgRead';

		// 设置当前已读
		$('.J-read').on('click',function(){
			var _id = $(this).closest('li').attr('data-id');
			var _this = $(this);
			$.ajax({
				url: readUrl,
				type: 'post',
				data: {ids: _id}
			})
			.done(function() {
				_this.closest('li').find('.unread').remove();
			})
		})

 		//设置全部已读
		$('#J-all-read').on('click',function(){
			var _arrId = [];
			$('#J-container li').each(function(i,e){
				_arrId[i] = $(this).attr('data-id');
			})

			var _stringId = _arrId.join(',');

			$.ajax({
				url: readUrl,
				type: 'post',
				data: {ids: _stringId}
			})
			.done(function() {
				$('#J-container li').find('.unread').remove();
			})			
		})

	}else if(yktGlobal.pageName == 'home_order_goods_comment'){

		var _starNum;   //星数

		$('.J-open-comment').on('click',function(){

			var _html ='<div class="info">'
						 +'<div class="item clearfix">'
							 +'<div class="label"><i>*</i>评分：</div>'
							 +'<div class="frame">'
							 +'<div class="star J-star">'
							 +'<img data-i="1" src="/Public/themes/ykt/images/star_2.png"/>'
							 +'<img data-i="2" src="/Public/themes/ykt/images/star_2.png"/>'
							 +'<img data-i="3" src="/Public/themes/ykt/images/star_2.png"/>'
							 +'<img data-i="4" src="/Public/themes/ykt/images/star_2.png"/>'
							 +'<img data-i="5" src="/Public/themes/ykt/images/star_2.png"/>'
							 +'</div>'
							 +'</div>'
						 +'</div>'
						 +'<div class="item clearfix">'
							 +'<div class="label"><i>*</i>心得：</div>'
							 +'<div class="frame">'
							 +'<textarea class="J-textarea" placeholder="商品是否给力？快分享你的购买心得吧~" ></textarea>'
							 +'</div>'
						 +'</div>'
						 +'<div class="item clearfix">'
							 +'<div class="label"></div>'
							 +'<div class="frame">' 
							 +'<a class="J-submit-btn btn u-btn-primary" href="javascript:;">发表评价</a>'
							 +'</div>'
						 +'</div>'			
					 +'</div>';

			$('.J-info').empty();
			$(this).siblings('.J-info').append(_html);

			$('.J-submit-btn').on('click',function(){

				var _this = $(this);
				var _gid = $(this).closest('li').attr('data-gid');
                var _gpid = $(this).closest('li').attr('data-gpid');
                var _order_id = $(this).closest('li').attr('data-orderId');
				var _commentTxt = $('.J-textarea').val();
				var _hash = $('input[name="__hash__"]').val();

				if(_commentTxt.length<=60){
					$.ajax({
						url: '/Index/GoodsComment/doComment',
						type: 'post',
						dataType: 'json',
						data: {
							gid:_gid,
	                        gpid:_gpid,
	                        order_id:_order_id,
							star: _starNum,
							comment:_commentTxt,
							__hash__:_hash
						}
					})
					.done(function(data) {
						var _resultTxt;
						if(data.code){
							_resultTxt = '评论成功';
							// _this.closest('.J-info').siblings('.J-open-comment').hide();
							// $('.J-textarea').closest('.frame').append(_commentTxt).end().remove()
							// $('.J-submit-btn').closest('.item').remove();
						}else{
							_resultTxt = '评价失败';
						}
						_this.closest('li').hide();
						var d = dialog({
							title: '消息',
							content: _resultTxt,
							fixed: true,
							okValue: '确 定',

							ok: function () {}
						});
						d.width(320)
						d.showModal();	
					})	
				}else{
	                dialog({
	                    title: '提示',
	                    content: '评论不能超过60个字',          
	                    okValue: '确定',
	                    fixed: true, 
	                    ok: function () {} 
	                }).width(200).showModal();	
				}			
			})

			//评星
			function setStar(obj){
				var _dataI = obj.attr('data-i');
				$('.J-star img').prop('src','/Public/themes/ykt/images/star_2.png')
				$('.J-star img:lt('+_dataI+')').prop('src','/Public/themes/ykt/images/star_1.png')
			}

			$('.J-star img').on('mouseover',function(){
				var _this = $(this);
				setStar(_this);
			})

			$('.J-star img').on('mouseout',function(){
				$('.J-star img').prop('src','/Public/themes/ykt/images/star_2.png')
			});


			$('.J-star img').on('click',function(){
				var _this = $(this);
				_starNum = $(this).attr('data-i');
				setStar(_this);
				
				$('.J-star img').off('mouseover mouseout');
			})
		})
	}else if(yktGlobal.pageName == 'home_contribute'){
	}

	//分页
	pageControl('/Index/Home/'+_pageUrl,'#J-container');
});

