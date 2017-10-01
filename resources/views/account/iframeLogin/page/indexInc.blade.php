</head>
<body style="overflow: hidden;">
<div id="embedded-login">
    <h1>Battle.net</h1>
    <form id="form" method="post" action="/iframeLogin">
        {{ csrf_field() }}
        <a id="embedded-close" href="javascript:void(0);" onclick="closeiframe()"></a>
        <p>
            <label class="label" for="accountName"> 用户名 </label>
            @if(@$loginCheckUtils->getLoginErrorCode()==2)
                <input id="accountName" class='input input-error'
                       onfocus="this.className = 'input';
                       if(document.getElementById('password').className=='input')
                       {
                           document.getElementById('namePasswordErrorSpan').removeChild(document.getElementById('emailAddress-message'));
                       }"
                       type="text" value="" name="username" maxlength="32" tabindex="1"/>
            @else
                <input id="accountName" class='input' type="text" value="" name="username" maxlength="32" tabindex="1"/>
            @endif
        </p>
        <p>
            <label class="label" for="password">密码</label>
            @if(@$loginCheckUtils->getLoginErrorCode()==2)
                <input id="password" class='input input-error'
                       onfocus="this.className = 'input';
                       if(document.getElementById('accountName').className=='input'){
                           document.getElementById('namePasswordErrorSpan').removeChild(document.getElementById('emailAddress-message'));
                       }"
                       type="password" name="password" maxlength="256" tabindex="2" autocomplete="off"/>
            @else
                <input id="password" class='input' type="password" name="password" maxlength="256" tabindex="2"
                       autocomplete="off"/>
        @endif
        @if(@$loginCheckUtils->getLoginErrorCode()==2)
            <div id="namePasswordErrorSpan">
                <span id="emailAddress-message" class="inline-message">用户名或密码输入错误!</span>
            </div>
        @elseif(@$loginCheckUtils->getLoginErrorCode()==3)
            <div id="namePasswordErrorSpan">
                <span id="emailAddress-message" class="inline-message">无权登入,该账号已被封禁!</span>
            </div>
            @endif
            </p>
            <div class="imgandreloader">
                <div id="captcha-image" style="width: 200px;height: 70px;">
                    <img id="sec-string" onclick="refreshCaptcha();
                    document.getElementById('letters_code').focus();"
                         src="/api/captchaCode?rand={{time()}}"
                         alt="换一个"
                         title="换一个" class="border-5"/>
                </div>
                <div id="captcha-reloader">
                    看不清楚？<br/>
                    <a href="javascript:void(0);" onclick="refreshCaptcha();
                        document.getElementById('letters_code').focus();">换一个</a>
                    <script type='text/javascript'>
                        //定义的刷新请求
                        function refreshCaptcha() {
                            var img = document.images['sec-string'];
                            img.src = img.src.substring(0, img.src.lastIndexOf("?")) + "?rand=" + Math.random() * 1000;
                        }
                        jquerycodechecked = false;
                        //<![CDATA[
                        $(document).ready(function () {
                            $("#letters_code").keydown(function () {
                                removeNoCpatchaInputError();
                            });
                            $("#letters_code").keyup(function () {
                                if ($("#letters_code")[0].value.length === 6) {
                                    checkyanzhenma($("#letters_code")[0].value);
                                    jquerycodechecked = true;
                                } else {
                                    jquerycodechecked = false;
                                    document.getElementById('checkyanzhenmaajax').innerHTML = "";
                                }
                            });
                        });
                        //]]>
                    </script>
                </div>
            </div>
            <p>
                <label class="label" for="letters_code" style="margin-bottom: 4px;">
                    出于安全性考虑，请输入上方图示中的字符。（这并不是您的密码）</label>
                @if(@$loginCheckUtils->getLoginErrorCode()==1)
                    <input id="letters_code" class='input input-error'
                           onfocus="this.className = 'input';document.getElementById('errorspan').removeChild(document.getElementById('emailAddress-message'));"
                           type="text" onblur="if (!jquerycodechecked) {
                        checkyanzhenma(this.value);
                    }" style="width:290px;" name="letters_code" maxlength="6" tabindex="2" autocomplete="off"/>
                @else
                    <input id="letters_code" class='input' type="text" onblur="if (!jquerycodechecked) {
                        checkyanzhenma(this.value);
                    }" style="width:290px;" name="letters_code" maxlength="6" tabindex="2" autocomplete="off"/>
                @endif
                &nbsp;&nbsp;<span id="checkyanzhenmaajax"></span>
            <div id="errorspan">
                @if(@$loginCheckUtils->getLoginErrorCode()==1)
                    <span id="emailAddress-message" class="inline-message">验证码输入错误!</span>
                @endif
            </div>
            </p>
            <p><span id="remember-me"><label for="persistLogin" style="position: relative;top: 4px;">
                    <input id="persistLogin" type="checkbox" checked="checked" name="persistLogin"
                           style="top: 2px;position: relative;"/>保持登录状态
                </label></span>
                <button id="creation-submit" class="ui-button button1" type="submit"
                        onClick='return submitcheck();' data-text="正在处理……"><span
                            class="button-left"><span
                                class="button-right">
                        登录
                    </span></span></button>
            </p>
    </form>
</div>
</body>
</html>
