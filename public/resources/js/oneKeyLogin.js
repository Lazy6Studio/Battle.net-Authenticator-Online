;(function ($) {
    var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/", a256 = '', r64 = [256], r256 = [256],
        i = 0;
    var UTF8 = {
        encode: function (strUni) {
            var strUtf = strUni.replace(/[\u0080-\u07ff]/g, function (c) {
                var cc = c.charCodeAt(0);
                return String.fromCharCode(0xc0 | cc >> 6, 0x80 | cc & 0x3f)
            }).replace(/[\u0800-\uffff]/g, function (c) {
                var cc = c.charCodeAt(0);
                return String.fromCharCode(0xe0 | cc >> 12, 0x80 | cc >> 6 & 0x3F, 0x80 | cc & 0x3f)
            });
            return strUtf
        }, decode: function (strUtf) {
            var strUni = strUtf.replace(/[\u00e0-\u00ef][\u0080-\u00bf][\u0080-\u00bf]/g, function (c) {
                var cc = ((c.charCodeAt(0) & 0x0f) << 12) | ((c.charCodeAt(1) & 0x3f) << 6) | (c.charCodeAt(2) & 0x3f);
                return String.fromCharCode(cc)
            }).replace(/[\u00c0-\u00df][\u0080-\u00bf]/g, function (c) {
                var cc = (c.charCodeAt(0) & 0x1f) << 6 | c.charCodeAt(1) & 0x3f;
                return String.fromCharCode(cc)
            });
            return strUni
        }
    };
    while (i < 256) {
        var c = String.fromCharCode(i);
        a256 += c;
        r256[i] = i;
        r64[i] = b64.indexOf(c);
        ++i
    }
    function code(s, discard, alpha, beta, w1, w2) {
        s = String(s);
        var buffer = 0, i = 0, length = s.length, result = '', bitsInBuffer = 0;
        while (i < length) {
            var c = s.charCodeAt(i);
            c = c < 256 ? alpha[c] : -1;
            buffer = (buffer << w1) + c;
            bitsInBuffer += w1;
            while (bitsInBuffer >= w2) {
                bitsInBuffer -= w2;
                var tmp = buffer >> bitsInBuffer;
                result += beta.charAt(tmp);
                buffer ^= tmp << bitsInBuffer
            }
            ++i
        }
        if (!discard && bitsInBuffer > 0) result += beta.charAt(buffer << (w2 - bitsInBuffer));
        return result
    }

    var Plugin = $.base64 = function (dir, input, encode) {
        return input ? Plugin[dir](input, encode) : dir ? null : this
    };
    Plugin.btoa = Plugin.encode = function (plain, utf8encode) {
        plain = Plugin.raw === false || Plugin.utf8encode || utf8encode ? UTF8.encode(plain) : plain;
        plain = code(plain, false, r256, b64, 8, 6);
        return plain + '===='.slice((plain.length % 4) || 4)
    };
    Plugin.atob = Plugin.decode = function (coded, utf8decode) {
        coded = String(coded).split('=');
        var i = coded.length;
        do {
            --i;
            coded[i] = code(coded[i], true, r64, a256, 6, 8)
        } while (i > 0);
        coded = coded.join('');
        return Plugin.raw === false || Plugin.utf8decode || utf8decode ? UTF8.decode(coded) : coded
    }
}(jQuery));
var pollingXHR, showPolling;
var delayMillSeconds = 5000;
function startPolling() {
    if (showPolling != null) {
        clearInterval(showPolling);
        showPolling = null
    }
    window.ActiveXObject ? pollingXHR = new ActiveXObject("Microsoft.XMLHTTP") : window.XMLHttpRequest && (pollingXHR = new XMLHttpRequest);
    pollingXHR.open("GET", pollingUrl, !0);
    pollingXHR.onreadystatechange = pollingResultChuLi;
    pollingXHR.send(null)
}
function pollingResultChuLi() {
    if (pollingXHR.readyState === 4 && pollingXHR.status === 200) {
        var textHTML = pollingXHR.responseText;
        if (textHTML === "" || textHTML === null) {
            hideOneKeyUI();
            showPolling = setInterval("startPolling()", delayMillSeconds);
            return
        }
        jsondata = eval("(" + textHTML + ")");
        if (jsondata.code !== 0) {
            hideOneKeyUI();
            showPolling = setInterval("startPolling()", delayMillSeconds);
            return
        }
        showOneKeyUI(jsondata)
    } else if (pollingXHR.readyState === 4) {
        hideOneKeyUI();
        showPolling = setInterval("startPolling()", delayMillSeconds);
        return
    }
}
function showOneKeyUI(jsonObj) {
    if (OneKeyLogin.getState()) {
        if (jsonObj.data.request_id != null && jsonObj.data.request_id != "" && OneKeyLogin.getRequestId() != jsonObj.data.request_id) {
            OneKeyLogin.embeddedUrl = oneKeyLoginUrl + window.btoa(JSON.stringify(jsonObj));
            OneKeyLogin.changeUrl();
            return
        }
    } else if (OneKeyLogin.getRequestId() == jsonObj.data.request_id) {
        showPolling = setInterval("startPolling()", delayMillSeconds);
        return
    }
    $.base64.utf8encode = true;
    OneKeyLogin.requestId = jsonObj.data.request_id;
    OneKeyLogin.embeddedUrl = oneKeyLoginUrl + encodeURI(JSON.stringify(jsonObj));
    OneKeyLogin.open()
}
function hideOneKeyUI() {
    OneKeyLogin.requestId = "";
    OneKeyLogin._close(true)
}
$(document).ready(function () {
    startPolling()
});