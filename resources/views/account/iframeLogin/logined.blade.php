@include('universal.header.header_login_iframe')
@include('account.iframeLogin.source.JsAndCss')
@include('account.iframeLogin.source.JsJumpLogined')
</head>
<body style="overflow: hidden;">
<div id="embedded-login">
    <h1>Battle.net</h1>
    <div id="contentloged" class="login">
        <h2 style="text-align: center;"><br><br>您已经登入了,不要重复登入<br><br></h2>
        <script language="javascript">
            Load("{{config('app.url')}}"); //要跳转到的页面
        </script>
    </div>
    <div id="ShowDiv"></div>
</div>
</body>
</html>