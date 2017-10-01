<script language='javascript' type='text/javascript'>
    var secs = {!!$countDown!!}; //倒计时的秒数
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
        document.getElementById('dumiao').innerHTML = '将在'+num+'秒后自动跳转' ;
        if(num == 0) { window.top.location.href=URL }
    }
</script>