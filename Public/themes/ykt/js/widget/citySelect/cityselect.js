"use strict";!function($){$.fn.citySelect=function(t){if(!(this.length<1)){t=$.extend({url:"/Public/themes/ykt/js/widget/citySelect/city.min.js",prov:null,city:null,dist:null,nodata:null,required:!0},t);var i,e=this,n=e.find(".prov"),l=e.find(".city"),d=e.find(".dist"),s=(t.prov,t.city,t.dist,t.required?"":"<option value=''>请选择</option>"),o=function(){var e=n.get(0).selectedIndex;return t.required||e--,l.empty().attr("disabled",!0),d.empty().attr("disabled",!0),0>e||"undefined"==typeof i.citylist[e].c?void("none"==t.nodata?(l.css("display","none"),d.css("display","none")):"hidden"==t.nodata&&(l.css("visibility","hidden"),d.css("visibility","hidden"))):(temp_html=s,$.each(i.citylist[e].c,function(t,i){temp_html+="<option value='"+i.n+"'>"+i.n+"</option>"}),l.html(temp_html).attr("disabled",!1).css({display:"",visibility:""}),void c())},c=function(){var e=n.get(0).selectedIndex,o=l.get(0).selectedIndex;return t.required||(e--,o--),d.empty().attr("disabled",!0),0>e||0>o||"undefined"==typeof i.citylist[e].c[o].a?void("none"==t.nodata?d.css("display","none"):"hidden"==t.nodata&&d.css("visibility","hidden")):(temp_html=s,$.each(i.citylist[e].c[o].a,function(t,i){temp_html+="<option value='"+i.s+"'>"+i.s+"</option>"}),void d.html(temp_html).attr("disabled",!1).css({display:"",visibility:""}))},u=function(){temp_html=s,$.each(i.citylist,function(t,i){temp_html+="<option value='"+i.p+"'>"+i.p+"</option>"}),n.html(temp_html),setTimeout(function(){null!=t.prov&&(n.val(t.prov),o(),setTimeout(function(){null!=t.city&&(l.val(t.city),c(),setTimeout(function(){null!=t.dist&&d.val(t.dist)},1))},1))},1),n.bind("change",function(){o()}),l.bind("change",function(){c()})};"string"==typeof t.url?$.getJSON(t.url,function(t){i=t,u()}):(i=t.url,u())}}}(jQuery);