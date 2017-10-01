@include('universal.header.header_login')
@include('account.logout.source.Css')
@include('account.logout.source.JsJump')
<!--suppress HtmlUnknownTarget -->
<div id="wrapper">
    <h1 id="logo"><a href="/"><img src="/resources/img/bn-logo.png" alt=""></a></h1>
    <div id="contentloged" class="login">
        <h2 style="text-align: center;"><br><br>您尚未登入，无需登出，即将跳转到主页</h2>
        <script language="javascript">
            Load("{{config('app.url')}}"); //要跳转到的页面
        </script>
    </div>
    <div id="ShowDiv"></div>
</div>
@include('universal.footer.bottom');