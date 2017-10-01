@include('universal.header.header_normal')
@include('account.register.source.JsAndCss')
@include('account.register.source.JsJumpRegisterSuccess')
@include('universal.top.layoutTop')
<div id="layout-middle">
    <div id="homewrapper">
        <div id="content">
            <div id="page-header">
                <p class="privacy-message"><b>
                        注册成功。
                    </b>
                    阅读我们的<a href="/copyright" target="_blank">
                        版权声明及免责条款
                    </a>，了解您的注意事项。
                </p>
            </div>
            <div id="contentloged" class="login">
                <h2 style="text-align: center;"><br><br>恭喜您，注册成功<br><br></h2>
                <script language="javascript">
                    Load("{{config('app.url')}}"); //要跳转到的页面
                </script>
            </div>
            <div id="ShowDiv"></div>
        </div>
    </div>
</div>
@include('universal.footer.bottom')