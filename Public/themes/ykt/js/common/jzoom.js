"use strict";define(function(require,exports,module){function i(i){this.x=i.pageX,this.y=i.pageY}!function($){$.fn.jqueryzoom=function(o){var t={xzoom:200,yzoom:200,offset:10,position:"right",lens:1,preload:1};o&&$.extend(t,o);var e="";$(this).hover(function(){var o=$(this).offset().left,s=$(this).offset().top,d=$(this).children("img").get(0).offsetWidth,m=$(this).children("img").get(0).offsetHeight;e=$(this).children("img").attr("alt");var v,h,n,r,f,l,g=$(this).children("img").attr("jqimg");$(this).children("img").attr("alt",""),0==$("div.zoomdiv").get().length&&($(this).after("<div class='zoomdiv'><img class='bigimg' src='"+g+"'/></div>"),$(this).append("<div class='jqZoomPup'>&nbsp;</div>")),"right"==t.position?v=o+d+t.offset+t.xzoom>screen.width?o-t.offset-t.xzoom:o+d+t.offset:(v=o-t.xzoom-t.offset,0>v&&(v=o+d+t.offset)),$("div.zoomdiv").css({top:s,left:v}),$("div.zoomdiv").css("top","41px"),$("div.zoomdiv").css("left","410px"),$("div.zoomdiv").width(t.xzoom),$("div.zoomdiv").height(t.yzoom),$("div.zoomdiv").show(),t.lens||$(this).css("cursor","crosshair"),$(document.body).mousemove(function(e){h=new i(e);var v=$(".bigimg").get(0).offsetWidth,g=$(".bigimg").get(0).offsetHeight,c="x",a="y";if(isNaN(a)|isNaN(c)){var a=v/d,c=g/m;$("div.jqZoomPup").width(t.xzoom/(1*a)),$("div.jqZoomPup").height(t.yzoom/(1*c)),t.lens&&$("div.jqZoomPup").css("visibility","visible")}n=h.x-$("div.jqZoomPup").width()/2-o,r=h.y-$("div.jqZoomPup").height()/2-s,t.lens&&(n=h.x-$("div.jqZoomPup").width()/2<o?0:h.x+$("div.jqZoomPup").width()/2>d+o?d-$("div.jqZoomPup").width()-2:n,r=h.y-$("div.jqZoomPup").height()/2<s?0:h.y+$("div.jqZoomPup").height()/2>m+s?m-$("div.jqZoomPup").height()-2:r),t.lens&&$("div.jqZoomPup").css({top:r,left:n}),f=r,$("div.zoomdiv").get(0).scrollTop=f*c,l=n,$("div.zoomdiv").get(0).scrollLeft=l*a})},function(){$(this).children("img").attr("alt",e),$(document.body).unbind("mousemove"),t.lens&&$("div.jqZoomPup").remove(),$("div.zoomdiv").remove()});var s=0;t.preload&&($("body").append("<div style='display:none;' class='jqPreload"+s+"'>360buy</div>"),$(this).each(function(){var i=$(this).children("img").attr("jqimg"),o=jQuery("div.jqPreload"+s).html();jQuery("div.jqPreload"+s).html(o+'<img src="'+i+'">')}))}}(jQuery)});