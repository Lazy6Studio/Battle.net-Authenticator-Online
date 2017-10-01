<!--suppress SpellCheckingInspection -->
<div id="layout-middle"
     style="margin: 0;padding: 0;border: 0;outline: 0;background: #DDDCDA url({{config('app.url')}}resources/img/bgbody.jpg) top center repeat-x;border-bottom: 4px solid #777674;width: 100%;min-height: 359px;">
    <div id="homewrapper"
         style="width: 990px;margin: 0 auto;padding: 0;border: 0;outline: 0;">
        <div id="content"
             style="margin: 0;border: 0;outline: 0;padding: 32px 0;position: relative;">
            <div id="page-content" style="overflow: hidden;margin: 0;padding: 0;border: 0;outline: 0;height: auto;">
                <div id="breadcrumb"
                     style="margin: 0;padding: 0;border: 0;outline: 0;color: rgb(141,141,141);position: absolute;z-index: 1;left: 0;">
                    <ol class="ui-breadcrumb"
                        style="margin: 0;padding: 0;border: 0;outline: 0;position: relative;list-style: none outside none;top: -3px;">
                        <li style="margin: 0;padding: 0;border: 0;outline: 0;display: inline;margin-right: 5px;padding-right: 12px;background: url({{config('app.url')}}resources/img/breadcrumb.png) no-repeat 100%;max-width: 850px;max-height: 40px;overflow: hidden;">
                            <a href="{{config('app.url')}}" target="_blank"
                               style="border-bottom: none;color: #5f5f5f;font-weight: bold;font-size: 12px;line-height: 20px;font-style: normal;text-decoration: none;">首页</a>
                        </li>
                        <li style="margin: 0;padding: 0;border: 0;outline: 0;display: inline;margin-left: 5px;padding-right: 12px;position: relative;background: none;max-width: 850px;max-height: 40px;overflow: hidden;">
                            <a href="{{config('app.url')}}account" target="_blank"
                               style="border-bottom: none;color: #000000;font-weight: bold;font-size: 12px;line-height: 20px;font-style: normal;text-decoration: none;">{{@$topNavValueText}}</a>
                        </li>
                    </ol>
                </div>
                <div class="article-column"
                     style="color: #4A4A4A;float: left;width: 100%;margin: 0;padding: 0;border: 0;outline: 0;">
                    <div id="article-container" style="margin: 0;padding: 0;border: 0;outline: 0;margin-top: 40px;">
                        <div id="article"
                             style="margin: 0;border: 0;outline: 0;position: relative;background-color: rgb(235,235,235);padding: 28px 30px 30px;">
                            <h2 id="article-title"
                                style="margin: 0;padding: 0;border: 0;outline: 0;padding-bottom: 3px;border-bottom: 1px solid rgb(170,170,170);font-size: 27px;font-weight: bold;margin-bottom: 20px;color: rgb(0,0,0);letter-spacing: -0.05em;">
                                欢迎使用{{config('app.name')}}</h2>
                            <div id="article-content"
                                 style="margin: 0;padding: 0;border: 0;outline: 0;color: #4A4A4A;background-color: #EBEBEB;font-size: 14px;line-height: 20px;">
                                <p style="margin: 0;padding: 0;border: 0;outline: 0;margin-bottom: 25px;">
                                    欢迎访问{{config('app.name')}}，请注意，{{config('app.name')}}不是官方的站点，它是由{{config('app.developer_name')}}开发的第三方网站<br>
                                    请务必查看<a href="{{config('app.url')}}faq"
                                            style="color: #0072a3;text-decoration: none;">FAQ</a>页面和<a
                                            href="{{config('app.url')}}copyright"
                                            style="color: #0072a3;text-decoration: none;">版权声明及免责条款</a>页面，这将确保您了解您的权利和义务，并帮助您使用本站点<br>
                                    您可以使用任何连入国际互联网的计算机访问我们，并获得您的动态验证码，我想，这些东西你都已经知道了，所以不多说了，还是查看下方的邮件具体内容吧<br>
                                </p>
                                <h3 class="article-ci"
                                    style="width:66.66%;border-bottom: 1px solid rgb(170,170,170);margin: 0;padding: 0;outline: 0;font-size: 20px;font-weight: normal;color: #000000;padding-bottom: 3px;margin-bottom: 11px;">
                                    为什么我们发送了这封邮件 </h3>
                                <p class="alignleft"
                                   style="margin: 0;padding: 0;border: 0;outline: 0;text-align: left;margin-bottom: 5px;">
                                    您刚刚成功创建了{{config('app.name')}}的新账号，为保证账号安全性，发送本邮件告知您账号简单的信息，并请您点击邮箱地址确认链接确认邮箱地址<br><br>
                                </p>
                                <h3 class="article-ci"
                                    style="width:66.66%;border-bottom: 1px solid rgb(170,170,170);margin: 0;padding: 0;outline: 0;font-size: 20px;font-weight: normal;color: #000000;padding-bottom: 3px;margin-bottom: 11px;">
                                    您的注册信息 </h3>
                                <p class="alignleft"
                                   style="margin: 0;padding: 0;border: 0;outline: 0;text-align: left;margin-bottom: 5px;">
                                    您的用户名：{{$_USER->getUserName()}}<br>
                                    您的用户ID：{{$_USER->getUserId()}}<br>
                                    您的密码：{{Functions::emailHidePassword($unMd5Password)}} （只显示前三位）<br>
                                    您的安全问题：{{$registerQuestion[$_USER->getUserQuestion()]}}<br>
                                    您的问题答案：(出于安全考虑，该信息已隐藏)<br>
                                    您的账号类型：{{$_USER->getUserRight()==\App\User::USER_NORMAL?"普通账号":"共享账号"}}<br>
                                    您的注册邮箱：{{$_USER->getUserEmail()}}<br>
                                    您的注册时间：{{$_USER->getUserRegisterTime()}}<br>
                                    @if(!empty($wechatNickname))
                                        您绑定的微信账号昵称：{{$wechatNickname}}<br>
                                    @endif<br>
                                </p>
                                <h3 class="article-ci"
                                    style="width:66.66%;border-bottom: 1px solid rgb(170,170,170);outline: 0;font-size: 20px;font-weight: normal;color: #000000;padding: 0 0 3px;margin: 0 0 11px;">
                                    邮箱地址确认 </h3>
                                <p class="alignleft"
                                   style="margin: 0;padding: 0;border: 0;outline: 0;text-align: left;margin-bottom: 5px;">
                                    您的账号已经创建，为了今后能顺利管理账号，请点击下面的链接确认您的邮箱地址<br>
                                    <a href='{{$mailCheckUrl}}' target='_blank'
                                       style="text-decoration: none;color: #0072a3;text-align: left;">点击确认您的邮箱地址</a><br><br>
                                </p>
                                <h3 class="article-ci"
                                    style="width:66.66%;border-bottom: 1px solid rgb(170,170,170);outline: 0;font-size: 20px;font-weight: normal;color: #000000;padding: 0 0 3px;margin: 0 0 11px;">
                                    提醒 </h3>
                                <p class="alignleft"
                                   style="margin: 0;padding: 0;border: 0;outline: 0;text-align: left;margin-bottom: 5px;">
                                    如果这不是您操作的，请彻底删除本邮件，请绝对不要点击以上链接<br>
                                    本邮件为自动发送，请不要直接回复，如需联系请<a href="mailto:{{config('app.app_host_email')}}"
                                                             style="text-decoration: none;color: #0072a3;text-align: left;">点击此处</a><br><br>
                                </p>
                                <h3 class="article-ci"
                                    style="width:66.66%;border-bottom: 1px solid rgb(170,170,170);outline: 0;font-size: 20px;font-weight: normal;color: #000000;padding: 0 0 3px;margin: 0 0 11px;">
                                    发件人&发件时间 </h3>
                                <p class="alignleft"
                                   style="margin: 0;padding: 0;border: 0;outline: 0;text-align: left;margin-bottom: 5px;">
                                    {{config('app.owner')}}<br>
                                    发送于{{date('Y-m-d H:i:s')}}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
