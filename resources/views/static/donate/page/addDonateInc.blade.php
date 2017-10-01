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
                    添加捐赠信息
                </h2>
                <h3 class="headline"> 填写捐赠相关数据，展示到捐赠页面 </h3>
            </div>
            <form id="support-form" class="login-support-form" novalidate="novalidate" action="/addDonate"
                  method="post">
                {{csrf_field()}}
                <div class="input-row input-row-text">
                    <span class="input-left">
                        <label for="donateName">
                            <span class="label-text">
                                捐赠人昵称：
                            </span>
                            <span class="input-required">*</span>
                        </label>
                    </span>
                    <span class="input-right" style="margin-left: -3px;">
                        <span class="input-text input-text-medium">
                            <input name="donateName" id="donateName" class="medium border-5 glow-shadow-2" autocomplete="off"
                                   placeholder="请输入捐赠人昵称，留空显示匿名土豪"
                                   maxlength="16" tabindex="1" type="text">
                            <span class="inline-message " id="email-message">&nbsp;</span>
                        </span>
                    </span>
                </div>
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
                                   placeholder="请输入系统用户名，留空不添加相关权限"
                                   maxlength="64" tabindex="1" type="text">
                                <span id="checkusernameajax"></span>
                            <span class="inline-message " id="email-message">&nbsp;</span>
                        </span>
                    </span>
                </div>
                <div class="input-row input-row-text">
                    <span class="input-left">
                        <label for="donateTime">
                            <span class="label-text">
                                捐赠时间：
                            </span>
                            <span class="input-required">*</span>
                        </label>
                    </span>
                    <span class="input-right" style="margin-left: -3px;">
                        <span class="input-text input-text-medium">
                            <input name="donateTime" id="donateTime" class="medium border-5 glow-shadow-2" autocomplete="off"
                                   placeholder="格式类似2013-06-20，留空默认为当天"
                                   maxlength="10" tabindex="1" type="text">
                            <span class="inline-message " id="email-message">&nbsp;</span>
                        </span>
                    </span>
                </div>

                <div class="input-row input-row-text">
                    <span class="input-left">
                        <label for="donateCurrency">
                            <span class="label-text">
                                捐赠币种：
                            </span>
                            <span class="input-required">*</span>
                        </label>
                    </span>
                    <span class="input-right" style="margin-left: -3px;">
                        <span class="input-text input-text-medium">
                            <input name="donateCurrency" id="donateCurrency" class="medium border-5 glow-shadow-2" autocomplete="off"
                                   placeholder="请输入币种" required="required"
                                   maxlength="16" tabindex="1" type="text">
                            <span class="inline-message " id="email-message">&nbsp;</span>
                        </span>
                    </span>
                </div>
                <div class="input-row input-row-text">
                    <span class="input-left">
                        <label for="donateCount">
                            <span class="label-text">
                                捐赠数量：
                            </span>
                            <span class="input-required">*</span>
                        </label>
                    </span>
                    <span class="input-right" style="margin-left: -3px;">
                        <span class="input-text input-text-medium">
                            <input name="donateCount" id="donateCount" class="medium border-5 glow-shadow-2" autocomplete="off"
                                   placeholder="请输入捐赠的数量" required="required"
                                   maxlength="16" tabindex="1" type="text">
                            <span class="inline-message " id="email-message">&nbsp;</span>
                        </span>
                    </span>
                </div>

                <fieldset class="ui-controls ">
                    <button class="ui-button button1" type="submit" id="creation-submit" tabindex="1"><span
                                class="button-left"><span class="button-right">添加</span></span></button>
                    <a class="ui-cancel " href="/donate">
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