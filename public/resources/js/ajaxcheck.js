function createXHR() {
    window.ActiveXObject ? XHR = new ActiveXObject("Microsoft.XMLHTTP") : window.XMLHttpRequest && (XHR = new XMLHttpRequest)
}
function checkname(e) {
    e != "" && e != null ? (createXHR(), document.getElementById("checkusernameajax").innerHTML = "<img src='/resources/img/waiting.gif' alt=''>查询用户名是否可用", document.getElementById("creation-submit").disabled = "", XHR.open("GET", "/api/check/checkUserName?name=" + e, !0), XHR.onreadystatechange = bacheck, XHR.send(null)) : (document.getElementById("checkusernameajax").innerHTML = "", document.getElementById("creation-submit").disabled = "")
}
function checkyanzhenma(e) {
    e != "" && e != null ? (createXHR(), document.getElementById("checkyanzhenmaajax").innerHTML = "<img src='/resources/img/waiting.gif' alt=''>", document.getElementById("creation-submit").disabled = "", XHR.open("GET","/api/check/checkCpatcha?code=" + e, !0), XHR.onreadystatechange = bbcheck, XHR.send(null)) : (document.getElementById("checkyanzhenmaajax").innerHTML = "", document.getElementById("creation-submit").disabled = "")
}
function bacheck() {
    if (XHR.readyState == 4 && XHR.status == 200) {
        var e = XHR.responseText;
        e == "true" ? (document.getElementById("checkusernameajax").innerHTML = "<img src='/resources/img/success.png' alt=''>用户名可以使用", document.getElementById("creation-submit").disabled = "") : e == "false" ? (document.getElementById("checkusernameajax").innerHTML = "<img src='/resources/img/warning-triangle.gif' alt=''>用户名已被占用", document.getElementById("creation-submit").disabled = "disabled") : e == "illegal" ? (document.getElementById("checkusernameajax").innerHTML = "<img src='/resources/img/warning-triangle.gif' alt=''>用户名仅允许使用中文、数字、字母及下划线", document.getElementById("creation-submit").disabled = "disabled") : (document.getElementById("checkusernameajax").innerHTML = "", document.getElementById("creation-submit").disabled = "disabled")
    }
}
function bbcheck() {
    if (XHR.readyState == 4 && XHR.status == 200) {
        var e = XHR.responseText;
        e == "true" ? (document.getElementById("checkyanzhenmaajax").innerHTML = "<img src='/resources/img/success.png' alt=''>", document.getElementById("creation-submit").disabled = "") : e == "false" ? (document.getElementById("checkyanzhenmaajax").innerHTML = "<img src='/resources/img/warning2.png' alt=''>", document.getElementById("creation-submit").disabled = "disabled") : (document.getElementById("checkyanzhenmaajax").innerHTML = "", document.getElementById("creation-submit").disabled = "disabled")
    }
}
var XHR