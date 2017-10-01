@include('universal.header.header_login')
@include('account.login.source.JsAndCss')
@include('account.login.source.JsJumpLogined')
</head>
<body>
<div id="wrapper">
    <h1 id="logo"><a href="/"><img src="/resources/img/bn-logo.png" alt=""></a></h1>
    <div id="contentloged" class="login">
        <h2 style="text-align: center;"><br><br>您已经登入了,不要重复登入<br><br></h2>
        <script language="javascript">
            Load("{{config('app.url')}}"); //要跳转到的页面
        </script>
    </div>
    <div id="ShowDiv"></div>
</div>
@include('universal.footer.bottom')