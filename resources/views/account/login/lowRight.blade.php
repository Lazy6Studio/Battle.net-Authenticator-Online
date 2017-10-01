@include('universal.header.header_login')
@include('account.login.source.JsAndCss')
@include('account.login.source.JsJumpLowRight')
<div id="wrapper">
    <h1 id="logo"><a href="/"><img src="/resources/img/bn-logo.png" alt=""></a></h1>
    <div id="contentloged" class="login">
        <h2 style="text-align: center;"><br><br>共享账号已存在活跃客户端<br><br></h2>
        <script language="javascript">
            Load("{{config('app.url')}}login"); //要跳转到的页面
        </script>
    </div>
    <div id="ShowDiv"></div>
</div>
@include('universal.footer.bottom')