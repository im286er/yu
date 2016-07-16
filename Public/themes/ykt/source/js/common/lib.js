define(function(require, exports, module) {    
	var $ = require('jquery');	
    $.fn.jdMarquee = function(b, c) {
        "function" == typeof b && (c = b, b = {});
        var d, e = $.extend({
                deriction: "up",
                speed: 10,
                auto: !1,
                width: null,
                height: null,
                step: 1,
                control: !1,
                _front: null,
                _back: null,
                _stop: null,
                _continue: null,
                wrapstyle: "",
                stay: 5e3,
                delay: 20,
                dom: "div>ul>li".split(">"),
                mainTimer: null,
                subTimer: null,
                tag: !1,
                convert: !1,
                btn: null,
                disabled: "disabled",
                pos: {
                    ojbect: null,
                    clone: null
                }
            }, b || {}),
            f = this.find(e.dom[1]),
            g = this.find(e.dom[2]);
        if ("up" == e.deriction || "down" == e.deriction) {
            var h = f.eq(0).outerHeight(),
                i = e.step * g.eq(0).outerHeight();
            f.css({
                width: e.width + "px",
                overflow: "hidden"
            })
        }
        if ("left" == e.deriction || "right" == e.deriction) {
            var j = g.length * g.eq(0).outerWidth();
            f.css({
                width: j + "px",
                overflow: "hidden"
            });
            var i = e.step * g.eq(0).outerWidth()
        }
        var k = function() {
                var a = "<div style='position:relative;overflow:hidden;z-index:1;width:" + e.width + "px;height:" + e.height + "px;" + e.wrapstyle + "'></div>";
                switch (f.css({
                    position: "absolute",
                    left: 0,
                    top: 0
                }).wrap(a), e.pos.object = 0, d = f.clone(), f.after(d), e.deriction) {
                    default:
                        case "up":
                        f.css({
                        marginLeft: 0,
                        marginTop: 0
                    }),
                    d.css({
                        marginLeft: 0,
                        marginTop: h + "px"
                    }),
                    e.pos.clone = h;
                    break;
                    case "down":
                            f.css({
                            marginLeft: 0,
                            marginTop: 0
                        }),
                        d.css({
                            marginLeft: 0,
                            marginTop: -h + "px"
                        }),
                        e.pos.clone = -h;
                        break;
                    case "left":
                            f.css({
                            marginTop: 0,
                            marginLeft: 0
                        }),
                        d.css({
                            marginTop: 0,
                            marginLeft: j + "px"
                        }),
                        e.pos.clone = j;
                        break;
                    case "right":
                            f.css({
                            marginTop: 0,
                            marginLeft: 0
                        }),
                        d.css({
                            marginTop: 0,
                            marginLeft: -j + "px"
                        }),
                        e.pos.clone = -j
                }
                e.auto && (l(), f.hover(function() {
                    n(e.mainTimer)
                }, function() {
                    l()
                }), d.hover(function() {
                    n(e.mainTimer)
                }, function() {
                    l()
                })), c && c(), e.control && p()
            },
            l = function(a) {
                n(e.mainTimer), e.stay = a ? a : e.stay, e.mainTimer = setInterval(function() {
                    m()
                }, e.stay)
            },
            m = function() {
                n(e.subTimer), e.subTimer = setInterval(function() {
                    s()
                }, e.delay)
            },
            n = function(a) {
                null != a && clearInterval(a)
            },
            o = function(b) {
                b ? (a(e._front).unbind("click"), a(e._back).unbind("click"), a(e._stop).unbind("click"), a(e._continue).unbind("click")) : p()
            },
            p = function() {
                null != e._front && a(e._front).click(function() {
                    a(e._front).addClass(e.disabled), o(!0), n(e.mainTimer), e.convert = !0, e.btn = "front", m(), e.auto || (e.tag = !0), q()
                }), null != e._back && a(e._back).click(function() {
                    a(e._back).addClass(e.disabled), o(!0), n(e.mainTimer), e.convert = !0, e.btn = "back", m(), e.auto || (e.tag = !0), q()
                }), null != e._stop && a(e._stop).click(function() {
                    n(e.mainTimer)
                }), null != e._continue && a(e._continue).click(function() {
                    l()
                })
            },
            q = function() {
                e.tag && e.convert && (e.convert = !1, "front" == e.btn && ("down" == e.deriction && (e.deriction = "up"), "right" == e.deriction && (e.deriction = "left")), "back" == e.btn && ("up" == e.deriction && (e.deriction = "down"), "left" == e.deriction && (e.deriction = "right")), e.auto ? l() : l(4 * e.delay))
            },
            r = function(a, b, c) {
                c ? (n(e.subTimer), e.pos.object = a, e.pos.clone = b, e.tag = !0) : e.tag = !1, e.tag && (e.convert ? q() : e.auto || n(e.mainTimer)), ("up" == e.deriction || "down" == e.deriction) && (f.css({
                    marginTop: a + "px"
                }), d.css({
                    marginTop: b + "px"
                })), ("left" == e.deriction || "right" == e.deriction) && (f.css({
                    marginLeft: a + "px"
                }), d.css({
                    marginLeft: b + "px"
                }))
            },
            s = function() {
                var b = "up" == e.deriction || "down" == e.deriction ? parseInt(f.get(0).style.marginTop) : parseInt(f.get(0).style.marginLeft),
                    c = "up" == e.deriction || "down" == e.deriction ? parseInt(d.get(0).style.marginTop) : parseInt(d.get(0).style.marginLeft),
                    g = Math.max(Math.abs(b - e.pos.object), Math.abs(c - e.pos.clone)),
                    k = Math.ceil((i - g) / e.speed);
                switch (e.deriction) {
                    case "up":
                        g == i ? (r(b, c, !0), a(e._front).removeClass(e.disabled), o(!1)) : (-h >= b && (b = c + h, e.pos.object = b), -h >= c && (c = b + h, e.pos.clone = c), r(b - k, c - k));
                        break;
                    case "down":
                        g == i ? (r(b, c, !0), a(e._back).removeClass(e.disabled), o(!1)) : (b >= h && (b = c - h, e.pos.object = b), c >= h && (c = b - h, e.pos.clone = c), r(b + k, c + k));
                        break;
                    case "left":
                        g == i ? (r(b, c, !0), a(e._front).removeClass(e.disabled), o(!1)) : (-j >= b && (b = c + j, e.pos.object = b), -j >= c && (c = b + j, e.pos.clone = c), r(b - k, c - k));
                        break;
                    case "right":
                        g == i ? (r(b, c, !0), a(e._back).removeClass(e.disabled), o(!1)) : (b >= j && (b = c - j, e.pos.object = b), c >= j && (c = b - j, e.pos.clone = c), r(b + k, c + k))
                }
            };
        ("up" == e.deriction || "down" == e.deriction) && h >= e.height && h >= e.step && k(), ("left" == e.deriction || "right" == e.deriction) && j >= e.width && j >= e.step && k()
    }
});     