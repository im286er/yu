"use strict";define(function(require,exports,module){var $=require("jquery"),t=require("common");$(function(){require("ajaxupload"),require("validate"),"contribute"==yktGlobal.pageName||"anli"==yktGlobal.pageName?!function(){var e=function(t,e,a,i,n,l,s,o){$(i).val(t),"titleImg"!=l&&"background"!=l||($(n).remove(),$(".upload-cover-pic").append('<div class="ui-dialog-loading"></div>'),setTimeout(function(){var a='<img id="J-titleImg-container" src="'+yktGlobal.upload+t+'">';$(".upload-cover-pic").append(a),$(e).siblings(".ui-dialog-loading").remove()},2e3))},a=function(){$("#J-upload-form").validate({rules:{title:{required:!0},summary:{required:!0},agreement:{required:!0},title_img:{required:!0}},messages:{title:{required:"标题不能为空"},summary:{required:"简介不能为空"},agreement:{required:"请勾选原创声明（暂不接受非原创投稿）"}},errorPlacement:function(t,e){t.appendTo(e.siblings(".msg-span"))},submitHandler:function(e){var a=$("#J-titleImg-inputVal").val();return a?void dialog({title:"消息",content:"确认填写信息",okValue:"确定",cancelValue:"取消",cancel:function(){},ok:function(){e.submit()}}).width(400).showModal():(dialog({title:"消息",content:"标题图不能为空",okValue:"确定",fixed:!0,ok:function(){t.animateTo(0,0)}}).width(400).show(),!1)}})};t.AjaxUploadPic("#J-titleImg-button","#J-titleImg-status","#J-titleImg-inputVal","#J-titleImg-container","titleImg",640,360,e),$('input[name="release"]').on("click",function(){a()})}():"detail"==yktGlobal.pageName&&($.ajax({url:yktGlobal.controller+"/doView",type:"GET",data:{aid:articleId}}),$.ajax({url:"/Index/ArticleComment/getComment",type:"POST",dataType:"html",data:{aid:articleId}}).done(function(e){function a(t,e){$("#J-comment-wrap").delegate(t,"click",function(){var t,a=$(this).attr("data-order");t=1==e?$(this).text():$(this).attr("data-page");var i=$("#J-article-id").attr("data-articleId");$.ajax({url:pageUrl,type:"post",data:{aid:i,currentPage:t,order:a},dataType:"html",success:function(t){$("#J-comment-area").empty(),$("#J-comment-area").append(t)}})})}$(e).fadeIn().prependTo($("#J-comment-area"));var i=$("#J-header-avatar").prop("src");$("#J-comment-avatar").prop("src",i);var n,l=!1;$(".reply-list").each(function(){var t=$(this).find(".inside-list").length;t&&$(this).find(".J-comment-reply-bd").show()}),$("#J-comment-wrap").delegate(".J-expression-btn","click",function(){var t=$(this).hasClass("open");t?($(this).removeClass("open"),$(this).siblings(".J-expression-box").removeClass("open")):($(this).addClass("open"),$(this).siblings(".J-expression-box").addClass("open"))}),$("#J-comment-wrap").delegate(".J-expression-box a","click",function(){var t,e=$(this).closest(".J-expression-wrap"),a=e.siblings(".comment-textarea"),i=e.find(".J-expression-btn"),n=$(this).text();"true"==a.prop("contenteditable")?(t=a.text(),a.text(t+n)):(t=a.val(),a.val(t+n)),i.removeClass("open"),$(this).closest(".J-expression-box").removeClass("open")}),$("#J-comment-btn").on("click",function(){if(!t.isLogin())return t.LoginDialog(),!1;var e=($(this),$.trim($(".comment-textarea").val())),a=$("#J-article-id").attr("data-articleId"),i=$("#J-header-avatar").prop("src");e?$.ajax({url:commentUrl,data:{aid:a,content:e},type:"post",dataType:"json",success:function(t){var a='<li class="reply-list clearfix" data-topid="'+t.id+'"><div class="hd"><div class="avatar"><a href="#"><img src="'+i+'"/></a></div><div class="level">作者</div></div><div class="bd J-comment-reply-content"><div class="name">'+yktGlobal.userName+'</div><span class="time">'+t.add_time+'</span><div class="txt">'+e+'</div><div class="comment-tool"><ul class="frame"><li class="comment-tool-list J-comment-like" data-switch="true">顶[<span class="num">0</span>]</li><li class="comment-tool-list J-comment-reply-hd">回复</li></ul></div><div class="inside J-comment-reply-bd clearfix"><ul class="J-second-reply-frame"></ul><div class="reply-comment fr J-reply-comment-hd">我也要回复</div><div class="J-reply-comment-bd clearfix"><div class="comment-textarea" contenteditable="true"><span class="J-reply-obj"></span></div><div class="u-btn u-btn-primary u-btn-size-1 radius fr J-second-reply">回复</div></div></div></div></li>';$("#J-reply-frame").html()||$("#J-comment-area").append('<ul id="J-reply-frame" class="reply" style="display: block;"></ul>'),$(a).fadeIn().prependTo($("#J-reply-frame")),$("#J-comment-textarea").val("")}}):dialog({title:"消息",content:"您还没有评论，先写点什么吧",okValue:"确定",fixed:!0,ok:function(){}}).width(400).showModal()}),$("#J-comment-wrap").delegate(".J-second-reply","click",function(){if(!t.isLogin())return t.LoginDialog(),!1;var e=$(this),a=$(this).closest(".J-comment-reply-content").find(".inside-list").length,i=$.trim(e.siblings(".comment-textarea").text()),s=$("#J-article-id").attr("data-articleId"),o=$(this).parents(".reply-list").attr("data-topId"),r=$("#J-header-avatar").prop("src");if(a&&l)var d=n,i=$.trim(e.siblings(".comment-textarea").text()),c={aid:s,content:i,top_id:o,to_uid:d};else c={aid:s,content:i,top_id:o};i?$.ajax({url:commentUrl,type:"POST",data:c,dataType:"json",success:function(t){e.parents(".J-comment-reply-bd").find(".J-second-reply-frame").append('<li class="inside-list clearfix animate"><div class="inside-l"><div class="inside-avatar"><a href="#"><img src="'+r+'"></a></div></div><div class="bd J-comment-reply-inside-content"><div class="name">'+yktGlobal.userName+'</div><span class="time">'+t.add_time+'</span><div class="txt">'+i+'</div><div class="comment-tool"><ul class="frame"><li class="comment-tool-list J-comment-reply-inside-hd">回复</li></ul></div></div></li>'),e.parents(".J-comment-reply-bd").find(".comment-textarea").empty(),e.parents(".J-reply-comment-bd").hide()}}):dialog({title:"消息",content:"您还没有评论，先写点什么吧",okValue:"确定",fixed:!0,ok:function(){}}).width(400).showModal()}),$("#J-comment-wrap").delegate(".J-comment-reply-inside-hd","click",function(){if(!t.isLogin())return t.LoginDialog(),!1;l=!0;var e=$(this).parents(".J-comment-reply-inside-content").find(".name").text();$(this).parents(".J-comment-reply-bd").children(".J-reply-comment-bd").show(),$(this).parents(".J-comment-reply-bd").find(".comment-textarea").empty(),$(this).parents(".J-comment-reply-bd").find(".comment-textarea").append('<span class="J-reply-obj">回复@'+e+"：</span>");var a=$(this);n=a.parents(".inside-list").find(".inside-avatar").attr("data-userid")}),$("#J-comment-wrap").delegate(".J-comment-reply-hd","click",function(){if(!t.isLogin())return t.LoginDialog(),!1;$(this).closest(".reply-list").siblings(".reply-list").find(".J-comment-reply-bd").hide();var e=$(this).parents(".J-comment-reply-content").find(".J-comment-reply-bd"),a=e.is(":visible");a?e.hide():e.show();var i=$(this).closest(".J-comment-reply-content").find(".inside-list").length;i||$(this).parents(".J-comment-reply-content").find(".J-reply-comment-hd").hide().end().find(".J-reply-comment-bd").show()}),$("#J-comment-wrap").delegate(".J-reply-comment-hd","click",function(){return t.isLogin()?(l=!1,void $(this).nextAll(".J-reply-comment-bd").show().find(".J-reply-obj").remove()):(t.LoginDialog(),!1)}),$("#J-comment-wrap").delegate(".J-comment-like","click",function(){var t=$(this),e=t.closest(".reply-list").attr("data-topid"),a=t.find(".num"),i=a.text(),n=t.attr("data-switch");"true"==n&&(i++,$.ajax({url:likeUrl,data:{id:e},type:"post",success:function(e){a.text(i),t.css("color","#00aeb6"),t.attr("data-switch","false")}}))}),a(".J-paginator",1),a(".paginator-item-next",2),a(".paginator-item-prev",2),a(".J-comment-type",2),$("#J-comment-wrap").delegate(".J-inside-paginator","click",function(){var t=$(this),e=$(this).text(),a=$(this).parents(".reply-list").attr("data-topId"),i=$(this).parents(".J-second-reply-page").find(".J-reply-num").text();$.ajax({url:insidePageUrl,type:"post",data:{top_id:a,reply_num:i,p:e},dataType:"html",success:function(e){t.closest(".J-second-reply-page").empty().append(e)}})})}),$("#J-collect").click(function(){if(!t.isLogin())return t.LoginDialog(),!1;var e=$(this).attr("data-switch"),a=$(this);"true"==e&&$.ajax({url:yktGlobal.controller+"/doCollect",type:"POST",dataType:"json",data:{aid:articleId,cat_id:catId,type:"+"}}).done(function(t){1==t.code?a.addClass("active").attr("data-switch","false").find("p").html("收藏成功"):2==t.code&&a.addClass("active").attr("data-switch","false").find("p").html("已收藏过")})}),$("#J-like").click(function(){if(!t.isLogin())return t.LoginDialog(),!1;var e=$(this).attr("data-switch");"true"==e&&($.ajax({url:yktGlobal.controller+"/doView",type:"POST",dataType:"json",data:{aid:articleId}}),$(this).addClass("active").attr("data-switch","false").find("p").html("已点赞"))}),$("#J-open-comment").on("click",function(){var t=$(this).css("display");"block"==t&&($(this).hide(),$("#J-comment-wrap").fadeIn())}),$("#J-open-comment").hide(),$("#J-comment-wrap").fadeIn())})});