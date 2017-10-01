function createXHR() {
    window.ActiveXObject ? XHR = new ActiveXObject("Microsoft.XMLHTTP") : window.XMLHttpRequest && (XHR = new XMLHttpRequest)
}
function authsync(e) {
    e != "" && e != null ? (createXHR(), authid = e, document.getElementById("authsyncbutton" + authid).disabled = "disabled", document.getElementById("jiaochenshijian" + authid).innerHTML = "正在校正", XHR.open("GET", "/api/auth/doSync?authId=" + authid, !0), XHR.onreadystatechange = syncreceive, XHR.send(null)) : (document.getElementById("authsyncbutton" + authid).disabled = "", document.getElementById("jiaochenshijian" + authid).innerHTML = "校正时间")
}
function syncreceive() {
    if (XHR.readyState == 4 && XHR.status == 200) {
        var e = XHR.responseText;
        (e == null || e == "" || e == "false") ? (document.getElementById("jiaochenshijian" + authid).innerHTML = "校正失败", document.getElementById("authsyncbutton" + authid).disabled = "", setTimeout("showtxtjiaochenshijian()", 1500)) : (document.getElementById("jiaochenshijian" + authid).innerHTML = "校正成功", document.getElementById("authshangcitongbushijian" + authid).innerHTML = e, document.getElementById("authsyncbutton" + authid).disabled = "", setTimeout("showtxtjiaochenshijian()", 1500))
    }
}
function showtxtjiaochenshijian() {
    document.getElementById("jiaochenshijian" + authid).innerHTML = "校正时间"
}
function createXHRAX() {
    window.ActiveXObject ? XHRAX = new ActiveXObject("Microsoft.XMLHTTP") : window.XMLHttpRequest && (XHRAX = new XMLHttpRequest)
}
function authmoren(e) {
    e != "" && e != null ? (createXHRAX(), authids = e, document.getElementById("morenauthbutton" + authids).disabled = "disabled", document.getElementById("morenanquanlin" + authids).innerHTML = "正在设置", XHRAX.open("GET", "/api/auth/setDefault?authId=" + authids, !0), XHRAX.onreadystatechange = morenreceive, XHRAX.send(null)) : (document.getElementById("morenauthbutton" + authids).disabled = "disabled", document.getElementById("morenanquanlin" + authids).innerHTML = "设置默认")
}
function morenreceive() {
    if (XHRAX.readyState == 4 && XHRAX.status == 200) {
        var textHTML = XHRAX.responseText, jsondata = eval("(" + textHTML + ")");
        jsondata.result == "0" ? (document.getElementById("morenanquanlin" + authids).innerHTML = "设置失败", document.getElementById("morenauthbutton" + authids).disabled = "", setTimeout("showtxtshezhimoren()", 1500)) : (document.getElementById("morenanquanlin" + authids).innerHTML = "设置成功", document.getElementById("morenauthbutton" + authids).disabled = "disabled", document.getElementById("morenanquanlin" + jsondata.oldmorenauthid).innerHTML = "设置默认", document.getElementById("morenauthbutton" + jsondata.oldmorenauthid).disabled = "", document.getElementById("morenpicspan" + authids).innerHTML = "<img class='morenauthleftpic' src='/resources/img/moren.png' alt=''>", document.getElementById("morenpicspan" + jsondata.oldmorenauthid).innerHTML = "", document.getElementById("morenanquanlin" + authids).innerHTML = "已为默认", serverauthmorenid = authids)
    }
}
function showtxtshezhimoren() {
    document.getElementById("morenanquanlin" + authids).innerHTML = "设置默认", document.getElementById("morenauthbutton" + authids).disabled = ""
}
function createXHRL() {
    window.ActiveXObject ? XHRL = new ActiveXObject("Microsoft.XMLHTTP") : window.XMLHttpRequest && (XHRL = new XMLHttpRequest)
}
function authdelete(e) {
    e != "" && e != null ? (createXHRL(), authids = e, document.getElementById("authdelbutton" + authids).disabled = "disabled", document.getElementById("shanchuauth" + authids).innerHTML = "正在删除", XHRL.open("GET", "/api/auth/deleteAuth?authId=" + authids, !0), XHRL.onreadystatechange = deletereceive, XHRL.send(null)) : (document.getElementById("authdelbutton" + authids).disabled = "", document.getElementById("shanchuauth" + authids).innerHTML = "确认删除")
}
function deletereceive() {
    if (XHRL.readyState == 4 && XHRL.status == 200) {
        var textHTML = XHRL.responseText, jsondata = eval("(" + textHTML + ")");
        jsondata.result == 0 ? (document.getElementById("shanchuauth" + authids).innerHTML = "删除失败", document.getElementById("authdelbutton" + authids).disabled = "", setTimeout("showtxtshanchu()", 1500)) : jsondata.oldmorendeleted == 0 ? (document.getElementById("shanchuauth" + authids).innerHTML = "删除成功", document.getElementById("youshangfangtianjiaABC").innerHTML = "<a class='ui-button button1' href='addauth.php'><span class='button-left'><span class='button-right'>添加一个安全令</span></span></a>", document.getElementById("authdelbutton" + authids).disabled = "", $("#henxiangtr" + authids).animate({marginBottom: "toggle"}, {duration: 1500})) : jsondata.newmorenid > 0 ? (document.getElementById("morenpicspan" + jsondata.deleteauid).innerHTML = "", document.getElementById("morenpicspan" + jsondata.newmorenid).innerHTML = "<img class='morenauthleftpic' src='/resources/img/moren.png' alt=''>", document.getElementById("shanchuauth" + authids).innerHTML = "删除成功", document.getElementById("youshangfangtianjiaABC").innerHTML = "<a class='ui-button button1' href='addauth.php'><span class='button-left'><span class='button-right'>添加一个安全令</span></span></a>", document.getElementById("authdelbutton" + authids).disabled = "", $("#henxiangtr" + authids).animate({marginBottom: "toggle"}, {duration: 1500})) : (document.getElementById("shanchuauth" + authids).innerHTML = "删除成功", document.getElementById("youshangfangtianjiaABC").innerHTML = "<a class='ui-button button1' href='addauth.php'><span class='button-left'><span class='button-right'>添加一个安全令</span></span></a>", document.getElementById("authdelbutton" + authids).disabled = "", $("#henxiangtr" + authids).animate({marginBottom: "toggle"}, {duration: 1500}))
    }
}
function showtxtshanchu() {
    document.getElementById("shanchuauth" + authid).innerHTML = "确认删除"
}
function ShowElement(e, t) {
    if (e.innerHTML.includes('/resources/img/unavailable.png') && e.innerHTML.includes('设置失败,请重试')) {
        return;
    }
    if (e.innerHTML.includes('/resources/img/waiting.gif') && e.innerHTML.includes('正在设置新名称')) {
        return;
    }
    if (e.innerHTML.includes('/resources/img/warning2.png') && e.innerHTML.includes('名称必须小于12位')) {
        return;
    }
    if (e.innerHTML.includes('/resources/img/success.png') && e.innerHTML.includes('设置成功')) {
        return;
    }
    oldhtml = e.innerHTML, timeclick || (oldname = oldhtml, timeclick = !0);
    var n = document.createElement("input");
    n.type = "text", n.id = "xiugaiminzitxtinput", n.value = oldname, n.onblur = function () {
        timeclick = !1;
        if (oldname != n.value && n.value != "" && n.value.length <= 12) e.innerHTML = "正在设置新名称<img width=12 heigth=12 src='/resources/img/waiting.gif' class='setNewNameImage'>", newhtml = n.value, setnewname(n.value, t); else if (n.value.length > 12) {
            e.innerHTML = "名称必须小于12位<img width=12 heigth=12 src='/resources/img/warning2.png' class='setNewNameImage'>";
            var r = e;
            setTimeout("showleftright('" + t + "')", 1e3)
        } else e.innerHTML = oldname
    }, n.onkeypress = function (r) {
        if (r.keyCode == 13)if (oldhtml != n.value && n.value != "" && n.value.length <= 12) e.innerHTML = "正在设置新名称<img width=12 heigth=12 src='/resources/img/waiting.gif'  class='setNewNameImage'>", newhtml = n.value, setnewname(n.value, t); else if (n.value.length > 12) {
            e.innerHTML = "名称必须小于12位<img width=12 heigth=12 src='/resources/img/warning2.png' class='setNewNameImage'>";
            var i = e;
            setTimeout("showleftright('" + t + "')", 1e3)
        } else e.innerHTML = oldhtml;
        r.keyCode == 27 && (e.innerHTML = oldhtml)
    }, e.innerHTML = "", e.appendChild(n), n.focus()
}
function showleftright(e) {
    document.getElementById("authnamecode" + e).innerHTML = oldhtml
}
function showokright(e) {
    document.getElementById("authnamecode" + e).innerHTML = newhtml
}
function createXHRAY() {
    window.ActiveXObject ? XHRAY = new ActiveXObject("Microsoft.XMLHTTP") : window.XMLHttpRequest && (XHRAY = new XMLHttpRequest)
}
function setnewname(e, t) {
    t != "" && t != null ? (createXHRAY(), authidso = t, XHRAY.open("GET", "api/auth/changeName?authId=" + authidso + "&authName=" + encodeURI(e), !0), XHRAY.onreadystatechange = authnamereceive, XHRAY.send(null)) : document.getElementById("authnamecode" + authidso).innerHTML = oldhtml
}
function authnamereceive() {
    if (XHRAY.readyState != 4) {
        return;
    }
    if (XHRAY.status == 200) {
        var e = XHRAY.responseText;
        e == "false" ? (document.getElementById("authnamecode" + authidso).innerHTML = "设置失败,请重试<img width=12 heigth=12 src='/resources/img/unavailable.png' class='setNewNameErrorImage'>", setTimeout("showleftright('" + authidso + "')", 2e3)) : (document.getElementById("authnamecode" + authidso).innerHTML = "设置成功<img width=12 heigth=12 src='/resources/img/success.png' class='setNewNameErrorImage'>", setTimeout("showokright('" + authidso + "')", 2e3))
    } else {
        document.getElementById("authnamecode" + authidso).innerHTML = "设置失败,请重试<img width=12 heigth=12 src='/resources/img/unavailable.png' class='setNewNameErrorImage'>", setTimeout("showleftright('" + authidso + "')", 2e3)
    }
}
var XHR, authid, XHRAX, authids, XHRL, authids, oldhtml, newhtml;
timeclick = !1;
var XHRAY, authidso;