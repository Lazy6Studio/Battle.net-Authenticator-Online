function processbarload(e) {
    i = 0, i = 100 * e / 30, s = 0, showload = setInterval("processbarsetload()", delaytime)
}
function processbarsetload() {
    i += .1, s += .1, i >= 100 && clearInterval(showload), s >= 6 && (document.getElementById("rightajaxzhuangtai").innerHTML = ""), document.getElementById("authprogressbar").style.width = i + "%"
}
function createXHR() {
    window.ActiveXObject ? XHR = new ActiveXObject("Microsoft.XMLHTTP") : window.XMLHttpRequest && (XHR = new XMLHttpRequest)
}
function getauthjsondata() {
    isAuthCodeLoading = true, createXHR(), $("#authcode").fadeOut(500), document.getElementById("rightajaxzhuangtai").innerHTML = "获取下一组令牌中&nbsp;<img class='getCodeStateImage' src='/resources/img/waiting.gif'/>", XHR.open("GET", geturladd, !0), XHR.onreadystatechange = jsonchuli, XHR.send(null)
}
function jsonchuli() {
    isAuthCodeLoading = false;
    clearInterval(showload);
    if (XHR.readyState === 4 && XHR.status === 200) {
        var textHTML = XHR.responseText, jsondata = eval("(" + textHTML + ")");
        document.getElementById("authcode").innerHTML = jsondata.code, $("#authcode").fadeIn(500);
        if (jsondata.success) {
            $("#creation-submit").attr("data-clipboard-text", jsondata.code);
            if (!Clipboard.isSupported()) {
                clip.setText(document.getElementById("authcode").innerHTML)
            }
            processbarload(jsondata.time), document.getElementById("rightajaxzhuangtai").innerHTML = "获取成功&nbsp;<img class='getCodeStateImage' src='/resources/img/success.png'/>", timedelay = setTimeout("getauthjsondata()", (delaytime - jsondata.time) * 1e3)
        } else {
            $("#creation-submit").attr("data-clipboard-text", "");
            document.getElementById("rightajaxzhuangtai").innerHTML = "获取失败,请重试&nbsp;<img class='getCodeStateImage' src='/resources/img/warning2.png'/>"
        }
    } else {
        $("#creation-submit").attr("data-clipboard-text", "");
        document.getElementById("rightajaxzhuangtai").innerHTML = "获取失败,请重试&nbsp;<img class='getCodeStateImage' src='/resources/img/warning2.png'/>"
    }
}
function refreshcodegeas() {
    if (isAuthCodeLoading) {
        return;
    }
    isAuthCodeLoading = true;
    $("#creation-submit").attr("data-clipboard-text", "");
    clearInterval(showload), clearTimeout(timedelay), createXHR(), $("#authcode").fadeOut(500), document.getElementById("rightajaxzhuangtai").innerHTML = "正在刷新令牌验证码&nbsp;<img class='getCodeStateImage' src='/resources/img/waiting.gif'/>", XHR.open("GET", geturladd, !0), XHR.onreadystatechange = jsonchuli, XHR.send(null)
}
function init() {
    if (Clipboard.isSupported()) {
        var clipboard = new Clipboard(document.getElementById('creation-submit'));
        clipboard.on('success', function (e) {
            document.getElementById("copydatamode").innerHTML = "已复制到剪切板&nbsp;<img class='getCodeStateImage' src='/resources/img/success.png'/>", setTimeout("document.getElementById('copydatamode').innerHTML=''", 2e3)
        });
        clipboard.on('error', function (e) {
            document.getElementById("copydatamode").innerHTML = "复制到剪切板失败&nbsp;<img class='getCodeStateImage' src='/resources/img/warning2.png'/>", setTimeout("document.getElementById('copydatamode').innerHTML=''", 2e3)
        });
        $("#creation-submit").click(function () {
            errorText = document.getElementById("rightajaxzhuangtai").innerHTML;
            if (((errorText.indexOf("获取下一组令牌中") >= 0 && errorText.indexOf("/resources/img/waiting.gif") > 0)
                || (errorText.indexOf("正在刷新令牌验证码") >= 0 && errorText.indexOf("/resources/img/waiting.gif") > 0)
                ||
                (
                    (errorText === "LOADNOW") && !(errorText.indexOf("获取失败,请重试") >= 0 && errorText.indexOf("/resources/img/warning2.png") > 0)
                ))) {
                document.getElementById("copydatamode").innerHTML = "请等待验证码刷新&nbsp;<img class='getCodeStateImage' src='/resources/img/warning2.png'/>", setTimeout("document.getElementById('copydatamode').innerHTML=''", 2e3)
                return;
            }
            if (errorText.indexOf("获取失败,请重试") >= 0 && errorText.indexOf("/resources/img/warning2.png") > 0) {
                document.getElementById("copydatamode").innerHTML = "请刷新验证码&nbsp;<img class='getCodeStateImage' src='/resources/img/warning2.png'/>", setTimeout("document.getElementById('copydatamode').innerHTML=''", 2e3)
            }
        });
        return;
    }
    ZeroClipboard.setMoviePath("/resources/swf/ZeroClipboard.swf"), clip = new ZeroClipboard.Client, clip.setHandCursor(!0), clip.addEventListener("mouseOver", my_mouse_over), clip.glue("creation-submit"), clip.addEventListener("mouseUp", my_mouse_up)
}
function my_mouse_over(e) {
    errorText = document.getElementById("rightajaxzhuangtai").innerHTML;
    if ((errorText.indexOf("获取失败,请重试") >= 0 && errorText.indexOf("/resources/img/warning2.png") > 0)
        || (errorText.indexOf("获取下一组令牌中") >= 0 && errorText.indexOf("/resources/img/waiting.gif") > 0)
        || (errorText.indexOf("正在刷新令牌验证码") >= 0 && errorText.indexOf("/resources/img/waiting.gif") > 0)
        || (errorText === "LOADNOW")) {
        return;
    }
    if (document.getElementById("authcode").innerHTML.length > 0) {
        clip.setText(document.getElementById("authcode").innerHTML)
    }
}
function my_mouse_up(e) {
    errorText = document.getElementById("rightajaxzhuangtai").innerHTML;
    if (((errorText.indexOf("获取下一组令牌中") >= 0 && errorText.indexOf("/resources/img/waiting.gif") > 0)
        || (errorText.indexOf("正在刷新令牌验证码") >= 0 && errorText.indexOf("/resources/img/waiting.gif") > 0)
        ||
        (
            (errorText === "LOADNOW") && !(errorText.indexOf("获取失败,请重试") >= 0 && errorText.indexOf("/resources/img/warning2.png") > 0)
        ))) {
        document.getElementById("copydatamode").innerHTML = "请等待验证码刷新&nbsp;<img class='getCodeStateImage' src='/resources/img/warning2.png'/>", setTimeout("document.getElementById('copydatamode').innerHTML=''", 2e3)
        return;
    }
    if (errorText.indexOf("获取失败,请重试") >= 0 && errorText.indexOf("/resources/img/warning2.png") > 0) {
        document.getElementById("copydatamode").innerHTML = "请刷新验证码&nbsp;<img class='getCodeStateImage' src='/resources/img/warning2.png'/>", setTimeout("document.getElementById('copydatamode').innerHTML=''", 2e3)
        return;
    }
    if (document.getElementById("authcode").innerHTML.length > 0) {
        document.getElementById("copydatamode").innerHTML = "已复制到剪切板&nbsp;<img class='getCodeStateImage' src='/resources/img/success.png'/>", setTimeout("document.getElementById('copydatamode').innerHTML=''", 2e3)
    }
}
var i = 0, s = 0, jqmode = 0, delaytime = 30.5, timedelay, XHR, isAuthCodeLoading;
$(window).load(function () {
    showload = showload = setInterval("processbarsetload()", 1e7), getauthjsondata(), init()
});
var clip = null;
$(document).ready(function () {
    $("#aforwowjq").click(function () {
        jqmode == 0 ? ($("#game-list-wow").outerHeight(!0) > 280 && $("#layout-middle").animate({height: $("#layout-middle").outerHeight(!0) + $("#game-list-wow").outerHeight(!0) - 300}), jqmode = 1) : ($("#game-list-wow").outerHeight(!0) > 280 && $("#layout-middle").animate({height: $("#layout-middle").outerHeight(!0) - $("#game-list-wow").outerHeight(!0) + 286}), jqmode = 0)
    })
})