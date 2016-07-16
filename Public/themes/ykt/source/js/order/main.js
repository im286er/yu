define(function(require, exports, module) {
	var $ = require('jquery');
	var ykt = require('common');
    require('artDialog');
    require('validate');

    /*新增、编辑收货地址
    * url     请求地址
    * type    类型：add,update
    * that    当前对象
    * @return {[type]}
    */
    function consigneeDialog(url,type,that){
        //loading
        ykt.appendLoading('J-html-loading');
        var _url = '';
        var _title;
        if(type == 'add'){
            _url = addAddressUrl;
            _title = '新增收货人信息';
        }else if(type == 'update'){
            _url = editAddressUrl;
            _title = '编辑收货人信息';    
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
                    title: _title,
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
                            var _province = $("#J-ajax-province").children('option:selected').val();  //省
                            var _city = $("#J-ajax-city").children('option:selected').val();           //城市
                            var _district = $("#J-ajax-area").children('option:selected').val();           //区 
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
                             // alert(JSON.stringify(data)); 
                                var _amount = $('.J-addr-list').length + 1;
                                var _addId = data['id'];

                                //新增
                                if(type == 'add'){
                                    //切换选中状态
                                    $('.consignee-item').removeClass('selected');
                                    if(_amount == 1){
                                        $('#J-consignee').empty();
                                    }
                                    $('.J-addr-list').removeClass('selected');
                                    $('#J-consignee').append('<li class="J-addr-list selected" data-id="'+data['id']+'"><div class="consignee-item"><span data-consignee="'+_provinceTxt+' '+_realName+'">'+_realName+' '+_provinceTxt+'</span> <i></i></div><div class="addr-detail"><span class="addr-name">'+_realName+'</span> <span class="addr-info">'+_provinceTxt+' '+_cityTxt+' '+_districtTxt+' '+_street+'</span> <span class="addr-tel">'+_mobile+'</span></div><div class="op-btns"><a class="J-setdefault" href="javascript:;" data-id='+data['id']+'>设为默认地址</a><a class="J-consignee-edit" href="javascript:;" data-id='+data['id']+'>编辑</a> <a class="J-consignee-delete" href="javascript:;" data-id='+data['id']+'>删除</a></div></li>')

                                    // 展开收货地址
                                    // $('#J-consignee').height('auto');
                                    // $('#J-addr-more').addClass('close');
                                    $('input[name="address_id"]').val(data['id']);

                                    if(_amount == 1){
                                        $('.J-addr-list').addClass('selected setDefault');
                                        $('.consignee-item span').text('默认地址');
                                        //设置地址ID
                                        expressage();                                               
                                    }    

                                    //设置选中ID
                                    $('input[name="address_id"]').val(_addId);              
                                    //运费计算
                                    expressage();  
                                //编辑
                                }else if(type == 'update'){
                                    that.closest('.J-addr-list').find('.consignee-item span').html(_provinceTxt+' '+_realName);
                                    that.closest('.J-addr-list').find('.addr-name').html(_realName);
                                    that.closest('.J-addr-list').find('.addr-info').html(_provinceTxt+' '+_cityTxt+' '+_districtTxt+' '+_street);
                                    that.closest('.J-addr-list').find('.addr-tel').html(_mobile);
                                        //设置地址ID
                                        expressage();                                     
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
    //运费计算
    function expressage(){
        var _addressId = $('input[name="address_id"]').val();

        // alert(_addressId)
        // var _weight = $('input[name="weight"]').val();

        if(_addressId){
            $.ajax({
                url: '/Index/Order/getExpressFee',
                type:'POST',
                data: {address_id: _addressId,tcId:_tcId}
            })
            .done(function(data) {
                if(data.code){
                    $('#J-expressage').text(data.express_fee);
                    $('#J-total-price').text(data.order_price);   
                }else{
                    var _goodsPrice = parseFloat($('#J-goods-price').text());
                    //商品+运费
                    var _totalPrice = (parseFloat(data.cost) + _goodsPrice).toFixed(1);

                    $('#J-expressage').text(data.cost);
                    $('#J-total-price').text(_totalPrice);
                }
            })
        }
    }

    if(yktGlobal.pageName == 'info'){
        var _tcId = $('input[name="tcId"]').val(); //预订单号
     
        //展开全部
        // $('#J-addr-more').on('click',function(){

        //     var _content = $('#J-consignee');
        //     //当前值
        //     var _heightVal = _content.height();

        //     if(_heightVal == '40'){
        //         _content.height('auto');
        //         $(this).addClass('close');
        //     }else{ 
        //         _content.height('40');
        //         $(this).removeClass('close')
        //     }
        // });

        //选择地址
        $("#J-address-wrap").delegate(".consignee-item","click",function(){
            var _id = $(this).closest('.J-addr-list').attr('data-id');
            //设置选中ID
            $('input[name="address_id"]').val(_id);              
            $('.J-addr-list').removeClass('selected');
            //当前父级添加选中样式
            $(this).closest('.J-addr-list').addClass('selected');
            //运费计算
            expressage();             
        });

        //设置默认
        $("#J-address-wrap").delegate(".J-setdefault","click",function(){
            var _this = $(this);
            var _id = $(this).attr('data-id');

            $.ajax({
                type: 'POST',
                url: '/Index/Address/setDefault',
                data: {id: _id},
            })
            .done(function() {  
                //对应修改默认地址
                $('.J-addr-list').each(function(index, el) {
                    var _isDefault = $(this).hasClass('setDefault');
                    var _obj = $(this).find('.consignee-item span');
                    //判断是否默认,
                    if(_isDefault){
                        var _consignee = _obj.attr('data-consignee');
                        _obj.html(_consignee);
                        $(this).removeClass('setDefault');
                    }
                });
                _this.closest('.J-addr-list').find('.consignee-item span').html('默认地址');
                _this.closest('.J-addr-list').addClass('setDefault');            
            })  
        });

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

        //编辑收货地址
        $("#J-address-wrap").delegate(".J-consignee-edit","click",function(){
            var _this = $(this);
            var _id = $(this).closest('li').attr('data-id');

            //重置地址
            editAddressUrl = '/Index/Address/edit/id/';
            editAddressUrl = editAddressUrl+_id
            consigneeDialog('/Index/Address/update','update',_this);
        });

         //删除收货地址
        $("#J-address-wrap").delegate(".J-consignee-delete","click",function(){
            var _this = $(this);
            var _id = $(this).attr('data-id');

            $.ajax({
                url: '/Index/Address/del',
                data: {id: _id},
            })
            .done(function(data) {

                var _hasSelected = _this.closest('.J-addr-list').hasClass('selected');
                var _goodsPrice = $('#J-goods-price').text();

                //如果删除了选中地址，清空ID
                if(_hasSelected){
                    $.ajax({
                        url: '/Index/order/clearExpressFee',
                        data: {tcId: _tcId},
                    })
                    .done(function(data) {
                        
                        if(data.code){
                            $('#J-expressage').text('');
                            $('#J-total-price').text(data.order_price);
                            $('input[name="address_id"]').val(''); 
                        }
                    })

                }
                var _amount = $('.J-addr-list').length;
                if(_amount <= 1){
                    $('#J-consignee').append('<h3 class="fl" style="color: red;">未有收货人信息!</h3>')                    
                }

                _this.closest('.J-addr-list').remove();    


               
            })        
        });


        //赋值->运费
        expressage();
        
        //提交
        $('#J-submit-btn').on('click',function(){
            var _addressId = $('input[name="address_id"]').val();
            var __hash__ = $('input[name="__hash__"]').val();
            var error_msg = '亲爱的会员~~,订单信息失效或商品库存限制,请重新选取商品下单!';

            if(!_addressId){
                dialog({
                    title:'消息',
                    content:'请选择收货人!',
                    okValue:'确定',   
                    fixed:true,
                    ok:function(){}                 
                }).width(400).showModal();              
                return false;
            }
            $(this).off('click').removeClass('u-btn-primary').addClass('u-disabled').text('提交中...');
            //$(this).closest('form').submit();
            $.ajax({
                url: '/Index/Order/submit',
                type: 'POST',
                dataType: 'json',
                data: {tcId: _tcId,__hash__:__hash__,address_id:_addressId},
            })
            .done(function(data) {
                if(data.code){
                    //移除提交事件,切换样式,更改文字
                    window.location.href = data.jumpUrl;
                    return false;
                }else{
                    if(data.msg) error_msg = data.msg;
                    dialog({
                        title:'消息',
                        content:error_msg,
                        okValue:'确定',   
                        fixed:true,
                        cancel:false,
                        ok:function(){ 
                            window.location.href = '/Index/Sale/index.html'; 
                        }                 
                    }).width(500).showModal(); 
                }
            })
        })
    }else if(yktGlobal.pageName == 'confirm'){
        $('input[type="button"]').on('click',function(){
            $(this).off('click').removeClass('u-btn-primary').addClass('u-disabled').text('提交中...');
            dialog({
                title:'正在支付...',
                content:'<a class="dialog-btn" href="/Index/Home/index.html">支付已完成!</a><a class="dialog-btn" href="/Index/Home/index.html">支付出现问题?</a>',
                okValue:'确定',   
                fixed:true,
                cancel:false,
                ok:false,           
            }).width(300).showModal(); 
            $(this).closest('form').submit();
        })
    }
});