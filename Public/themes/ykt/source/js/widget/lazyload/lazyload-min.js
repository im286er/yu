define(function(b,a,c){(function(f,e,d,h){var g=f(e);f.fn.lazyload=function(i){var k=this;var l;var j={threshold:0,failure_limit:0,event:"scroll",effect:"show",container:e,data_attribute:"original",skip_invisible:false,appear:null,load:null,placeholder:"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"};function m(){var n=0;k.each(function(){var o=f(this);if(j.skip_invisible&&!o.is(":visible")){return}if(f.abovethetop(this,j)||f.leftofbegin(this,j)){}else{if(!f.belowthefold(this,j)&&!f.rightoffold(this,j)){o.trigger("appear");n=0}else{if(++n>j.failure_limit){return false}}}})}if(i){if(h!==i.failurelimit){i.failure_limit=i.failurelimit;delete i.failurelimit}if(h!==i.effectspeed){i.effect_speed=i.effectspeed;delete i.effectspeed}f.extend(j,i)}l=(j.container===h||j.container===e)?g:f(j.container);if(0===j.event.indexOf("scroll")){l.bind(j.event,function(){return m()})}this.each(function(){var n=this;var o=f(n);n.loaded=false;if(o.attr("src")===h||o.attr("src")===false){if(o.is("img")){o.attr("src",j.placeholder)}}o.one("appear",function(){if(!this.loaded){if(j.appear){var p=k.length;j.appear.call(n,p,j)}f("<img />").bind("load",function(){var r=o.attr("data-"+j.data_attribute);o.hide();if(o.is("img")){o.attr("src",r)}else{o.css("background-image","url('"+r+"')")}o[j.effect](j.effect_speed);n.loaded=true;var q=f.grep(k,function(t){return !t.loaded});k=f(q);if(j.load){var s=k.length;j.load.call(n,s,j)}}).attr("src",o.attr("data-"+j.data_attribute))}});if(0!==j.event.indexOf("scroll")){o.bind(j.event,function(){if(!n.loaded){o.trigger("appear")}})}});g.bind("resize",function(){m()});if((/(?:iphone|ipod|ipad).*os 5/gi).test(navigator.appVersion)){g.bind("pageshow",function(n){if(n.originalEvent&&n.originalEvent.persisted){k.each(function(){f(this).trigger("appear")})}})}f(d).ready(function(){m()});return this};f.belowthefold=function(j,k){var i;if(k.container===h||k.container===e){i=(e.innerHeight?e.innerHeight:g.height())+g.scrollTop()}else{i=f(k.container).offset().top+f(k.container).height()}return i<=f(j).offset().top-k.threshold};f.rightoffold=function(j,k){var i;if(k.container===h||k.container===e){i=g.width()+g.scrollLeft()}else{i=f(k.container).offset().left+f(k.container).width()}return i<=f(j).offset().left-k.threshold};f.abovethetop=function(j,k){var i;if(k.container===h||k.container===e){i=g.scrollTop()}else{i=f(k.container).offset().top}return i>=f(j).offset().top+k.threshold+f(j).height()};f.leftofbegin=function(j,k){var i;if(k.container===h||k.container===e){i=g.scrollLeft()}else{i=f(k.container).offset().left}return i>=f(j).offset().left+k.threshold+f(j).width()};f.inviewport=function(i,j){return !f.rightoffold(i,j)&&!f.leftofbegin(i,j)&&!f.belowthefold(i,j)&&!f.abovethetop(i,j)};f.extend(f.expr[":"],{"below-the-fold":function(i){return f.belowthefold(i,{threshold:0})},"above-the-top":function(i){return !f.belowthefold(i,{threshold:0})},"right-of-screen":function(i){return f.rightoffold(i,{threshold:0})},"left-of-screen":function(i){return !f.rightoffold(i,{threshold:0})},"in-viewport":function(i){return f.inviewport(i,{threshold:0})},"above-the-fold":function(i){return !f.belowthefold(i,{threshold:0})},"right-of-fold":function(i){return f.rightoffold(i,{threshold:0})},"left-of-fold":function(i){return !f.rightoffold(i,{threshold:0})}})})(jQuery,window,document)});