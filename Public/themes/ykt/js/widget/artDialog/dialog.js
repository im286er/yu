"use strict";var _typeof="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol?"symbol":typeof t};define(function(require){var $=require("jquery"),t=require("./popup"),e=require("./dialog-config"),n=0,i=new Date-0,o=!("minWidth"in $("html")[0].style),s="createTouch"in document&&!("onmousemove"in document)||/(iPhone|iPad|iPod)/i.test(navigator.userAgent),c=!o&&!s,a=function d(t,e,o){var a=t=t||{};"string"!=typeof t&&1!==t.nodeType||(t={content:t,fixed:!s}),t=$.extend(!0,{},d.defaults,t),t.original=a;var r=t.id=t.id||i+n,u=d.get(r);return u?u.focus():(c||(t.fixed=!1),t.quickClose&&(t.modal=!0,t.backdropOpacity=0),$.isArray(t.button)||(t.button=[]),void 0!==o&&(t.cancel=o),t.cancel&&t.button.push({id:"cancel",value:t.cancelValue,callback:t.cancel,display:t.cancelDisplay}),void 0!==e&&(t.ok=e),t.ok&&t.button.push({id:"ok",value:t.okValue,callback:t.ok,autofocus:!0}),d.list[r]=new d.create(t))},r=function(){};r.prototype=t.prototype;var u=a.prototype=new r;return a.create=function(e){var i=this;$.extend(this,new t);var o=(e.original,$(this.node).html(e.innerHTML)),s=$(this.backdrop);return this.options=e,this._popup=o,$.each(e,function(t,e){"function"==typeof i[t]?i[t](e):i[t]=e}),e.zIndex&&(t.zIndex=e.zIndex),o.attr({"aria-labelledby":this._$("title").attr("id","title:"+this.id).attr("id"),"aria-describedby":this._$("content").attr("id","content:"+this.id).attr("id")}),this._$("close").css("display",this.cancel===!1?"none":"").attr("title",this.cancelValue).on("click",function(t){i._trigger("cancel"),t.preventDefault()}),this._$("dialog").addClass(this.skin),this._$("body").css("padding",this.padding),e.quickClose&&s.on("onmousedown"in document?"mousedown":"click",function(){return i._trigger("cancel"),!1}),this.addEventListener("show",function(){s.css({opacity:0,background:e.backdropBackground}).animate({opacity:e.backdropOpacity},150)}),this._esc=function(e){var n=e.target,o=n.nodeName,s=/^input|textarea$/i,c=t.current===i,a=e.keyCode;!c||s.test(o)&&"button"!==n.type||27===a&&i._trigger("cancel")},$(document).on("keydown",this._esc),this.addEventListener("remove",function(){$(document).off("keydown",this._esc),delete a.list[this.id]}),n++,a.oncreate(this),this},a.create.prototype=u,$.extend(u,{content:function(t){var e=this._$("content");return"object"===("undefined"==typeof t?"undefined":_typeof(t))?(t=$(t),e.empty("").append(t.show()),this.addEventListener("beforeremove",function(){$("body").append(t.hide())})):e.html(t),this.reset()},title:function(t){return this._$("title").text(t),this._$("header")[t?"show":"hide"](),this},width:function(t){return this._$("content").css("width",t),this.reset()},height:function(t){return this._$("content").css("height",t),this.reset()},button:function(t){t=t||[];var e=this,n="",i=0;return this.callbacks={},"string"==typeof t?(n=t,i++):$.each(t,function(t,o){var s=o.id=o.id||o.value,c="";e.callbacks[s]=o.callback,o.display===!1?c=' style="display:none"':i++,n+='<button type="button" i-id="'+s+'"'+c+(o.disabled?" disabled":"")+(o.autofocus?' autofocus class="ui-dialog-autofocus"':"")+">"+o.value+"</button>",e._$("button").on("click","[i-id="+s+"]",function(t){var n=$(this);n.attr("disabled")||e._trigger(s),t.preventDefault()})}),this._$("button").html(n),this._$("footer")[i?"show":"hide"](),this},statusbar:function(t){return this._$("statusbar").html(t)[t?"show":"hide"](),this},_$:function(t){return this._popup.find("[i="+t+"]")},_trigger:function(t){var e=this.callbacks[t];return"function"!=typeof e||e.call(this)!==!1?this.close().remove():this}}),a.oncreate=$.noop,a.getCurrent=function(){return t.current},a.get=function(t){return void 0===t?a.list:a.list[t]},a.list={},a.defaults=e,a});