@include('universal.header.header_login_iframe')
@include('account.iframeLogin.source.JsAndCss')
@include('account.iframeLogin.source.JsJumpLowRight')
</head>
<body style="overflow: hidden;">
<div id="embedded-login">
    <h1>Battle.net</h1>
    <div id="contentloged" class="login">
        <a id="embedded-close" href="javascript:void(0);" onclick="closeiframe()"></a>
        <h2 style="text-align: center;"><br><br>共享账号已存在活跃客户端<br><br></h2>
        <script language="javascript">
            Load("{{config('app.url')."login"}}"); //要跳转到的页面
        </script>
    </div>
    <div id="ShowDiv"></div>
</div>
</body>
</html>