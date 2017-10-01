function createXHR() {
    window.ActiveXObject ? XHR = new ActiveXObject("Microsoft.XMLHTTP") : window.XMLHttpRequest && (XHR = new XMLHttpRequest)
}
function checkname(e) {
    e != "" && e != null ?
        (createXHR(), document.getElementById("checkusernameajax").innerHTML = "<img src='/resources/img/waiting.gif' alt=''>查询用户是否存在", document.getElementById("creation-submit").disabled = "", XHR.open("GET", "/api/check/checkUserName?name=" + e, !0), XHR.onreadystatechange = bacheck, XHR.send(null)) :
        (document.getElementById("checkusernameajax").innerHTML = "", document.getElementById("creation-submit").disabled = "")
}
function bacheck() {
    if (XHR.readyState == 4 && XHR.status == 200) {
        var e = XHR.responseText;
        e == "true" ? (document.getElementById("checkusernameajax").innerHTML = "<img src='/resources/img/warning-triangle.gif' alt=''>用户不存在，请检查后再试或清空之", document.getElementById("creation-submit").disabled = "disabled") : e == "false" ? (document.getElementById("checkusernameajax").innerHTML = "<img src='/resources/img/success.png' alt=''>用户存在，可以正常操作", document.getElementById("creation-submit").disabled = "") : e == "illegal" ? (document.getElementById("checkusernameajax").innerHTML = "<img src='/resources/img/warning-triangle.gif' alt=''>用户名仅允许使用中文、数字、字母及下划线", document.getElementById("creation-submit").disabled = "disabled") : (document.getElementById("checkusernameajax").innerHTML = "", document.getElementById("creation-submit").disabled = "")
    }
}
var XHR