"use strict";define(function(require,exports,module){var $=require("jquery"),e=require("common");require("flexslider"),$(function(){$("#J-slidedown").flexslider({animation:"slide",slideshowSpeed:5e3}),$("#J-recommend a").on("click",function(){var n=$(this).attr("data-id");$(this).siblings().removeClass("active").end().addClass("active"),e.faLoading("J-fa-loading","#J-recommend-ul"),$.ajax({url:"/Index/Index/recommend",type:"POST",data:{cat_id:n}}).done(function(e){setTimeout(function(){$("#J-recommend-ul").empty().append(e),$("#J-html-loading").remove()},500)})})})});