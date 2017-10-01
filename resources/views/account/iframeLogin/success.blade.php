@include('universal.header.header_login_iframe')
@include('account.iframeLogin.source.JsAndCss')
@include('account.iframeLogin.source.JsJumpSuccess')
</head>
<body style="overflow: hidden;">
<div id="embedded-login">
    <h1>Battle.net</h1>
    <div id="contentloged" class="login">
        <h2 style="text-align: center;"><br><br>登入成功<br><br></h2>
        <script language="javascript">
            Load("{{config('app.url')}}"); //要跳转到的页面
        </script>
    </div>
    <div id="ShowDiv"></div>
    <script>
        $(window.parent.document).find("iframe").load(function(){
            var main = $(window.parent.document).find("iframe");
            var thisheight = $(document).height();
            $(window.parent.document).find("#login-embedded").height(thisheight);
            main.height(thisheight);
            parent.ifNotLoginIframeCanChangeThisValue =true;
        });
    </script>
</div>
</body>
</html>