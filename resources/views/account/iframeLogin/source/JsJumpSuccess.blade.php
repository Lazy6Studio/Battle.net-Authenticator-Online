<script language='javascript' type='text/javascript'>
    var secs =3; //倒计时的秒数
    var URL ;
    function Load(url){
        URL =url;
        for(var i=secs;i>=0;i--)
        {
            window.setTimeout('doUpdate(' + i + ')', (secs-i) * 1000);
        }
    }
    function doUpdate(num)
    {
        document.getElementById('ShowDiv').innerHTML = '<h3>登录窗口将在'+num+'秒后自动关闭并刷新主页</h3>' ;
        if(num === 0) {
            window.parent.window.location.href=URL
        }
    }
    $(window.parent.document).find("iframe").load(function(){
        parent.ifNotLoginIframeCanChangeThisValue =true;
    });
</script>