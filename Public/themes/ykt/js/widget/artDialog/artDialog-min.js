"use strict";var _typeof="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol?"symbol":typeof t};define(function(require){!function(){function t(e){var o=i[e],s="exports";return"object"==("undefined"==typeof o?"undefined":_typeof(o))?o:(o[s]||(o[s]={},o[s]=o.call(o[s],t,o[s],o)||o[s]),o[s])}function e(t,e){i[t]=e}var i={};e("jquery",function(){return jQuery}),e("popup",function(t){function e(){this.destroyed=!1,this.__popup=i("<div />").css({display:"none",position:"absolute",outline:0}).attr("tabindex","-1").html(this.innerHTML).appendTo("body"),this.__backdrop=this.__mask=i("<div />").css({opacity:.7,background:"#000"}),this.node=this.__popup[0],this.backdrop=this.__backdrop[0],o++}var i=t("jquery"),o=0,s=!("minWidth"in i("html")[0].style),n=!s;return i.extend(e.prototype,{node:null,backdrop:null,fixed:!1,destroyed:!0,open:!1,returnValue:"",autofocus:!0,align:"bottom left",innerHTML:"",className:"ui-popup",show:function(t){if(this.destroyed)return this;var o=this.__popup,a=this.__backdrop;if(this.__activeElement=this.__getActive(),this.open=!0,this.follow=t||this.follow,!this.__ready){if(o.addClass(this.className).attr("role",this.modal?"alertdialog":"dialog").css("position",this.fixed?"fixed":"absolute"),s||i(window).on("resize",i.proxy(this.reset,this)),this.modal){var r={position:"fixed",left:0,top:0,width:"100%",height:"100%",overflow:"hidden",userSelect:"none",zIndex:this.zIndex||e.zIndex};o.addClass(this.className+"-modal"),n||i.extend(r,{position:"absolute",width:i(window).width()+"px",height:i(document).height()+"px"}),a.css(r).attr({tabindex:"0"}).on("focus",i.proxy(this.focus,this)),this.__mask=a.clone(!0).attr("style","").insertAfter(o),a.addClass(this.className+"-backdrop").insertBefore(o),this.__ready=!0}o.html()||o.html(this.innerHTML)}return o.addClass(this.className+"-show").show(),a.show(),this.reset().focus(),this.__dispatchEvent("show"),this},showModal:function(){return this.modal=!0,this.show.apply(this,arguments)},close:function(t){return!this.destroyed&&this.open&&(void 0!==t&&(this.returnValue=t),this.__popup.hide().removeClass(this.className+"-show"),this.__backdrop.hide(),this.open=!1,this.blur(),this.__dispatchEvent("close")),this},remove:function(){if(this.destroyed)return this;this.__dispatchEvent("beforeremove"),e.current===this&&(e.current=null),this.__popup.remove(),this.__backdrop.remove(),this.__mask.remove(),s||i(window).off("resize",this.reset),this.__dispatchEvent("remove");for(var t in this)delete this[t];return this},reset:function(){var t=this.follow;return t?this.__follow(t):this.__center(),this.__dispatchEvent("reset"),this},focus:function(){var t=this.node,o=this.__popup,s=e.current,n=this.zIndex=e.zIndex++;if(s&&s!==this&&s.blur(!1),!i.contains(t,this.__getActive())){var a=o.find("[autofocus]")[0];!this._autofocus&&a?this._autofocus=!0:a=t,this.__focus(a)}return o.css("zIndex",n),e.current=this,o.addClass(this.className+"-focus"),this.__dispatchEvent("focus"),this},blur:function(){var t=this.__activeElement,e=arguments[0];return e!==!1&&this.__focus(t),this._autofocus=!1,this.__popup.removeClass(this.className+"-focus"),this.__dispatchEvent("blur"),this},addEventListener:function(t,e){return this.__getEventListener(t).push(e),this},removeEventListener:function(t,e){for(var i=this.__getEventListener(t),o=0;o<i.length;o++)e===i[o]&&i.splice(o--,1);return this},__getEventListener:function(t){var e=this.__listener;return e||(e=this.__listener={}),e[t]||(e[t]=[]),e[t]},__dispatchEvent:function(t){var e=this.__getEventListener(t);this["on"+t]&&this["on"+t]();for(var i=0;i<e.length;i++)e[i].call(this)},__focus:function(t){try{this.autofocus&&!/^iframe$/i.test(t.nodeName)&&t.focus()}catch(e){}},__getActive:function(){try{var t=document.activeElement,e=t.contentDocument,i=e&&e.activeElement||t;return i}catch(o){}},__center:function(){var t=this.__popup,e=i(window),o=i(document),s=this.fixed,n=s?0:o.scrollLeft(),a=s?0:o.scrollTop(),r=e.width(),c=e.height(),l=t.width(),d=t.height(),h=(r-l)/2+n,u=382*(c-d)/1e3+a,f=t[0].style;f.left=Math.max(parseInt(h),n)+"px",f.top=Math.max(parseInt(u),a)+"px"},__follow:function(t){var e=t.parentNode&&i(t),o=this.__popup;if(this.__followSkin&&o.removeClass(this.__followSkin),e){var s=e.offset();if(s.left*s.top<0)return this.__center()}var n=this,a=this.fixed,r=i(window),c=i(document),l=r.width(),d=r.height(),h=c.scrollLeft(),u=c.scrollTop(),f=o.width(),p=o.height(),_=e?e.outerWidth():0,v=e?e.outerHeight():0,b=this.__offset(t),g=b.left,m=b.top,y=a?g-h:g,w=a?m-u:m,k=a?0:h,x=a?0:u,E=k+l-f,L=x+d-p,C={},I=this.align.split(" "),N=this.className+"-",z={top:"bottom",bottom:"top",left:"right",right:"left"},T={top:"top",bottom:"top",left:"left",right:"left"},M=[{top:w-p,bottom:w+v,left:y-f,right:y+_},{top:w,bottom:w-p+v,left:y,right:y-f+_}],S={left:y+_/2-f/2,top:w+v/2-p/2},V={left:[k,E],top:[x,L]};i.each(I,function(t,e){M[t][e]>V[T[e]][1]&&(e=I[t]=z[e]),M[t][e]<V[T[e]][0]&&(I[t]=z[e])}),I[1]||(T[I[1]]="left"===T[I[0]]?"top":"left",M[1][I[1]]=S[T[I[1]]]),N+=I.join("-")+" "+this.className+"-follow",n.__followSkin=N,e&&o.addClass(N),C[T[I[0]]]=parseInt(M[0][I[0]]),C[T[I[1]]]=parseInt(M[1][I[1]]),o.css(C)},__offset:function(t){var e=t.parentNode,o=e?i(t).offset():{left:t.pageX,top:t.pageY};t=e?t:t.target;var s=t.ownerDocument,n=s.defaultView||s.parentWindow;if(n==window)return o;var a=n.frameElement,r=i(s),c=r.scrollLeft(),l=r.scrollTop(),d=i(a).offset(),h=d.left,u=d.top;return{left:o.left+h-c,top:o.top+u-l}}}),e.zIndex=1024,e.current=null,e}),e("dialog-config",{backdropBackground:"#000",backdropOpacity:.7,content:'<span class="ui-dialog-loading">Loading..</span>',title:"",statusbar:"",button:null,ok:null,cancel:null,okValue:"ok",cancelValue:"cancel",cancelDisplay:!0,width:"",height:"",padding:"",skin:"",quickClose:!1,cssUri:"../css/ui-dialog.css",innerHTML:'<div i="dialog" class="ui-dialog"><div class="ui-dialog-arrow-a"></div><div class="ui-dialog-arrow-b"></div><table class="ui-dialog-grid"><tr><td i="header" class="ui-dialog-header"><button i="close" class="ui-dialog-close">&#215;</button><div i="title" class="ui-dialog-title"></div></td></tr><tr><td i="body" class="ui-dialog-body"><div i="content" class="ui-dialog-content"></div></td></tr><tr><td i="footer" class="ui-dialog-footer"><div i="statusbar" class="ui-dialog-statusbar"></div><div i="button" class="ui-dialog-button"></div></td></tr></table></div>'}),e("dialog",function(t){var e=t("jquery"),i=t("popup"),o=t("dialog-config"),s=o.cssUri;if(s){var n=t[t.toUrl?"toUrl":"resolve"];n&&(s=n(s),s='<link rel="stylesheet" href="'+s+'" />',e("base")[0]?e("base").before(s):e("head").append(s))}var a=0,r=new Date-0,c=!("minWidth"in e("html")[0].style),l="createTouch"in document&&!("onmousemove"in document)||/(iPhone|iPad|iPod)/i.test(navigator.userAgent),d=!c&&!l,h=function p(t,i,o){var s=t=t||{};("string"==typeof t||1===t.nodeType)&&(t={content:t,fixed:!l}),t=e.extend(!0,{},p.defaults,t),t.original=s;var n=t.id=t.id||r+a,c=p.get(n);return c?c.focus():(d||(t.fixed=!1),t.quickClose&&(t.modal=!0,t.backdropOpacity=0),e.isArray(t.button)||(t.button=[]),void 0!==o&&(t.cancel=o),t.cancel&&t.button.push({id:"cancel",value:t.cancelValue,callback:t.cancel,display:t.cancelDisplay}),void 0!==i&&(t.ok=i),t.ok&&t.button.push({id:"ok",value:t.okValue,callback:t.ok,autofocus:!0}),p.list[n]=new p.create(t))},u=function(){};u.prototype=i.prototype;var f=h.prototype=new u;return h.create=function(t){var o=this;e.extend(this,new i);var s=(t.original,e(this.node).html(t.innerHTML)),n=e(this.backdrop);return this.options=t,this._popup=s,e.each(t,function(t,e){"function"==typeof o[t]?o[t](e):o[t]=e}),t.zIndex&&(i.zIndex=t.zIndex),s.attr({"aria-labelledby":this._$("title").attr("id","title:"+this.id).attr("id"),"aria-describedby":this._$("content").attr("id","content:"+this.id).attr("id")}),this._$("close").css("display",this.cancel===!1?"none":"").attr("title",this.cancelValue).on("click",function(t){o._trigger("cancel"),t.preventDefault()}),this._$("dialog").addClass(this.skin),this._$("body").css("padding",this.padding),t.quickClose&&n.on("onmousedown"in document?"mousedown":"click",function(){return o._trigger("cancel"),!1}),this.addEventListener("show",function(){n.css({opacity:0,background:t.backdropBackground}).animate({opacity:t.backdropOpacity},150)}),this._esc=function(t){var e=t.target,s=e.nodeName,n=/^input|textarea$/i,a=i.current===o,r=t.keyCode;!a||n.test(s)&&"button"!==e.type||27===r&&o._trigger("cancel")},e(document).on("keydown",this._esc),this.addEventListener("remove",function(){e(document).off("keydown",this._esc),delete h.list[this.id]}),a++,h.oncreate(this),this},h.create.prototype=f,e.extend(f,{content:function(t){var i=this._$("content");return"object"==("undefined"==typeof t?"undefined":_typeof(t))?(t=e(t),i.empty("").append(t.show()),this.addEventListener("beforeremove",function(){e("body").append(t.hide())})):i.html(t),this.reset()},title:function(t){return this._$("title").text(t),this._$("header")[t?"show":"hide"](),this},width:function(t){return this._$("content").css("width",t),this.reset()},height:function(t){return this._$("content").css("height",t),this.reset()},button:function(t){t=t||[];var i=this,o="",s=0;return this.callbacks={},"string"==typeof t?(o=t,s++):e.each(t,function(t,n){var a=n.id=n.id||n.value,r="";i.callbacks[a]=n.callback,n.display===!1?r=' style="display:none"':s++,o+='<button type="button" i-id="'+a+'"'+r+(n.disabled?" disabled":"")+(n.autofocus?' autofocus class="ui-dialog-autofocus"':"")+">"+n.value+"</button>",i._$("button").on("click","[i-id="+a+"]",function(t){var o=e(this);o.attr("disabled")||i._trigger(a),t.preventDefault()})}),this._$("button").html(o),this._$("footer")[s?"show":"hide"](),this},statusbar:function(t){return this._$("statusbar").html(t)[t?"show":"hide"](),this},_$:function(t){return this._popup.find("[i="+t+"]")},_trigger:function(t){var e=this.callbacks[t];return"function"!=typeof e||e.call(this)!==!1?this.close().remove():this}}),h.oncreate=e.noop,h.getCurrent=function(){return i.current},h.get=function(t){return void 0===t?h.list:h.list[t]},h.list={},h.defaults=o,h}),window.dialog=t("dialog")}()});