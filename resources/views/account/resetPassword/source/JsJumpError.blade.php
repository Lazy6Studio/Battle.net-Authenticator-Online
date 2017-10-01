{{HTML::style('/resources/css/authbody.css')}}
<script language='javascript' type='text/javascript'>
    var secs = 3; //倒计时的秒数
    var URL;
    function Load(url) {
        URL = url;
        for (var i = secs; i >= 0; i--) {
            window.setTimeout('doUpdate(' + i + ')', (secs - i) * 1000);
        }
    }
    function doUpdate(num) {
        document.getElementById('ShowDiv').innerHTML = '<h3>将在' + num + '秒后自动跳转，如果您的浏览器不支持自动跳转，<a href="' + URL + '">请点击跳转</a></h3>';
        if (num === 0) {
            window.top.location.href = URL
        }
    }
</script>