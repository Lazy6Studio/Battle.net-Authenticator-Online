{{HTML::style('/resources/css/accountbody.css')}}
<script>
    $(window.parent.document).find("iframe").load(function () {
        var main = $(window.parent.document).find("iframe");
        var thisheight = $(document).height();
        $(window.parent.document).find("#login-embedded").height(thisheight);
        main.height(thisheight);
    });
    $(function () {
        $('#accountName').focus();
    });
    function closeiframe() {
        window.parent.OneKeyLogin._close(true);
        window.parent.startPolling();
    }
</script>

<script language='javascript' type='text/javascript'>
    var secs = 3; //倒计时的秒数
    var isLoading = false;
    var hasResult = false;
    var commitXHR;
    var postUrl = '/api/oneButtonAuth/commit';
    function Load(msg) {
        for (var i = secs; i >= 0; i--) {
            window.setTimeout('doUpdate(' + i + ',"' + msg + '")', (secs - i) * 1000);
        }
    }
    function doUpdate(num, msg) {
        document.getElementById('resultInfo').innerHTML = msg + '，将在' + num + '秒后自动关闭';
        if (num === 0) {
            closeiframe();
        }
    }

    function commitComfirmInfo(isAgree) {
        if (isLoading || hasResult) {
            return;
        }
        isLoading = true;
        window.ActiveXObject ? commitXHR = new ActiveXObject("Microsoft.XMLHTTP") : window.XMLHttpRequest && (commitXHR = new XMLHttpRequest);
        commitXHR.open("POST", postUrl, !0);
        commitXHR.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
        commitXHR.onreadystatechange = ajaxResultChuLi;
        commitXHR.send("accept=" + isAgree + "&json=" + '{{urlencode($gotJson)}}');
        document.getElementById('resultInfo').innerHTML = "提交请求中";
    }
    function ajaxResultChuLi() {
        if (commitXHR.readyState != 4) {
            return;
        }
        if (commitXHR.status == 200) {
            var textHTML = commitXHR.responseText;
            if (textHTML == "" || textHTML == null) {
                document.getElementById('resultInfo').innerHTML = "请求失败，请重试";
                isLoading = false;
                return;
            }
            jsondata = eval("(" + textHTML + ")");
            if (jsondata.code != 0) {
                document.getElementById('resultInfo').innerHTML = "请求失败，请重试";
                isLoading = false;
                return;
            }
            hasResult = true;
            isLoading = false;
            Load(jsondata.message);
        } else {
            isLoading = false;
            document.getElementById('resultInfo').innerHTML = "请求失败，请重试";
        }
    }

    function autoCloseWhenTimeout() {
        setTimeout('closeiframe()',
        @if ($jsonArray['data']['time'] + 120 <= time() )
        {{"1"}}
        @else
        {{1000 * ($jsonArray['data']['time'] + 120 - time())}}
        @endif
        );
    }
    window.onload = autoCloseWhenTimeout;
</script>