@include('universal.header.header_normal')
@include('account.changePassword.source.JsJumpSuccess')
@include('universal.top.layoutTop')
<div id="layout-middle">
    <div id="homewrapper">
        <div id="content">
            <div id="page-content">
                <div id="breadcrumb">
                    <ol class="ui-breadcrumb">
                        <li><a href="/">首页</a></li>
                        <li class="last"><a href="/changePassword">修改密码</a></li>
                    </ol>
                </div>
            </div>
            <div id="contentloged" class="login">
                <h2 style="text-align: center;"><br><br>密码修改成功，已向注册邮箱发送通知邮件<br><br></h2>
                <script language="javascript">
                    Load("{{config('app.url')}}account"); //要跳转到的页面
                </script>
            </div>
            <div id="ShowDiv"></div>

        </div>
    </div>
</div>
@include('universal.footer.bottom')