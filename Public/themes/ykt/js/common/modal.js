"use strict";var _typeof="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol?"symbol":typeof t};define(function(require,exports,module){+function($){function t(t,o){return this.each(function(){var s=$(this),i=s.data("bs.modal"),n=$.extend({},e.DEFAULTS,s.data(),"object"==("undefined"==typeof t?"undefined":_typeof(t))&&t);i||s.data("bs.modal",i=new e(this,n)),"string"==typeof t?i[t](o):n.show&&i.show(o)})}var e=function(t,e){this.options=e,this.$body=$(document.body),this.$element=$(t),this.$backdrop=this.isShown=null,this.scrollbarWidth=0,this.options.remote&&this.$element.find(".modal-content").load(this.options.remote,$.proxy(function(){this.$element.trigger("loaded.bs.modal")},this))};e.VERSION="3.3.2",e.TRANSITION_DURATION=300,e.BACKDROP_TRANSITION_DURATION=150,e.DEFAULTS={backdrop:!0,keyboard:!0,show:!0},e.prototype.toggle=function(t){return this.isShown?this.hide():this.show(t)},e.prototype.show=function(t){var o=this,s=$.Event("show.bs.modal",{relatedTarget:t});this.$element.trigger(s),this.isShown||s.isDefaultPrevented()||(this.isShown=!0,this.checkScrollbar(),this.setScrollbar(),this.$body.addClass("m-modal-open"),this.escape(),this.resize(),this.$element.on("click.dismiss.bs.modal",'[data-dismiss="modal"]',$.proxy(this.hide,this)),this.backdrop(function(){var s=$.support.transition&&o.$element.hasClass("fade");o.$element.parent().length||o.$element.appendTo(o.$body),o.$element.show().scrollTop(0),o.options.backdrop&&o.adjustBackdrop(),o.adjustDialog(),s&&o.$element[0].offsetWidth,o.$element.addClass("in").attr("aria-hidden",!1),o.enforceFocus();var i=$.Event("shown.bs.modal",{relatedTarget:t});s?o.$element.find(".modal-dialog").one("bsTransitionEnd",function(){o.$element.trigger("focus").trigger(i)}).emulateTransitionEnd(e.TRANSITION_DURATION):o.$element.trigger("focus").trigger(i)}))},e.prototype.hide=function(t){t&&t.preventDefault(),t=$.Event("hide.bs.modal"),this.$element.trigger(t),this.isShown&&!t.isDefaultPrevented()&&(this.isShown=!1,this.escape(),this.resize(),$(document).off("focusin.bs.modal"),this.$element.removeClass("in").attr("aria-hidden",!0).off("click.dismiss.bs.modal"),$.support.transition&&this.$element.hasClass("fade")?this.$element.one("bsTransitionEnd",$.proxy(this.hideModal,this)).emulateTransitionEnd(e.TRANSITION_DURATION):this.hideModal())},e.prototype.enforceFocus=function(){$(document).off("focusin.bs.modal").on("focusin.bs.modal",$.proxy(function(t){this.$element[0]===t.target||this.$element.has(t.target).length||this.$element.trigger("focus")},this))},e.prototype.escape=function(){this.isShown&&this.options.keyboard?this.$element.on("keydown.dismiss.bs.modal",$.proxy(function(t){27==t.which&&this.hide()},this)):this.isShown||this.$element.off("keydown.dismiss.bs.modal")},e.prototype.resize=function(){this.isShown?$(window).on("resize.bs.modal",$.proxy(this.handleUpdate,this)):$(window).off("resize.bs.modal")},e.prototype.hideModal=function(){var t=this;this.$element.hide(),this.backdrop(function(){t.$body.removeClass("m-modal-open"),t.resetAdjustments(),t.resetScrollbar(),t.$element.trigger("hidden.bs.modal")})},e.prototype.removeBackdrop=function(){this.$backdrop&&this.$backdrop.remove(),this.$backdrop=null},e.prototype.backdrop=function(t){var o=this,s=this.$element.hasClass("fade")?"fade":"";if(this.isShown&&this.options.backdrop){var i=$.support.transition&&s;if(this.$backdrop=$('<div class="modal-backdrop '+s+'" />').prependTo(this.$element).on("click.dismiss.bs.modal",$.proxy(function(t){t.target===t.currentTarget&&("static"==this.options.backdrop?this.$element[0].focus.call(this.$element[0]):this.hide.call(this))},this)),i&&this.$backdrop[0].offsetWidth,this.$backdrop.addClass("in"),!t)return;i?this.$backdrop.one("bsTransitionEnd",t).emulateTransitionEnd(e.BACKDROP_TRANSITION_DURATION):t()}else if(!this.isShown&&this.$backdrop){this.$backdrop.removeClass("in");var n=function(){o.removeBackdrop(),t&&t()};$.support.transition&&this.$element.hasClass("fade")?this.$backdrop.one("bsTransitionEnd",n).emulateTransitionEnd(e.BACKDROP_TRANSITION_DURATION):n()}else t&&t()},e.prototype.handleUpdate=function(){this.options.backdrop&&this.adjustBackdrop(),this.adjustDialog()},e.prototype.adjustBackdrop=function(){this.$backdrop.css("height",0).css("height",this.$element[0].scrollHeight)},e.prototype.adjustDialog=function(){var t=this.$element[0].scrollHeight>document.documentElement.clientHeight;this.$element.css({paddingLeft:!this.bodyIsOverflowing&&t?this.scrollbarWidth:"",paddingRight:this.bodyIsOverflowing&&!t?this.scrollbarWidth:""})},e.prototype.resetAdjustments=function(){this.$element.css({paddingLeft:"",paddingRight:""})},e.prototype.checkScrollbar=function(){this.bodyIsOverflowing=document.body.scrollHeight>document.documentElement.clientHeight,this.scrollbarWidth=this.measureScrollbar()},e.prototype.setScrollbar=function(){var t=parseInt(this.$body.css("padding-right")||0,10);this.bodyIsOverflowing&&this.$body.css("padding-right",t+this.scrollbarWidth)},e.prototype.resetScrollbar=function(){this.$body.css("padding-right","")},e.prototype.measureScrollbar=function(){var t=document.createElement("div");t.className="m-modal-scrollbar-measure",this.$body.append(t);var e=t.offsetWidth-t.clientWidth;return this.$body[0].removeChild(t),e};var o=$.fn.modal;$.fn.modal=t,$.fn.modal.Constructor=e,$.fn.modal.noConflict=function(){return $.fn.modal=o,this},$(document).on("click.bs.modal.data-api",'[data-toggle="modal"]',function(e){var o=$(this),s=o.attr("href"),i=$(o.attr("data-target")||s&&s.replace(/.*(?=#[^\s]+$)/,"")),n=i.data("bs.modal")?"toggle":$.extend({remote:!/#/.test(s)&&s},i.data(),o.data());o.is("a")&&e.preventDefault(),i.one("show.bs.modal",function(t){t.isDefaultPrevented()||i.one("hidden.bs.modal",function(){o.is(":visible")&&o.trigger("focus")})}),t.call(i,n,this)})}(jQuery)});