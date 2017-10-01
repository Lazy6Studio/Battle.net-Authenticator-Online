function createXHR() {
    window.ActiveXObject ? XHR = new ActiveXObject("Microsoft.XMLHTTP") : window.XMLHttpRequest && (XHR = new XMLHttpRequest)
}
function renewemail() {
    createXHR(), document.getElementById("mailcheck").innerHTML = "[ 重新发送中...... ]", XHR.open("GET", "/api/resendVerifyEmail", !0), XHR.onreadystatechange = resendcheck, XHR.send(null)
}

function unBindWechat() {
    createXHR(), document.getElementById("unBindWechat").innerHTML = "[ 解绑中...... ]",
        XHR.open("GET", "/api/unBindWechatAccount", !0), XHR.onreadystatechange = unBindResult, XHR.send(null)
}
function unBindResult() {
    if (XHR.readyState == 4 && XHR.status == 200) {
        var e = XHR.responseText;
        if (e === "true") {
            document.getElementById("unBindWechat").innerHTML = "[ 解绑成功 ]";
            window.setTimeout("clearWechatInnerHtml()", 2000);
            return;
        }
        document.getElementById("unBindWechat").innerHTML = "[ 解绑失败，请重试 ]"
        window.setTimeout("reWriteUnBind()", 2000);
    }
}
function reWriteUnBind() {
    document.getElementById("unBindWechat").innerHTML = '[<a style="cursor:pointer;" onclick="unBindWechat();"> 解除绑定 </a>]'
}
function clearWechatInnerHtml() {
    document.getElementById("wechatBindInfo").innerHTML = "未绑定"
}
function resendcheck() {
    if (XHR.readyState == 4 && XHR.status == 200) {
        var e = XHR.responseText;
        switch (e) {
            case"0":
                document.getElementById("mailcheck").innerHTML = "[ 重新发送成功，请到邮箱查看 ]";
                break;
            case"1":
                document.getElementById("mailcheck").innerHTML = "[ 重新发送失败，请重新登入 ]";
                break;
            case"2":
                document.getElementById("mailcheck").innerHTML = "[ 您已经成功确认邮箱，无需重复确认 ]";
                break;
            case"3":
                Load(3);
                break;
            case"4":
                Load(4);
                break;
            default:
                alert(e)
        }
    } else document.getElementById("mailcheck").innerHTML = "[ 重新发送失败，请稍后重试 ]"
}
function Load(e) {
    for (var t = secs; t >= 0; t--)window.setTimeout("doUpdate(" + t + "," + e + ")", (secs - t) * 1e3)
}
function doUpdate(e, t) {
    switch (t) {
        case 3:
            document.getElementById("mailcheck").innerHTML = "[ 请不要频繁操作,请在" + e + "秒后重试 ]";
            break;
        case 4:
            document.getElementById("mailcheck").innerHTML = "[ 重新发送失败，请在" + e + "秒后重试 ]"
    }
    e == 0 && (document.getElementById("mailcheck").innerHTML = '[<a style="cursor:pointer;" onclick="renewemail();"> 重新发送验证邮件 </a>]')
}
var jqmode = 0, objectheigth = 0;
$(document).ready(function () {
    objectheigth = $("#game-list-wow").outerHeight(!0), $("#aforwowjq").click(function () {
        $("#game-list-wow").outerHeight(!0), jqmode == 0 ? (objectheigth > 280 && $("#layout-middle").animate({height: $("#layout-middle").outerHeight(!0) + objectheigth - 384}), jqmode = 1) : (objectheigth > 280 && $("#layout-middle").animate({height: $("#layout-middle").outerHeight(!0) - objectheigth + 376}), jqmode = 0)
    })
});
var XHR, secs = 60