<div id="layout-middle">
    <div id="homewrapper">
        <div id="content">
            <div id="page-content">
                <div id="breadcrumb">
                    <ol class="ui-breadcrumb">
                        <li><a href="/">首页</a>
                        </li>
                        <li class="last">
                            <a href="/about">关于</a>
                        </li>
                    </ol>
                </div>
                <div class="article-column">
                    <div id="article-container">
                        <div id="article">
                            <div class="article-games">
                                <a href="/"><img src="/resources/img/auth.png" alt=""></a>
                            </div>
                            <h2 id="article-title"> 欢迎使用{{config('app.name')}} </h2>
                            <div id="article-content">
                                <h3 class="article-ci"> 关于本站 </h3>
                                <p>
                                    本站是由{{config('app.developer_name')}}开发的{{config('app.blizzard_auth_name')}}颁发、动态验证码查看、一键安全令登录站点<br>
                                    本站属于业余开发，请不要与专业团队相提并论，开发人员比较呆蠢笨，容易犯错误，如发现BUG请<a
                                            href="mailto:{{env('APP_HOST_EMAIL')}}">联系我们</a>
                                </p>
                                <p>
                                    {{config('app.name')}}是面向个人用户的非盈利性服务，{{config('app.owner')}}不会为本站的基本服务向您强制收取任何费用，您所享受的一切基本服务都是免费的<br>
                                    请认准本站唯一域名<a href="{{config('app.url')}}">{{config('app.url')}}</a>，虽然我们不钓鱼，但是其他网站说不定哪天就来钓本站的鱼了
                                </p>
                                <h3 class="article-ci"> 大事记 </h3>
                                <p>
                                    2014年2月26日正式版上线，修复几个错误，同时邮件地址认证功能实装了。<br>
                                    2015年6月22日新版上线，修复多个BUG，使用非对称加密技术确保密码传输过程中的安全性。<br>
                                    2016年6月28日新增一键安全令登录功能，更加方便的同时也更加安全。<br>
                                    2017年2月24日切换至Linode服务器，使用LNMP架构替代LAMP。<br>
                                    2017年5月20日代码重构完成，使用Laravel框架开发，Mysql存储，Redis作为缓存，继续以开放的态度公开了源代码。<br>
                                    2017年5月26日网站获得ICP备案证书，切换至阿里云服务器，服务器性能提升，访问更快，当然花销也更大了，求<a
                                            href="/donate">捐赠</a>。
                                </p>
                                <h3 class="article-ci"> 开放源代码 </h3>
                                <p>
                                    开放源代码地址：<a href="https://github.com/ymback/Battle.net-Authenticator-Online">https://github.com/ymback/Battle.net-Authenticator-Online</a>，License：GNU
                                    GPL v3。
                                </p>
                                <!--<p>麻烦用我的Github里的源码建站的朋友留下点我的信息谢谢啊，我看的有个网站把我的信息全给删了，还把开发者叫XX，您这样也算是混Git的？</p>-->
                                <h3 class="article-ci"> 本站认证 </h3>
                                <p class="alignleft" style="font-size: 16px;margin-bottom: 0px;">①360网站安全认证</p>
                                <a href="http://webscan.360.cn/index/checkwebsite/url/www.myauth.us">
                                    <img style="margin-top:3px;border:0;"
                                         src="http://img.webscan.360.cn/status/pai/hash/295153ef7bd118d60bd1e664e13de7a9"
                                         alt=""></a>
                                <p class="alignleft" style="font-size: 16px;margin-bottom: 0px;">②W3C-HTML代码验证</p>
                                <a href="http://validator.w3.org/check?uri=referer"><img
                                            src="/resources/img/valid-xhtml10.png"
                                            style="margin-top:3px;"
                                            alt="Valid XHTML 1.0 Transitional" height="31" width="88"/></a>
                                <p class="alignleft" style="font-size: 16px;margin-bottom: 0px;">③W3C-CSS3代码验证</p>
                                <a href="http://jigsaw.w3.org/css-validator/check/referer">
                                    <img style="margin-top:3px;"
                                         src="/resources/img/vcss-blue.gif"
                                         alt="Valid CSS!"/>
                                </a>

                                {{--<p class="alignleft" style="font-size: 16px;margin-bottom: 0px;">④IPv6启用认证</p>--}}
                                {{--<div id=ipv6_enabled_www_test_logo>--}}
                                {{--<script language="JavaScript" type="text/javascript">--}}
                                {{--var Ipv6_Js_Server = (("https:" == document.location.protocol) ? "https://" : "http://");--}}
                                {{--document.write(unescape("%3Cscript src='" + Ipv6_Js_Server + "www.ipv6forum.com/ipv6_enabled/sa/SA.php?id=4376' type='text/javascript'%3E%3C/script%3E")); </script>--}}
                                {{--</div>--}}
                                <br>
                                <br>
                                <h3 class="article-ci"> 版权所有 </h3>
                                <p>{{config('app.name')}} ©2013-{{date('Y')}} {{config('app.owner')}} <br>All rights
                                    reserved
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>