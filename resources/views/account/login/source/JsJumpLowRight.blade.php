<script language='javascript' type='text/javascript'>
    var secs ={{strtotime($_USER->getUserThisLoginTime()) + (config('app.share_account_cookie_valid_hours')*60*60) - time()}}; //倒计时的秒数
    var URL;
    function Load(url) {
        URL = url;
        for (var i = secs; i >= 0; i--) {
            window.setTimeout('doUpdate(' + i + ')', (secs - i) * 1000);
        }
    }
    function doUpdate(num) {
        document.getElementById('ShowDiv').innerHTML = '<h3>本共享账号上次登陆于{{$_USER->getUserThisLoginTime()}}，' +
            '距现在不足{{config('app.share_account_cookie_valid_hours')*60}}分钟，将在' + num + '秒后自动跳转登录<br>如果您的浏览器不支持自动跳转，<a href="' + URL + '">请点击跳转</a></h3>';
        if (num === 0) {
            window.top.location.href = URL
        }
    }
</script>