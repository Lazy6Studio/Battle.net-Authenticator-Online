<!--suppress ALL -->
<div id="layout-bottom" onselectstart="return false;" unselectable="on">
    <div id="homewrapperbotton">
        <div id="footer">
            <div id="footline">
                <div id="sitemap">
                    <div class="column">
                        <h3 class="pages">
                            <a href="" tabindex="100">站点页面</a>
                        </h3>
                        <ul>
                            <li><a href="/welcome">WELCOME</a></li>
                            <li><a href="/faq">FAQ</a></li>
                            <li><a href="/donate">捐赠</a></li>
                        </ul>
                    </div>
                    <div class="column">
                        <h3 class="auths">
                            <a href="/myAuth" tabindex="100">安全令</a>
                        </h3>
                        <ul>
                            <li><a href="/auth">默认安全令</a></li>
                            <li><a href="/myAuthList">我的安全令</a></li>
                            <li><a href="/addAuth">添加安全令</a></li>
                        </ul>
                    </div>
                    <div class="column">
                        <h3 class="account">
                            <a href="/account" tabindex="100">账号</a>
                        </h3>
                        <ul>
                            @if(@$dbError || !$_USER->getIsLogin())
                                <li><a href='/forgetPassword'>忘记密码</a></li>
                                <li><a href='/register'>注册账号</a></li>
                                <li><a href='/login'>登入账号</a></li>
                            @else
                                <li><a href='/account'>查看我的账号</a></li>
                                <li><a href='/changePassword'>修改密码</a></li>
                                <li><a href='/changeEmailAddress'>修改邮箱地址</a></li>
                            @endif
                        </ul>
                    </div>
                    <div class="column">
                        <h3 class="setting">
                            <a href="/" tabindex="100">其他</a>
                        </h3>
                        <ul>
                            <li><a href="/copyright">版权声明及免责条款</a></li>
                            <li><a href="/about">关于本站</a></li>
                            <li><a href="mailto:{{config('app.app_host_email')}}">联系</a></li>
                        </ul>
                    </div>
                </div>
                <div id="copyright">
                    ©2013-{{date('Y')}} {{config('app.owner')}}版权所有
                    @if(!empty(config('app.icp_record_name')))
                        <span class="beian">&nbsp;&nbsp;&nbsp;{{config('app.icp_record_name')}}</span>
                    @endif
                    @if(!empty(config('app.icp_record_no')))
                        <span class="beian"><a
                                    href="http://www.miibeian.gov.cn/">{{config('app.icp_record_no')}}</a></span>
                    @endif
                    @if(!empty(config('app.police_record_no')))
                        <span class="beian" style="margin-left: 12px;">
                            <img src="/resources/img/gongan_beian.png" width="16" alt="公安备案图标"
                                 height="16"/>{{config('app.police_record_no')}}
                        </span>
                    @endif
                    <p>
                        <span>建站时间: 2013年6月20日</span>
                        <span>安全运行: {{round((time() - strtotime("2013-06-20")) / 3600 / 24)}}天</span>

                        @if(@!$dbError)
                            <span>用户总数: {{DBHelper::getUserCount()}}</span>
                            <span>令牌总数: {{DBHelper::getAuthCount()}}</span>
                        @else
                            @if(($userCount = DBHelper::getUserCount())!==false)
                                <span>用户总数: {{$userCount}}</span>
                            @endif
                            @if(($authCount = DBHelper::getAuthCount())!==false)
                                <span>令牌总数: {{$authCount}}</span>
                            @endif
                        @endif
                        <span>
                            @if(!empty(config('app.third_share_sina_key')))
                                <img onclick="shareweibo('sina')" title="分享到新浪微博" alt="分享到新浪微博"
                                     src="/resources/weiboimg/sina.png"
                                     style="height: 15px; width: 15px; cursor:pointer; cursor: pointer;"/>
                            @endif
                            {{--<img onclick="shareweibo('tencent')" title="分享到腾讯微博" alt="分享到腾讯微博"--}}
                            {{--src="/resources/weiboimg/qq.png"--}}
                            {{--style="height: 15px; width: 15px; cursor: pointer; cursor: pointer;"/>--}}
                            {{--<img onclick="shareweibo('sohu')" title="分享到搜狐微博" alt="分享到搜狐微博"--}}
                            {{--src="/resources/weiboimg/sohu.png"--}}
                            {{--style="height: 15px; width: 15px; cursor: pointer; cursor: pointer;"/>--}}
                            {{--<img onclick="shareweibo('netease')" title="分享到网易微博" alt="分享到网易微博"--}}
                            {{--src="/resources/weiboimg/163.png"--}}
                            {{--style="height: 15px; width: 15px; cursor: pointer; cursor: pointer;"/>--}}
                            <img onclick="shareweibo('facebook')" title="分享到FACEBOOK" alt="分享到FACEBOOK"
                                 src="/resources/weiboimg/fb.png"
                                 style="height: 15px; width: 15px; cursor: pointer; cursor: pointer;"/>
                                    <img onclick="shareweibo('twitter')" title="分享到TWITTER" alt="分享到TWITTER"
                                         src="/resources/weiboimg/twitter.png"
                                         style="height: 15px; width: 15px; cursor: pointer; cursor: pointer;"/>
                                    <img onclick="shareweibo('plurk')" title="分享到PLURK" alt="分享到PLURK"
                                         src="/resources/weiboimg/plurk.png"
                                         style="height: 15px; width: 15px; cursor: pointer; cursor: pointer;"/>
                            @if(Functions::isHTTPS())
                                <a href="https://letsencrypt.org/" title="加密证书由Let's Encrypt提供" target="_blank"
                                   alt="加密证书由Let's Encrypt提供"><img
                                            src="/resources/img/letsencrypt-logo-horizontal.svg"
                                            style="height: 15px; width: 63px; cursor: pointer; cursor: pointer;"/>
                                </a>
                            @endif
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@include('universal.footer.sharejs')
</body>
</html>