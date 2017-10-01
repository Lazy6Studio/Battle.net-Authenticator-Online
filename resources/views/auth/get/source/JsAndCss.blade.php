<script type="text/javascript">
    geturladd = '/api/auth/getCode?{{HttpFormConstant::FORM_KEY_AUTH_ID}}={{$authBean->getAuthId()}}';
</script>
{{HTML::script('/resources/js/clipboard.min.js')}}
{{HTML::script('/resources/js/auth.js')}}
{{HTML::script('/resources/js/ZeroClipboard.js')}}
{{HTML::script('/resources/js/lobby.js')}}
{{HTML::script('/resources/js/bam.js')}}
<script>
    var pollingUrl = '/api/oneButtonAuth/getRequestInfo?{{HttpFormConstant::FORM_KEY_AUTH_ID}}={{$authBean->getAuthId()}}';
    var oneKeyLoginUrl = '/oneButtonAuth/iframePage?json=';
</script>
{{HTML::script('/resources/js/oneKeyLogin.js')}}
{{HTML::style('/resources/css/authbody.css')}}