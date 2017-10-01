@include('universal.header.header_login')
@include('account.login.source.JsAndCss')
@include('account.login.source.JsJumpSuccess')
<div id="wrapper">
    <h1 id="logo"><a href="/"><img src="/resources/img/bn-logo.png" alt=""></a></h1>
    <div id="contentloged" class="login">
        <h2 style="text-align: center;"><br><br>恭喜您，您已成功登入<br><br></h2>
        <script language="javascript">
            Load("{{config('app.url')}}{{$from}}"); //要跳转到的页面
        </script>
    </div>
    <div id="ShowDiv"></div>
</div>
@include('universal.footer.bottom')