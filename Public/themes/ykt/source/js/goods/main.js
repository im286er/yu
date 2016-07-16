define(function(require, exports, module) {
    var $ = require('jquery');
    var ykt = require('common');
    //artDialog组件
    require('artDialog');
    require('../common/lib');
    require('../common/jzoom');

    $(function(){
        //浏览商品
        $.ajax({
            url: yktGlobal.controller+'/doView',
            type: 'GET',
            data: {gid: goodsId}
        })      

        //选中私有属性
        function chooseAttr(){

            var _hasActive;
            var _arr = [];           //私有属性数组
            var _arrIndex = 0;      //私有属性索引

            $('.J-item').each(function(i,e){
                let _attr = $(this).attr('data-attr');
                _hasActive = $(this).hasClass('active');

                if(_hasActive){
                    _arr[_arrIndex] = _attr
                    _arrIndex++;
                }
            }) 

            _arrJoin = _arr.join('-');
            _postAttr = _arr.join(','); 
        }       
        function BuyIsNum(val){
            if(isNaN(val)){
                alert('请输入数字');
                $('#J-buy-num').val('1')
                return false; 
            }else{
                return true;
            }                                            
        }
        // 改变input商品数量
        function numInputChange(){

            var _thisVal; //保存当前值

            $('.J-amount-input').on('focus',function(){
                _thisVal = $(this).val();
            })

            $('.J-amount-input').on('change',function(){

                var _this = $(this);
                var _changeVal = $(this).val();           

                if(isNaN(_changeVal)){ 
                    $(this).val(_thisVal);              
                }else if(_changeVal >= _stock){
                    dialog({
                        title: '提示',
                        content: '库存不足',          
                        okValue: '确定',
                        fixed: true, 
                        ok: function () {
                           
                        } 
                    }).width(200).showModal();
                    $(this).val(_thisVal); 
                }else{
                    _inputAmountVal = _changeVal;
                }
            })     
        }
        // 购物车
        function cartControl(){

            var _num = $('#J-buy-num');
            _inputAmountVal = _num.val();

            //增加数量
            $('#J-amount-increase').click(function(){

                goods_add();
            })

             //减少数量
            $('#J-amount-decrease').click(function(){
                goods_reduce();
            })    

            function goods_reduce(){

                //判断是否数字
                if(!BuyIsNum(_inputAmountVal)){
                    return false; 
                }

                _inputAmountVal = parseInt(_inputAmountVal);

                if(_inputAmountVal>1){
                    _inputAmountVal = _inputAmountVal-1;
                    _num.val(_inputAmountVal)
                }
            }

            function goods_add(){
     
                //判断是否数字
                if(!BuyIsNum(_inputAmountVal)){
                    return false; 
                }

                if(_inputAmountVal >= _stock){
                    dialog({
                        title: '提示',
                        content: '库存不足',          
                        okValue: '确定',
                        fixed: true, 
                        ok: function () {
                           
                        } 
                    }).width(200).showModal();
                    return false;
                }

                _inputAmountVal = parseInt(_inputAmountVal);
                _inputAmountVal = _inputAmountVal+1;
               
                _num.val(_inputAmountVal)
                
            }    
        }
        //倒计时
        function timer(intDiff){
            var _interval = setInterval(function(){
            var day=0,
                hour=0,
                minute=0, 
                second=0;//时间默认值        
            if(intDiff > 0){
                day = Math.floor(intDiff / (60 * 60 * 24));
                hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
                minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
                second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
            }else{
                clearInterval(_interval);
            }   
            if (minute <= 9) minute = '0' + minute;
            if (second <= 9) second = '0' + second;
            $('#J-day-show').html(day+"天");
            $('#J-hour-show').html('<s id="h"></s>'+hour+'时');
            $('#J-minute-show').html('<s></s>'+minute+'分');
            $('#J-second-show').html('<s></s>'+second+'秒');
            intDiff--;
            }, 1000);
        } 
        function onsaleTime(){
            let _unixTime = parseInt($('#J-hidden-onsale').val());
            let _time = new Date(_unixTime * 1000);
            let _nowTime = new Date();
            let _seconds = (_time.getTime()-_nowTime.getTime())/1000;
            timer(_seconds);
        }


        var _postAttr;       //购物车接口需要的值，两个属性用","隔开
        var _arrJoin;        //url需要的值，用"-"隔开    
        var _stock = $('input[name="stock"]').val();  //库存  
        var _inputAmountVal                     //购买数量

        chooseAttr();     
        numInputChange();
        cartControl();  
        onsaleTime(); 

        //切换
        $.yktTab("#J-goods-tab .tabBar span","#J-goods-tab .tabCon","current","click","0");     

        //产品放大镜
        $(".jqzoom").jqueryzoom({
            xzoom:300,  // 放大图的宽
            yzoom:300,  // 放大图的高
            offset:100,   // 放大图距离原图的位置
            position:'right'  // 放大图在原图的右边(默认为right)
        });

        // 产品列表
        $("#J-thumb").jdMarquee({
            deriction:"left",
            width:300,
            height:52,
            step:2,
            speed:4,
            delay:10,
            control:true,
            _front:"#spec-right",
            _back:"#spec-left"
        });

        $("#J-thumb img").on("mouseover",function(){
            var _src=$(this).prop("src");
            
            $("#J-booth img").prop('src',_src);
            $("#J-booth img").attr('jqimg',_src);
            // $("#J-booth img").eq(0).prop({ 
            //     src:src.replace("\/n5\/","\/n1\/"),
            //     jqimg:src.replace("\/n5\/","\/n0\/")
            // });
        });

        //加入购物车
        $('#J-basket-btn').click(function(event){
            
            var _goodsNum = $("#J-buy-num").val();
            //初始化参数 
            $('input[name="quick"]').val(0);

            //判断是否数字
            if(!BuyIsNum(_goodsNum)){
                return false; 
            }   

            $(this).closest('form').submit();
        }) 

        //立即购买
        $('#J-quick-btn').on('click',function(){
            var _this = $(this);
            var _goodsNum = $("#J-buy-num").val();
            //初始化参数 
            $('input[name="quick"]').val(1);
            //判断是否数字
            if(!BuyIsNum(_goodsNum)){
                return false; 
            }

            //立即购买检测登录回调
            function isLoginCallback(){
                $('#J-quick-btn').closest('form').attr('action','/Index/Order/genernate.html').submit();
            }
            //检测登录状态
            $.ajax({
                url: '/Index/Login/isLogin',
                async:false, 
                dataType: 'json'
            })
            .done(function(data) {
                if(!data.status){ 
                    ykt.LoginDialog('/Index/Order/genernate.html',isLoginCallback);                  
                }else{
                    _this.closest('form').attr('action','/Index/Order/genernate.html').submit();     
                }
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
                    data: {gid: goodsId,cat_id:catId,type:'+'}
                })            
                .done(function(data) {
                    if(data.code == 1){
                        _this.addClass('active').attr('data-switch','false').find('p').html('收藏成功');
                    }else if(data.code == 2){
                        _this.addClass('active').attr('data-switch','false').find('p').html('已收藏过');
                    }
                })
            }else{
                $.ajax({
                    url: yktGlobal.controller+'/doCollect',
                    type: 'POST',
                    dataType: 'json',
                    data: {gid: goodsId,type:'-'}
                })            
                .done(function(data) {
                    if(data.code){
                        _this.removeClass('active').attr('data-switch','true').find('p').html('收藏');
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
                    url: yktGlobal.controller+'/doLike',
                    type: 'POST',
                    dataType: 'json',
                    data: {gid: goodsId}
                })            
                $(this).addClass('active').attr('data-switch','false').find('p').html('已点赞');
            }
        })

        // -------------------------------------------------------------------------
        //私有属性切换
        $('.J-item').on('click',function(){
            var _this = $(this);
            var _disabled = $(this).hasClass('disabled');

            if(!_disabled){
                _this.siblings().removeClass('active');
                _this.addClass('active');

                chooseAttr();
         
                var _url = window.location.href;
                var _regular = /\/attr\//;  

                //跳转
                if(_regular.test(_url)){
                    let _arr = _url.split('/attr/');
                    _url = _arr[0]+'/attr/'+_arrJoin+'.html';
                }else{
                    let _arr = _url.split('.html');
                    _url = _arr[0]+'/attr/'+_arrJoin+'.html';
                }

                window.location = _url;
            }     

            // 异步方法
            // $.ajax({
            //     url: yktGlobal.controller+'/setChange',
            //     type: 'POST',
            //     dataType: 'JSON',
            //     data: {goods_id: goodsId,attr:_arrJoin}
            // })
            // .done(function(data) {
            //     $('#J-code').html(data.code)
            //     $('#J-stock').html(data.stock)
            //     $('#J-price').html(data.price)
            //     $('#J-weight').html(data.weight)
            // })
        })

        //购物须知
        var buyReadFlag = true;
        $('#J-buy-read-hd').on('click',function(){
            if(buyReadFlag){
                $('#J-buy-read-bd').load('/Index/Page/index/sign/gwxz.html');
                buyReadFlag = false;
            }
        })

        //评论
        var goodsComment = (function() {
            var userCommentFlag = true;
            var commentNum = $('#J-comment-num').text();
            var Commentparams = {
                gid:goodsId,
                p:1,
                comment_num:commentNum
            }

            return {
                pageControl:function(obj,type,params){
                    var _this = this
                    $('#J-goods-tab').delegate(obj,'click',function(){

                        //1.中间数字，2获取 data-page页数
                        if(type == 1){
                            params.p = parseInt($(this).text());
                        }else{
                            params.p = $(obj).attr('data-page');
                        }
                        _this.ajaxComment(params);
                    })  
                },
                ajaxComment:function(params){
                    var _this  = this;
                    ykt.faLoading('J-fa-loading','#J-user-comment-bd')
                    $.ajax({
                        url: '/Index/GoodsComment/show.html',
                        type: 'post',
                        dataType: 'html',
                        data: params
                    })
                    .done(function(data) {
                        setTimeout(function(){
                            if(data){
                                $('#J-user-comment-bd').empty().html(data);
                            }else{
                                $('#J-user-comment-bd').empty().html('<div class="m-no-record">没有相关记录</div>');
                            }
                        },500)
                        // userCommentFlag = false;
                    })  
                },
                click: function() {
                    var _this  = this;
                    $('#J-user-comment-hd').on('click',function(){
                        if(userCommentFlag){
                            Commentparams.p = 1;
                            _this.ajaxComment(Commentparams);
                        }
                    });
                    _this.pageControl('.J-page-item',1,Commentparams);
                    // 上下一页
                    _this.pageControl('.paginator-item-next',2,Commentparams);
                    _this.pageControl('.paginator-item-prev',2,Commentparams);

                    _this.pageControl('.J-comment-type',2,Commentparams);
                }
            };
        })();

        goodsComment.click()

    });
});