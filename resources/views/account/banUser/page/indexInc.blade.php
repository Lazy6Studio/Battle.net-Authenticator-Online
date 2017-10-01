<div id="layout-middle">
    <div id="homewrapper">
        <div id="content">
            @if(@!empty($errorString))
                <div class="alert error closeable border-4 glow-shadow">
                    <div class="alert-inner">
                        <div class="alert-message"><p class="title"><strong><a name="form-errors"> </a>发生下列错误：</strong>
                            </p>
                            <ul>
                                <li>{{$errorString}}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            <div id="page-header">
                <h2 class="subcategory">
                    封禁用户
                </h2>
                <h3 class="headline"> 填写用户名，直接让丫的拜拜 </h3>
            </div>
            <form id="support-form" class="login-support-form" novalidate="novalidate" action="/banUser" style="margin-top: 50px;"
                  method="post">
                {{csrf_field()}}
                <div class="input-row input-row-text">
                    <span class="input-left">
                        <label for="userName">
                            <span class="label-text">
                                系统用户名：
                            </span>
                            <span class="input-required">*</span>
                        </label>
                    </span>
                    <span class="input-right" style="margin-left: -3px;">
                        <span class="input-text input-text-medium">
                            <input name="userName" id="userName" onblur="checkname(this.value);"  class="medium border-5 glow-shadow-2" autocomplete="off"
                                   placeholder="请输入系统用户名，送TA回家" required="required"
                                   maxlength="64" tabindex="1" type="text">
                                <span id="checkusernameajax"></span>
                            <span class="inline-message " id="email-message">&nbsp;</span>
                        </span>
                    </span>
                </div>

                <fieldset class="ui-controls " style="margin-top: 40px;">
                    <button class="ui-button button1" type="submit" id="creation-submit" tabindex="1"><span
                                class="button-left"><span class="button-right">封禁</span></span></button>
                    <a class="ui-cancel " href="/">
                        <span>
                            返回 </span>
                    </a>
                    <script type="text/javascript">
                        //<![CDATA[
                        var FormMsg = {
                            'headerSingular': '出错了。',
                            'headerMultiple': '发生下列错误：',
                            'fieldInvalid': '部分内容填写有误。',
                            'fieldMissing': '此项为必填。',
                            'fieldsMissing': '请填写全部必填项。',
                            'emailInfo': '此将为您的登录使用名称。',
                            'emailMissing': '请输入一个有效的邮箱地址。',
                            'emailInvalid': '无效的电子邮件地址。',
                            'emailMismatch': '电子邮件地址必须相同。',
                            'passwordInvalid': '不符合密码规则。',
                            'passwordMismatch': '密码必须相同。',
                            'touDisagree': '继续前您必须先接受协议。'
                        };
                        //]]>
                    </script>
                </fieldset>
            </form>
        </div>
    </div>
</div>