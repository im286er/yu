// 只能选一个属性
function attrJustOne(){
    $('.J-just-one').find('input').on('click',function(){
        $(this).closest('.J-just-one').find('input').prop('checked',false);
        $(this).prop('checked',true);
    })
}    
// 私有属性初始化不能修改
function modifySwitch(type){
   if(type == 'cannot'){
       $('#J-clear-private-btn').hide();
       // $('#J-group-attr').attr('disabled','true').addClass('m-disabled');
       $('#J-private-frame').find('input:not(.J-input-disabled)').attr('disabled','true').addClass('m-disabled');
   }else{
       $('#J-clear-private-btn').fadeIn();
       // $('#J-group-attr').removeAttr('disabled').removeClass('m-disabled');
       $('#J-private-frame').find('input:not(.J-input-disabled)').removeAttr('disabled').removeClass('m-disabled');
   }
}
//修改套装搭配
function modifyGroupAttr(type){
    if(type == 'cannot'){
        $('#J-group-attr').attr('disabled','true').addClass('m-disabled');
    }else{
        $('#J-group-attr').removeAttr('disabled').removeClass('m-disabled');
    }
}
//私有属性管理
if(adminGlobal.pageName == 'privateattr'){
    $('#J-property-increase').on('click',function(){
        $('#J-input-list').append('<input type="text" name="attr[]" class="required property-input" />');
    })
    $('#J-property-decrease').on('click',function(){
        var propertyNum = $('#J-input-list input').length;
        propertyNum = propertyNum - 1;
        if(propertyNum){
            $('#J-input-list input:last').remove();
        } else {
            alert('第一个不能删除')
        }
    })    
} else if(adminGlobal.pageName == 'goods_add'){

    var _groudAttr = $('#J-group-attr');        //套装配搭input
    var _setType = $('input[name="multi"]');   //0-无搭配 1-1个私有属性 2-2个私有属性
    var _topAttr = $('input[name="top_attr"]');     

    //添加限制
    function attrRule(num,multi){
        if(multi == 1){
            //单属性不能大于5个
            if(num > 5){
                dialog({
                    title:'消息',
                    content:'属性值不能大于5个',
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
        $.ajax({
            url: '/Admin/Goods/attr_form',
            type: 'post',
            data: _data+'&multi='+_multi
        })
        .done(function(data) {
            $('#J-result-wrap').empty().append(data);

            //绑定图片上传
            var _resultNum = $('.result-list').length;

            for(var i = 1; i <= _resultNum; i++){
            AjaxUploadPic('#J-suit-button-'+i,'#J-suit-status-'+i,'#J-suit-inputVal-'+i,'#J-suit-container-'+i,'suit',200,200);
            }   
        })
    }

    //无套装 
    $('#J-no-attr').on('click',function(){
        var no_attr_html ='<div class="m-attr-data clearfix">'
                +'  <div class="bottom">'
                +'      <input class="u-input-text" type="text" name="stock" placeholder="请输入数量" data-rule-required="true" data-msg-required="数量不能为空" data-rule-digits="true" data-msg-digits="数量只能输入整数">'
                +'      <span class="unit">件</span>'
                +'      <input class="u-input-text" type="text" name="price" placeholder="请输入售价" data-rule-required="true" data-msg-required="售价不能为空" data-rule-number="true" data-msg-number="请输入合法的售价">'
                +'      <span class="unit">元</span>'
                +'      <input class="u-input-text textInput valid" type="text" name="goods_code" placeholder="商品编码" value="">'
                +'      <span class="unit"></span>'
                +'      <input class="u-input-text textInput valid" type="text" name="area_code" placeholder="区域码" value="">'
                +'      <span class="unit"></span>'
                +'      <input class="u-input-text textInput valid" type="text" name="abbr" placeholder="商品缩写" value="">'
                +'      <span class="unit"></span>'
                +'      <input class="u-input-text textInput valid" type="text" name="weight" placeholder="重量" value="">'
                +'      <span class="msg-span"></span>'
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
       var _html='<div class="multi-attr-content"><ul id="J-ul-attr"class="top"><li class="J-attr-row clearfix" data-row="1"><div class="J-attr-title attr-title"><input class="J-input-edit input-edit"type="text"name="attr_des[1][title]"placeholder="输入属性名"/></div><div class="J-attr-val-frame attr-val"><div class="J-attr-val-list attr-val-list"><input class="J-input-edit input-edit"type="text"name="attr_des[1][val][1]"placeholder="输入属性值"/><i class="m-close close J-delete-attr-val">x</i></div><a class="add-btn J-add-attr-val">+添加</a></div><i class="close-item m-close J-delete-attr-row">x</i></li></ul><a id="J-add-attr-btn"class="add-attr-btn"href="javascript:;">添加规格项目</a><div id="J-result-wrap"></div></div>';  
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
        var _row = _parentsLi.attr('data-row');
        var _html  ='<div class="J-attr-val-list attr-val-list">'
                   +'   <input class="J-input-edit input-edit" type="text" name="attr_des['+_row+'][val]['+_num+']" placeholder="输入属性值"/>'
                   +'       <i class="m-close close J-delete-attr-val">x</i>'
                   +'</div>'
        if(attrRule(_num,_multi)){
            $(this).before(_html);
        };
    })
    //删除值 
    $("#J-attr-frame").delegate(".J-delete-attr-val","click",function(){
        //获取父级li
        var _parentsLi = $(this).closest('li');
        //计算当前值数量
        var _attrListNum = _parentsLi.find('.J-attr-val-list').length;
        //获取行数
        var _row = _parentsLi.attr('data-row');

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
                   +'   <div class="J-attr-title attr-title">'
                   +'           <input class="J-input-edit input-edit" type="text" name="attr_des['+_row+'][title]" placeholder="输入属性名"/>'
                   +'       </div>'
                   +'       <div class="J-attr-val-frame attr-val">'
                   +'           <div class="J-attr-val-list attr-val-list">'
                   +'               <input class="J-input-edit input-edit" type="text" name="attr_des['+_row+'][val][1]" placeholder="输入属性值"/>'
                   +'               <i class="m-close close J-delete-attr-val">x</i>'
                   +'           </div>'
                   +'           <a class="add-btn J-add-attr-val">+添加</a>'
                   +'       </div>'
                   +'       <i class="close-item m-close J-delete-attr-row">x</i>'
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
        attrAjax();
    })

    // 商品图片
    AjaxUploadPic('#J-cover-button','#J-cover-status','#J-cover-inputVal','#J-cover-container','cover',200,200);
 
} else if(adminGlobal.pageName == 'expressage'){
    //默认运费
    $('#J-expressage-btn').on('click',function(){

        var _weight = $('input[name="init_weight"]').val();
        var _cost = $('input[name="init_cost"]').val();
        var _extraWeight = $('input[name="extra_weight"]').val();
        var _extraCost = $('input[name="extra_cost"]').val();

        $.ajax({
            type:'post',
            data:{
                init_weight:_weight,
                init_cost:_cost,
                extra_weight:_extraWeight,
                extra_cost:_extraCost
            },
            url:_controller+'/init',
            success:function(data){
                alert('设置成功')
            }
        })
    })
} else if(adminGlobal.pageName == 'expressage_add' || adminGlobal.pageName == 'expressage_edit'){
    var _province = $("#J-ajax-province");
    var _city = $("#J-ajax-city");
    var _option_html;
    var _currentProvinceVal = _province.children('option:selected').val();

    function ajaxCity(val){
        
        $.ajax({
            type:'post',
            dataType:'json',
            data:{pid: val}, 
            url:cityUrl+'/city',
            success:function(data){
                //清空
                _city.empty();
                
                $('<option value=""></option>').appendTo(_city);
                $.each(data, function(idx, obj) {
                    _option_html='<option value='+ obj.id +'>'+ obj.areaname +'</option>';
                    $(_option_html).appendTo(_city);
                })
            }
        })

    }          

    // 联动初始化
    //ajaxCity(_currentProvinceVal)

   _province.change(function(){
        var _province = $(this).children('option:selected').val();
        ajaxCity(_province)
    });         
} else if(adminGlobal.pageName == 'promote_add' || adminGlobal.pageName == 'promote_edit'){
   $("#J-fid").change(function(){
        var _fid = $(this).children('option:selected').val();
        $.ajax({
            url: _promoteUrl, 
            type: 'POST',
            dataType: 'json',
            data: {fid: _fid},
        })
        .done(function(data) {
            var html = '';
            $('#J-select-sign').empty();
            if(data){
                $(data).each(function(index, el) {
                    html += '<option value='+el.id+'>'+el.block_name+'</option>';
                }); 
            }else{
                html = '<option value=0>暂无</option>';
            }
            $('#J-select-sign').append(html); 
        })
        
   });  
}
