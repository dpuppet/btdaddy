function request_page(a) {
    window.location = a
}
function reload_page() {
    location.reload()
}
function show_message(a) {
    alert(a)
}
function show_hide_obj(a) {
    if (a.style.visibility == "visible") {
        a.style.visibility = "hidden"
    } else {
        a.style.visibility = "visible"
    }
}
function check_miner_name(a) {
    if (a.length > 15) {
        return false
    }
    reg = new RegExp("^[a-zA-Z0-9__]+$");
    if (!reg.test(a)) {
        return false
    }
    return true
}
function is_email(a) {
    var b = /^(?:[a-z\d]+[_\-\+\.]?)*[a-z\d]+@(?:([a-z\d]+\-?)*[a-z\d]+\.)+([a-z]{2,})+$/i;
    return b.test(a)
}
function check_username(c) {
    var a = /^[a-zA-Z0-9\_@.]+$/;
    if (!a.test(c)) {
        return false
    }
    return true
}
function GetBrowserType() {
    var a = "";
    if (navigator.userAgent.indexOf("MSIE") > 0) {
        return"MSIE"
    }
    if (isFirefox = navigator.userAgent.indexOf("Firefox") > 0) {
        return"Firefox"
    }
    if (isSafari = navigator.userAgent.indexOf("Safari") > 0) {
        return"Safari"
    }
    if (isCamino = navigator.userAgent.indexOf("Camino") > 0) {
        return"Camino"
    }
    if (isMozilla = navigator.userAgent.indexOf("Gecko/") > 0) {
        return"Gecko"
    }
    return"Unknow"
}
function get_element(b, a) {
    if (null == a) {
        a = 0
    }
    obj = document.getElementById(b);
    if (obj) {
        return obj
    }
    obj = document.getElementsByName(b);
    if (obj) {
        return obj[a]
    }
    return null
}
function search_child(c, a) {
    if (!c || !c.hasChildNodes()) {
        return false
    }
    if (a) {
        var b = 0;
        for (; b < c.childNodes.length; b++) {
            if (c.childNodes[b].id == a || c.childNodes[b].name == a) {
                return c.childNodes[b]
            }
            result = search_child(c.childNodes[b], a);
            if (result) {
                return result
            }
        }
    }
    return false
}
function check_number(b) {
    var a = b.keyCode;
    if (b.ctrlKey || b.altKey) {
        return true
    }
    if (!((a >= 48 && a <= 57) || (a >= 96 && a <= 105) || (a == 8) || (a == 46) || (a == 27) || (a == 37) || (a == 39) || (a == 16) || (a == 9) || (a == 33) || (a == 34) || (a == 190) || (a >= 91 && a <= 93) || (a >= 110 && a < 124))) {
        return false
    }
    return true
}

function get_max(d, c) {
    return d > c ? d : c
}
function get_min(d, c) {
    return d < c ? d : c
}
function cut_float_zero(b) {
    var a = b.indexOf(".");
    if (a < 0) {
        return b
    }
    var d = b.length;
    for (var c = b.length - 1; c >= a; c--) {
        if (b[c] == "0" || b[c] == ".") {
            d = c
        } else {
            break
        }
    }
    return b.substr(0, d)
}
function num_need_fix(b, c) {
    b = b.toString();
    var a = b.indexOf(".");
    if (a < 0) {
        return false
    }
    if (b.length - a - 1 > c) {
        if (c > 0) {
            return b.substr(0, a + c + 1)
        } else {
            return b.substr(0, a)
        }
    }
    return false
}
function num_fix(b, c) {
    b = Number(b).toFixed(15);
    var a = num_need_fix(b, c);
    return a ? a : b
}
function rand(b, a) {
    return Math.floor(Math.random() * a + b)
}
function page_rand() {
    return"&c=" + rand(0, 1000000)
}
function get_view_symbol(b, c) {
    var a = b.indexOf("_");
    if (a > 3) {
        return"股"
    } else {
        if (c == null || c == "l") {
            return b.substr(0, 3).toUpperCase()
        } else {
            return b.substr(4).toUpperCase()
        }
    }
}
function trim(c) {
    for (var b = 0; b < c.length && c.charAt(b) == " "; b++) {
    }
    for (var a = c.length; a > 0 && c.charAt(a - 1) == " "; a--) {
    }
    if (b > a) {
        return""
    }
    return c.substring(b, a)
}
function fireEvent(b, c) {
    if (document.createEventObject) {
        var a = document.createEventObject();
        return b.fireEvent("on" + c, a)
    } else {
        var a = document.createEvent("HTMLEvents");
        a.initEvent(c, true, true);
        return!b.dispatchEvent(a)
    }
}
function get_query_string(a) {
    if (location.href.indexOf("?") == -1 || location.href.indexOf(a + "=") == -1) {
        return""
    }
    var g = location.href.substring(location.href.indexOf("?") + 1);
    var d = g.split("<!@!>");
    var f, e, c;
    for (var b = 0; b < d.length; b++) {
        f = d[b].indexOf("=");
        if (f == -1) {
            continue
        }
        e = d[b].substring(0, f);
        c = d[b].substring(f + 1);
        if (e == a) {
            return unescape(c.replace(/\+/g, " "))
        }
    }
    return""
}
function trade_operator(b, h, f, i, a, d, e) {
    i = typeof i !== "undefined" ? i : 0;
    a = typeof a !== "undefined" ? a : 6;
    d = typeof d !== "undefined" ? d : 6;
    e = typeof e !== "undefined" ? e : 6;
    var c = {server_mark_ex: b, server_mark_cc: b};
    var g = {last_group_sec: 1800, last_range_hour: 1, rates_mark_ex: b, recheck_main_rates: function () {
        if (c.server_mark_ex > g.rates_mark_ex) {
            g.reload_main_rates();
            g.rates_mark_ex = c.server_mark_ex
        }
    }, reload_main_rates: function (m, p, n) {
        m = typeof m !== "undefined" ? m : g.last_group_sec;
        p = typeof p !== "undefined" ? p : g.last_range_hour;
        n = typeof n !== "undefined" ? n : false;
        g.last_group_sec = m;
        g.last_range_hour = m * 2 / 3600;
        var o = {symbol: h, group_sec: m, range_hour: p, type: "tline"};
        $.ajax({type: "post", url: "/json_svr/query/?u=1" + page_rand(), data: o, success: function (w, v) {
            var u = w;
            if (u && u.result) {
                if (n) {
                    trade_global.time_line = u.tline
                } else {
                    for (var s = 0; s < u.tline.length; s++) {
                        var x = u.tline[s];
                        if (x[0] == trade_global.time_line[trade_global.time_line.length - 1][0]) {
                            trade_global.time_line[trade_global.time_line.length - 1] = x
                        } else {
                            if (x[0] > trade_global.time_line[trade_global.time_line.length - 1][0]) {
                                trade_global.time_line.push(x)
                            }
                        }
                    }
                }
                var q = trade_global.time_line;
                var r = [];
                var t = [];
                for (s = 0; s < q.length; s++) {
                    r.push([q[s][0], q[s][2], q[s][3], q[s][4], q[s][5]]);
                    t.push([q[s][0], q[s][1]])
                }
                trade_global.main_chart.series[1].setData(r, true);
                trade_global.main_chart.series[0].setData(t, true);
                trade_global.main_chart.redraw();
                if (n) {
                    trade_global.main_chart.zoomOut()
                }
            }
        }})
    }, recheck: function () {
        g.recheck_main_rates(h)
    }};
    var l = {ask_list: [], bid_list: [], best_ask_rate: 0, best_bid_rate: 0, ab_mark_cc: b, ab_mark_ex: b, init: function (n, m) {
        this.best_ask_rate = m;
        this.best_bid_rate = n;
        j.update_best_rate_and_input()
    }, recalc_best_rate: function () {
        j.update_best_rate()
    }, reload_ask_bid_grid: function () {
        jQuery("#tableAskList").clearGridData();
        jQuery("#tableBidList").clearGridData();
        for (var m = 0; m < this.ask_list.length; m++) {
            jQuery("#tableAskList").jqGrid("addRowData", m + 1, this.ask_list[m])
        }
        jQuery("#tableAskList").setGridParam({sortname: "rate", sortorder: "asc"}).trigger("reloadGrid");
        for (var m = 0; m < this.bid_list.length; m++) {
            jQuery("#tableBidList").jqGrid("addRowData", m + 1, this.bid_list[m])
        }
        jQuery("#tableBidList").setGridParam({sortname: "rate", sortorder: "desc"}).trigger("reloadGrid")
    }, reload_ask_bid_list_table: function () {
        var m = {type: "ask_bid_list_table", symbol: h};
        $.ajax({type: "post", url: "/json_svr/query/?u=1" + page_rand(), data: m, success: function (p, o) {
            var n = p;
            if (n && n.result) {
                l.best_ask_rate = n.bid_rate0;
                l.best_bid_rate = n.ask_rate0;
                get_element("divAskList").innerHTML = n.ask_list_table;
                get_element("divBidList").innerHTML = n.bid_list_table
            } else {
                l.ab_mark_ex = 0;
                l.ab_mark_cc = 0
            }
            j.update_best_rate()
        }});
        return true
    }, reload_ask_bid_list: function () {
        var m = {type: "ask_bid_list", symbol: h};
        $.ajax({type: "post", url: "/json_svr/query/?u=1" + page_rand(), data: m, success: function (p, o) {
            var n = p;
            if (n && n.result) {
                l.ask_list = n.asks;
                l.bid_list = n.bids;
                l.reload_ask_bid_grid()
            } else {
                l.ab_mark_ex = 0;
                l.ab_mark_cc = 0
            }
            j.update_best_rate()
        }});
        return true
    }, recheck_ask_bid_list: function () {
        if (c.server_mark_cc > l.ab_mark_cc || c.server_mark_ex > l.ab_mark_ex) {
            l.reload_ask_bid_list_table();
            l.ab_mark_ex = c.server_mark_ex;
            l.ab_mark_cc = c.server_mark_ex
        }
    }, recheck: function () {
        l.recheck_ask_bid_list()
    }};
    var k = {history_data: [], th_mark_ex: b, init: function (m) {
        this.history_data = m;
        for (var n = 0; n < this.history_data.length; n++) {
            jQuery("#tableHistoryList").jqGrid("addRowData", null, this.history_data[n])
        }
        jQuery("#tableHistoryList").setGridParam({sortname: "date", sortorder: "desc"}).trigger("reloadGrid")
    }, reload_thistory_data: function () {
        var m = {type: "ex_rec", symbol: h, count: 5};
        $.ajax({type: "post", url: "/jport?op=query" + page_rand(), data: m, success: function (r, q) {
            var p = r;
            if (p && p.result && p.history) {
                for (var o = 0; o < p.history.length; o++) {
                    var s = {};
                    s.ticket = p.history[o].ticket;
                    s.order = p.history[o].order == 1 ? "买入" : "卖出";
                    s.rate = p.history[o].rate;
                    s.amount_l = p.history[o].vol;
                    s.amount_r = s.amount_l * s.rate;
                    s.date = $.format.date(p.history[o].date * 1000, "yyyy/MM/dd HH:mm:ss");
                    for (var n = 0; n < k.history_data.length; n++) {
                        if (k.history_data[n].ticket == s.ticket) {
                            s = null;
                            break
                        }
                    }
                    if (s) {
                        k.history_data.push(s);
                        jQuery("#tableHistoryList").jqGrid("addRowData", null, s)
                    }
                }
                jQuery("#tableHistoryList").setGridParam({sortname: "date", sortorder: "desc"}).trigger("reloadGrid");
                j.update_new_rate()
            } else {
                k.th_mark_ex = 0
            }
        }})
    }, recheck_thistory_data: function () {
        if (c.server_mark_ex > k.th_mark_ex) {
            k.reload_thistory_data();
            k.th_mark_ex = c.server_mark_ex
        }
    }, recheck: function () {
        k.recheck_thistory_data()
    }};
    var j = {fee: i * 0.01, on_request_ask_bid: function (q, p, o) {
        if (!f || f < 0) {
            apprise("请您先登录再进行操作", {animate: false, textOk: "确定"});
            return
        }
        if (o <= 0) {
            apprise("请输入交易量", {animate: false, textOk: "确定"});
            return
        }
        if (p >= 1000000000 || p == 0) {
            apprise("交易价必须大于0且小于10亿", {animate: false, textOk: "确定"});
            return
        }
        if (o >= 1000000000) {
            apprise("单笔交易量必须小于10亿", {animate: false, textOk: "确定"});
            return
        }
        if (q == "ask") {
            var n = get_element("balance_ask_able").innerHTML;
            if (p * o > Number(n)) {
                apprise("超出可买入额，请检查后重新输入", {animate: false, textOk: "确定"});
                return
            }
        } else {
            if (q == "bid") {
                var m = get_element("balance_bid_able").innerHTML;
                if (o > Number(m)) {
                    apprise("超出可卖出额，请检查后重新输入", {animate: false, textOk: "确定"});
                    return
                }
            } else {
                apprise("无效交易类型", {animate: false, textOk: "确定"});
                return
            }
        }
        var r = "";
        if (q == "ask") {
            r += "请确认买入订单:";
            r += "<hr/><br>";
            r += "<table id='tablePending'>";
            r += "<tr><td>买入价格: </td><td>" + num_fix(p, a) + "</td></tr>";
            r += "<tr><td>买入数量: </td><td>" + num_fix(o, d) + "&nbsp;" + get_view_symbol(h, "l") + "</td></tr>"
        } else {
            if (q == "bid") {
                r += "请确认卖出订单:";
                r += "<hr/><br>";
                r += "<table id='tablePending'>";
                r += "<tr><td>卖出价格: </td><td>" + num_fix(p, a) + "</td></tr>";
                r += "<tr><td>卖出数量: </td><td>" + num_fix(o, d) + "&nbsp;" + get_view_symbol(h, "l") + "</td></tr>"
            }
        }
        r += "</table>";
        r += "<br/>";
        apprise(r, {animate: false, confirm: true, textOk: "确定下单", textCancel: "取消"}, function (s) {
            if (s) {
                j.on_request_ask_bid_confirmed(q, p, o)
            } else {
                return
            }
        })
    }, on_request_ask_bid_confirmed: function (q, p, o) {
        if (!f || f < 0) {
            apprise("请您先登录再进行操作", {animate: false, textOk: "确定"});
            return
        }
        if (o <= 0) {
            apprise("请输入交易量", {animate: false, textOk: "确定"});
            return
        }
        if (p >= 1000000000 || p == 0) {
            apprise("交易价必须大于0且小于10亿", {animate: false, textOk: "确定"});
            return
        }
        if (o >= 1000000000) {
            apprise("单笔交易量必须小于10亿", {animate: false, textOk: "确定"});
            return
        }
        if (q == "ask") {
            var n = get_element("balance_ask_able").innerHTML;
            if (p * o > Number(n)) {
                apprise("超出可买入额，请检查后重新输入", {animate: false, textOk: "确定"});
                return
            }
        } else {
            if (q == "bid") {
                var m = get_element("balance_bid_able").innerHTML;
                if (o > Number(m)) {
                    apprise("超出可卖出额，请检查后重新输入", {animate: false, textOk: "确定"});
                    return
                }
            } else {
                apprise("无效交易类型", {animate: false, textOk: "确定"});
                return
            }
        }
        var r = apprise("<img style='margin: 65px;' src='/images/loading.gif'/>", {animate: false, buttonShow: false, textOk: "确定"});
        var s = {type: q, symbol: h, rate: p, vol: o};
        $.ajax({type: "post", url: "/json_svr/exchange/?u=1" + page_rand(), data: s, success: function (v, x) {
            var u = v;
            var w = "";
            if (u.records) {
                w += "已成交:";
                w += "<hr/>";
                w += "<table id='tableRecords'>";
                for (var t = 0; t < u.records.length; t++) {
                    w += "<tr><td>";
                    w += (t + 1) + ".</td><td>";
                    w += "成交价: " + num_fix(u.records[t].rate, a) + "</td>";
                    if (q == "ask") {
                        w += "<td>成交量: </td><td>" + num_fix(u.records[t].vol, d) + "&nbsp;" + get_view_symbol(h, "l") + "</td></td>";
                        w += "<td>手续费: </td><td>" + num_fix(u.records[t].vol * u.records[t].rate * j.fee, 8) + "&nbsp;" + get_view_symbol(h, "r") + "</td></tr>"
                    } else {
                        if (q == "bid") {
                            w += "<td>成交量: </td><td>" + num_fix(u.records[t].vol, d) + "&nbsp;" + get_view_symbol(h, "l") + "</td></td>";
                            w += "<td>手续费: </td><td>" + num_fix(u.records[t].vol * j.fee, 8) + "&nbsp;" + get_view_symbol(h, "l") + "</td></tr>"
                        }
                    }
                }
                w += "</table>";
                w += "<br/>"
            }
            if (u.pending) {
                w += "已挂单:";
                w += "<hr/>";
                w += "<table id='tablePending'>";
                for (var t = 0; t < u.pending.length; t++) {
                    w += "<tr><td colspan='2'>单号: </td><td>" + u.pending[t].id + "</td></tr>";
                    w += "<tr><td>价格: </td><td>" + num_fix(u.pending[t].rate, a) + "</td></tr>";
                    if (q == "ask") {
                        w += "<tr><td>挂单量: </td><td>" + num_fix(u.pending[t].vol, d) + "&nbsp;" + get_view_symbol(h, "l") + "</td></tr>";
                        w += "<tr><td>手续费: </td><td>" + num_fix(u.pending[t].vol * u.pending[t].rate * j.fee, 8) + "&nbsp;" + get_view_symbol(h, "r") + "</td></tr>"
                    } else {
                        if (q == "bid") {
                            w += "<tr><td>挂单量: </td><td>" + num_fix(u.pending[t].vol, d) + "&nbsp;" + get_view_symbol(h, "l") + "</td></tr>";
                            w += "<tr><td>手续费: </td><td>" + num_fix(u.pending[t].vol * j.fee, 8) + "&nbsp;" + get_view_symbol(h, "l") + "</td></tr>"
                        }
                    }
                }
                w += "</table>";
                w += "<br/>"
            }
            if (!u.result) {
                w = "处理失败，请稍候再试！原因是：" + u.msg
            }
            apprise(w, {animate: true, textOk: "确定"}, function (y) {
                if (y) {
                    reload_page()
                }
            })
        }, error: function () {
            apprise("网络错误: " + req.status + " " + req.responseText + " " + status + " " + err, {animate: true, textOk: "确定"}, function (t) {
                if (t) {
                    reload_page()
                }
            })
        }, complete: function () {
            r.close()
        }})
    }, recalc_fee: function (q) {
        if (q == "ask") {
            var p = get_element("ask_vol").value;
            var s = get_element("ask_rate").value;
            var o = get_element("ask_amount").value;
            get_element("ask_fee").innerHTML = cut_float_zero(num_fix(o * this.fee, 8));
            if (get_element("ask_fee").innerHTML.length > 11) {
                get_element("ask_fee").innerHTML = get_element("ask_fee").innerHTML.substr(0, 11)
            }
        } else {
            var r = get_element("bid_vol").value;
            var m = get_element("bid_rate").value;
            var n = get_element("bid_amount").value;
            get_element("bid_fee").innerHTML = cut_float_zero(num_fix(r * this.fee, 8));
            if (get_element("bid_fee").innerHTML.length > 11) {
                get_element("bid_fee").innerHTML = get_element("bid_fee").innerHTML.substr(0, 11)
            }
        }
        return true
    }, on_input_ask_vol: function () {
        var m = get_element("ask_amount");
        var q = get_element("ask_rate");
        var o = get_element("ask_vol");
        var p = num_need_fix(o.value, d);
        if (p) {
            o.value = p
        }
        var n = q.value * o.value;
        m.value = n;
        if (m.value < 0.0001) {
            m.value = 0
        }
        var p = num_need_fix(m.value, e);
        if (p) {
            m.value = p
        }
        return true
    }, on_input_bid_vol: function () {
        var m = get_element("bid_amount");
        var q = get_element("bid_rate");
        var o = get_element("bid_vol");
        var p = num_need_fix(o.value, d);
        if (p) {
            o.value = p
        }
        var n = q.value * o.value;
        m.value = n;
        var p = num_need_fix(n, e);
        if (p) {
            m.value = p
        }
        return true
    }, on_input_ask_rate: function () {
        var p = get_element("ask_rate");
        var o = num_need_fix(p.value, a);
        if (o) {
            p.value = o
        }
        var n = get_element("balance_ask_able");
        var m = get_element("amount_ask_able");
        m.innerHTML = num_fix(n.innerHTML / p.value, d);
        j.on_input_ask_vol()
    }, on_input_bid_rate: function () {
        var n = get_element("bid_rate");
        var m = num_need_fix(n.value, a);
        if (m) {
            n.value = m
        }
        j.on_input_bid_vol()
    }, on_input_ask_amount: function () {
        var m = get_element("ask_amount");
        var q = get_element("ask_rate");
        var o = get_element("ask_vol");
        var p = num_need_fix(m.value, e);
        if (p) {
            m.value = p
        }
        var n = m.value * 10000 / q.value;
        if (n < 0.01) {
            n = 0
        }
        n /= 10000;
        o.value = n;
        var p = num_need_fix(n, d);
        if (p) {
            o.value = p
        }
    }, on_input_bid_amount: function () {
        var m = get_element("bid_amount");
        var q = get_element("bid_rate");
        var o = get_element("bid_vol");
        var p = num_need_fix(m.value, e);
        if (p) {
            m.value = p
        }
        var n = m.value * 10000 / q.value;
        if (n < 0.01) {
            n = 0
        }
        n /= 10000;
        o.value = n;
        var p = num_need_fix(n, d);
        if (p) {
            o.value = p
        }
        return true
    }, cancel_order: function (o, m) {
        var n = apprise("<img style='margin: 65px;' src='/images/loading.gif'/>", {animate: false, buttonShow: false, textOk: "确定"});
        var p = {type: "cancel", symbol: o, oid: m};
        $.ajax({type: "post", url: "/json_svr/exchange/?u=1" + page_rand(), data: p, success: function (r, s) {
            var q = r;
            if (q && q.result) {
                view_code = "<div class='cancel_content'>撤单成功!</div>"
            } else {
                view_code = "<div class='cancel_content'>撤单失败! " + q.msg + "</div>"
            }
            apprise(view_code, {animate: true, textOk: "确定"}, function (t) {
                if (t) {
                    reload_page()
                }
            })
        }, error: function () {
            apprise("网络错误！", {animate: true, textOk: "确定"}, function (q) {
                if (q) {
                    reload_page()
                }
            })
        }, complete: function () {
            n.close()
        }})
    }, update_new_rate: function () {
        var n = 0;
        var r = 99999;
        var u = 0;
        var q = 0;
        if (trade_global && trade_global.time_line) {
            for (var p = 0; p < trade_global.time_line.length; p++) {
                tnode = trade_global.time_line[p];
                n = get_max(n, Number(tnode[2]));
                r = get_min(r, Number(tnode[3]));
                u = Number(tnode[4]);
                q += Number(tnode[5])
            }
        }
        var m = get_element("pb_close");
        var t = get_element("pb_high");
        var s = get_element("pb_low");
        var o = get_element("pb_vol");
        m.innerHTML = u.toFixed(a);
        t.innerHTML = n.toFixed(a);
        s.innerHTML = r.toFixed(a);
        o.innerHTML = q.toFixed(d)
    }, update_best_rate: function () {
        get_element("rate_best_ask").innerHTML = num_fix(Number(l.best_bid_rate + 4e-8).toFixed(10), a);
        get_element("rate_best_bid").innerHTML = num_fix(Number(l.best_ask_rate + 4e-8).toFixed(10), a);
        this.update_able_amount()
    }, update_best_rate_and_input: function () {
        get_element("rate_best_ask").innerHTML = num_fix(Number(l.best_bid_rate + 4e-8).toFixed(10), a);
        get_element("rate_best_bid").innerHTML = num_fix(Number(l.best_ask_rate + 4e-8).toFixed(10), a);
        get_element("ask_rate").value = get_element("rate_best_ask").innerHTML;
        get_element("bid_rate").value = get_element("rate_best_bid").innerHTML;
        this.update_able_amount()
    }, update_able_amount: function () {
        var p = l.best_ask_rate;
        var t = l.best_bid_rate;
        var r = get_element("balance_ask_able").innerHTML;
        var q = get_element("balance_bid_able").innerHTML;
        var o = r / t;
        var m = q * p;
        var s = get_element("amount_ask_able").innerHTML;
        var n = get_element("amount_bid_able").innerHTML;
        if (s <= 0 || get_element("rate_best_ask").innerHTML != num_fix(Number(l.best_bid_rate + 4e-8).toFixed(10), a)) {
            get_element("amount_ask_able").innerHTML = num_fix(Number(o).toFixed(10), d)
        }
        if (s <= 0 || get_element("rate_best_bid").innerHTML != num_fix(Number(l.best_ask_rate + 4e-8).toFixed(10), a)) {
            get_element("amount_bid_able").innerHTML = num_fix(Number(m).toFixed(10), e)
        }
    }};
    return{main_rate: g, main_ask_bid_list: l, main_history_grid: k, obj: j, run: function () {
        $.timer(function () {
            var m = {type: "time_mark", symbol: h, mtype: "ex_cc"};
            $.ajax({type: "post", url: "/json_svr/query/?u=1" + page_rand(), data: m, success: function (p, o) {
                var n = p;
                if (n && n.result) {
                    c.server_mark_ex = n.mark_ex;
                    c.server_mark_cc = n.mark_cc;
                    l.recheck()
                }
            }})
        }, 10000, true)
    }}
};