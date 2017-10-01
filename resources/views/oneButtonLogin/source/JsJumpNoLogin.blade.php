{{HTML::style('/resources/css/accountbody.css')}}
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
        document.getElementById('ShowDiv').innerHTML = '<h3>将在'+num+'秒后自动关闭</h3>' ;
        if(num === 0) {
            closeiframe();
        }
    }
</script>
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
    }
</script>