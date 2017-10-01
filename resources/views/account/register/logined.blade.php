@include('universal.header.header_normal')
@include('account.register.source.JsAndCss')
@include('account.register.source.JsJumpLogined')
@include('universal.top.layoutTop')
<div id="layout-middle">
    <div id="homewrapper">
        <div id="content">
            <div id="page-header">
                <p class="privacy-message"><b>
                        重复注册很没有必要！！！
                    </b>
                    阅读我们的<a href="/copyright" target="_blank">
                        版权声明及免责条款
                    </a>，了解您的注意事项。
                </p>
            </div>
            <div class="alert error closeable border-4 glow-shadow">
                <div class="alert-inner">
                    <div class="alert-message"><p class="title"><strong><a name="form-errors"> </a>发生错误：</strong></p>
                        <ul>
                            <li>您已经登入了，请不要重复注册</li>
                            <li id="dumiao"></li>
                            <li>如果您的浏览器不支持自动跳转，<a href="/">请点击跳转</a></li><!--不要修改这里的内容，这里的显示内容是特别的，不要修改jump写入的标签-->
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script language="javascript">
        Load("{{config('app.url')}}");
    </script>
</div>
@include('universal.footer.bottom')