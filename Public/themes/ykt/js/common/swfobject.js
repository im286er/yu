"use strict";var _typeof="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol?"symbol":typeof e};define(function(require,exports,module){var e=function(){function t(){if(!G){try{var e=V.getElementsByTagName("body")[0].appendChild(m("span"));e.parentNode.removeChild(e)}catch(t){return}G=!0;for(var n=D.length,a=0;n>a;a++)D[a]()}}function n(e){G?e():D[D.length]=e}function a(e){if(_typeof(M.addEventListener)!=L)M.addEventListener("load",e,!1);else if(_typeof(V.addEventListener)!=L)V.addEventListener("load",e,!1);else if(_typeof(M.attachEvent)!=L)g(M,"onload",e);else if("function"==typeof M.onload){var t=M.onload;M.onload=function(){t(),e()}}else M.onload=e}function i(){R?r():o()}function r(){var e=V.getElementsByTagName("body")[0],t=m(k);t.setAttribute("type",j);var n=e.appendChild(t);if(n){var a=0;!function(){if(_typeof(n.GetVariable)!=L){var i=n.GetVariable("$version");i&&(i=i.split(" ")[1].split(","),z.pv=[parseInt(i[0],10),parseInt(i[1],10),parseInt(i[2],10)])}else if(10>a)return a++,void setTimeout(arguments.callee,10);e.removeChild(t),n=null,o()}()}else o()}function o(){var e=W.length;if(e>0)for(var t=0;e>t;t++){var n=W[t].id,a=W[t].callbackFn,i={success:!1,id:n};if(z.pv[0]>0){var r=h(n);if(r)if(!w(W[t].swfVersion)||z.wk&&z.wk<312)if(W[t].expressInstall&&s()){var o={};o.data=W[t].expressInstall,o.width=r.getAttribute("width")||"0",o.height=r.getAttribute("height")||"0",r.getAttribute("class")&&(o.styleclass=r.getAttribute("class")),r.getAttribute("align")&&(o.align=r.getAttribute("align"));for(var c={},u=r.getElementsByTagName("param"),p=u.length,v=0;p>v;v++)"movie"!=u[v].getAttribute("name").toLowerCase()&&(c[u[v].getAttribute("name")]=u[v].getAttribute("value"));f(o,c,n,a)}else d(r),a&&a(i);else C(n,!0),a&&(i.success=!0,i.ref=l(n),a(i))}else if(C(n,!0),a){var y=l(n);y&&_typeof(y.SetVariable)!=L&&(i.success=!0,i.ref=y),a(i)}}}function l(e){var t=null,n=h(e);if(n&&"OBJECT"==n.nodeName)if(_typeof(n.SetVariable)!=L)t=n;else{var a=n.getElementsByTagName(k)[0];a&&(t=a)}return t}function s(){return!J&&w("6.0.65")&&(z.win||z.mac)&&!(z.wk&&z.wk<312)}function f(e,t,n,a){J=!0,A=a||null,I={success:!1,id:n};var i=h(n);if(i){"OBJECT"==i.nodeName?(E=c(i),_=null):(E=i,_=n),e.id=x,(_typeof(e.width)==L||!/%$/.test(e.width)&&parseInt(e.width,10)<310)&&(e.width="310"),(_typeof(e.height)==L||!/%$/.test(e.height)&&parseInt(e.height,10)<137)&&(e.height="137"),V.title=V.title.slice(0,47)+" - Flash Player Installation";var r=z.ie&&z.win?"ActiveX":"PlugIn",o="MMredirectURL="+M.location.toString().replace(/&/g,"%26")+"&MMplayerType="+r+"&MMdoctitle="+V.title;if(_typeof(t.flashvars)!=L?t.flashvars+="&"+o:t.flashvars=o,z.ie&&z.win&&4!=i.readyState){var l=m("div");n+="SWFObjectNew",l.setAttribute("id",n),i.parentNode.insertBefore(l,i),i.style.display="none",function(){4==i.readyState?i.parentNode.removeChild(i):setTimeout(arguments.callee,10)}()}u(e,t,n)}}function d(e){if(z.ie&&z.win&&4!=e.readyState){var t=m("div");e.parentNode.insertBefore(t,e),t.parentNode.replaceChild(c(e),t),e.style.display="none",function(){4==e.readyState?e.parentNode.removeChild(e):setTimeout(arguments.callee,10)}()}else e.parentNode.replaceChild(c(e),e)}function c(e){var t=m("div");if(z.win&&z.ie)t.innerHTML=e.innerHTML;else{var n=e.getElementsByTagName(k)[0];if(n){var a=n.childNodes;if(a)for(var i=a.length,r=0;i>r;r++)1==a[r].nodeType&&"PARAM"==a[r].nodeName||8==a[r].nodeType||t.appendChild(a[r].cloneNode(!0))}}return t}function u(e,t,n){var a,i=h(n);if(z.wk&&z.wk<312)return a;if(i)if(_typeof(e.id)==L&&(e.id=n),z.ie&&z.win){var r="";for(var o in e)e[o]!=Object.prototype[o]&&("data"==o.toLowerCase()?t.movie=e[o]:"styleclass"==o.toLowerCase()?r+=' class="'+e[o]+'"':"classid"!=o.toLowerCase()&&(r+=" "+o+'="'+e[o]+'"'));var l="";for(var s in t)t[s]!=Object.prototype[s]&&(l+='<param name="'+s+'" value="'+t[s]+'" />');i.outerHTML='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"'+r+">"+l+"</object>",H[H.length]=e.id,a=h(e.id)}else{var f=m(k);f.setAttribute("type",j);for(var d in e)e[d]!=Object.prototype[d]&&("styleclass"==d.toLowerCase()?f.setAttribute("class",e[d]):"classid"!=d.toLowerCase()&&f.setAttribute(d,e[d]));for(var c in t)t[c]!=Object.prototype[c]&&"movie"!=c.toLowerCase()&&p(f,c,t[c]);i.parentNode.replaceChild(f,i),a=f}return a}function p(e,t,n){var a=m("param");a.setAttribute("name",t),a.setAttribute("value",n),e.appendChild(a)}function v(e){var t=h(e);t&&"OBJECT"==t.nodeName&&(z.ie&&z.win?(t.style.display="none",function(){4==t.readyState?y(e):setTimeout(arguments.callee,10)}()):t.parentNode.removeChild(t))}function y(e){var t=h(e);if(t){for(var n in t)"function"==typeof t[n]&&(t[n]=null);t.parentNode.removeChild(t)}}function h(e){var t=null;try{t=V.getElementById(e)}catch(n){}return t}function m(e){return V.createElement(e)}function g(e,t,n){e.attachEvent(t,n),U[U.length]=[e,t,n]}function w(e){var t=z.pv,n=e.split(".");return n[0]=parseInt(n[0],10),n[1]=parseInt(n[1],10)||0,n[2]=parseInt(n[2],10)||0,t[0]>n[0]||t[0]==n[0]&&t[1]>n[1]||t[0]==n[0]&&t[1]==n[1]&&t[2]>=n[2]}function b(e,t,n,a){if(!z.ie||!z.mac){var i=V.getElementsByTagName("head")[0];if(i){var r=n&&"string"==typeof n?n:"screen";if(a&&(N=null,T=null),!N||T!=r){var o=m("style");o.setAttribute("type","text/css"),o.setAttribute("media",r),N=i.appendChild(o),z.ie&&z.win&&_typeof(V.styleSheets)!=L&&V.styleSheets.length>0&&(N=V.styleSheets[V.styleSheets.length-1]),T=r}z.ie&&z.win?N&&_typeof(N.addRule)==k&&N.addRule(e,t):N&&_typeof(V.createTextNode)!=L&&N.appendChild(V.createTextNode(e+" {"+t+"}"))}}}function C(e,t){if(X){var n=t?"visible":"hidden";G&&h(e)?h(e).style.visibility=n:b("#"+e,"visibility:"+n)}}function S(e){var t=/[\\\"<>\.;]/,n=null!=t.exec(e);return n&&("undefined"==typeof encodeURIComponent?"undefined":_typeof(encodeURIComponent))!=L?encodeURIComponent(e):e}var E,_,A,I,N,T,L="undefined",k="object",B="Shockwave Flash",O="ShockwaveFlash.ShockwaveFlash",j="application/x-shockwave-flash",x="SWFObjectExprInst",F="onreadystatechange",M=window,V=document,P=navigator,R=!1,D=[i],W=[],H=[],U=[],G=!1,J=!1,X=!0,z=function(){var e=_typeof(V.getElementById)!=L&&_typeof(V.getElementsByTagName)!=L&&_typeof(V.createElement)!=L,t=P.userAgent.toLowerCase(),n=P.platform.toLowerCase(),a=n?/win/.test(n):/win/.test(t),i=n?/mac/.test(n):/mac/.test(t),r=/webkit/.test(t)?parseFloat(t.replace(/^.*webkit\/(\d+(\.\d+)?).*$/,"$1")):!1,o=!1,l=[0,0,0],s=null;if(_typeof(P.plugins)!=L&&_typeof(P.plugins[B])==k)s=P.plugins[B].description,!s||_typeof(P.mimeTypes)!=L&&P.mimeTypes[j]&&!P.mimeTypes[j].enabledPlugin||(R=!0,o=!1,s=s.replace(/^.*\s+(\S+\s+\S+$)/,"$1"),l[0]=parseInt(s.replace(/^(.*)\..*$/,"$1"),10),l[1]=parseInt(s.replace(/^.*\.(.*)\s.*$/,"$1"),10),l[2]=/[a-zA-Z]/.test(s)?parseInt(s.replace(/^.*[a-zA-Z]+(.*)$/,"$1"),10):0);else if(_typeof(M.ActiveXObject)!=L)try{var f=new ActiveXObject(O);f&&(s=f.GetVariable("$version"),s&&(o=!0,s=s.split(" ")[1].split(","),l=[parseInt(s[0],10),parseInt(s[1],10),parseInt(s[2],10)]))}catch(d){}return{w3:e,pv:l,wk:r,ie:o,win:a,mac:i}}();(function(){z.w3&&((_typeof(V.readyState)!=L&&"complete"==V.readyState||_typeof(V.readyState)==L&&(V.getElementsByTagName("body")[0]||V.body))&&t(),G||(_typeof(V.addEventListener)!=L&&V.addEventListener("DOMContentLoaded",t,!1),z.ie&&z.win&&(V.attachEvent(F,function(){"complete"==V.readyState&&(V.detachEvent(F,arguments.callee),t())}),M==top&&!function(){if(!G){try{V.documentElement.doScroll("left")}catch(e){return void setTimeout(arguments.callee,0)}t()}}()),z.wk&&!function(){return G?void 0:/loaded|complete/.test(V.readyState)?void t():void setTimeout(arguments.callee,0)}(),a(t)))})(),function(){z.ie&&z.win&&window.attachEvent("onunload",function(){for(var t=U.length,n=0;t>n;n++)U[n][0].detachEvent(U[n][1],U[n][2]);for(var a=H.length,i=0;a>i;i++)v(H[i]);for(var r in z)z[r]=null;z=null;for(var o in e)e[o]=null;e=null})}();return{registerObject:function(e,t,n,a){if(z.w3&&e&&t){var i={};i.id=e,i.swfVersion=t,i.expressInstall=n,i.callbackFn=a,W[W.length]=i,C(e,!1)}else a&&a({success:!1,id:e})},getObjectById:function(e){return z.w3?l(e):void 0},embedSWF:function(e,t,a,i,r,o,l,d,c,p){var v={success:!1,id:t};z.w3&&!(z.wk&&z.wk<312)&&e&&t&&a&&i&&r?(C(t,!1),n(function(){a+="",i+="";var n={};if(c&&("undefined"==typeof c?"undefined":_typeof(c))===k)for(var y in c)n[y]=c[y];n.data=e,n.width=a,n.height=i;var h={};if(d&&("undefined"==typeof d?"undefined":_typeof(d))===k)for(var m in d)h[m]=d[m];if(l&&("undefined"==typeof l?"undefined":_typeof(l))===k)for(var g in l)_typeof(h.flashvars)!=L?h.flashvars+="&"+g+"="+l[g]:h.flashvars=g+"="+l[g];if(w(r)){var b=u(n,h,t);n.id==t&&C(t,!0),v.success=!0,v.ref=b}else{if(o&&s())return n.data=o,void f(n,h,t,p);C(t,!0)}p&&p(v)})):p&&p(v)},switchOffAutoHideShow:function(){X=!1},ua:z,getFlashPlayerVersion:function(){return{major:z.pv[0],minor:z.pv[1],release:z.pv[2]}},hasFlashPlayerVersion:w,createSWF:function(e,t,n){return z.w3?u(e,t,n):void 0},showExpressInstall:function(e,t,n,a){z.w3&&s()&&f(e,t,n,a)},removeSWF:function(e){z.w3&&v(e)},createCSS:function(e,t,n,a){z.w3&&b(e,t,n,a)},addDomLoadEvent:n,addLoadEvent:a,getQueryParamValue:function(e){var t=V.location.search||V.location.hash;if(t){if(/\?/.test(t)&&(t=t.split("?")[1]),null==e)return S(t);for(var n=t.split("&"),a=0;a<n.length;a++)if(n[a].substring(0,n[a].indexOf("="))==e)return S(n[a].substring(n[a].indexOf("=")+1))}return""},expressInstallCallback:function(){if(J){var e=h(x);e&&E&&(e.parentNode.replaceChild(E,e),_&&(C(_,!0),z.ie&&z.win&&(E.style.display="block")),A&&A(I)),J=!1}}}}();module.exports=e});