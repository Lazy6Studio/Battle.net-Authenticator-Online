{{HTML::style('/resources/css/accountbody.css')}}
{{HTML::script('/resources/js/lobby.js')}}
{{HTML::script('/resources/js/account.js')}}
<script src="http{{Functions::isHTTPS()?"s":""}}://ip.ws.126.net/ipquery?ip={{$_USER->getUserLastTimeLoginIP()}}"></script>
<script>
    $(document).ready(function () {
        if(localAddress!==null){
            ipLocation = localAddress['province']+localAddress['city'];
        }
        $("#lastLoginIpAddress").html($("#lastLoginIpAddress").html().trim() + " (" + ipLocation + ")");
    });
</script>