<!--suppress SpellCheckingInspection -->
<div id="layout-middle">
    <div id="homewrapper">
        <div id="content">
            <div id="page-content" style="height: 2914px;">
                <div id="breadcrumb">
                    <ol class="ui-breadcrumb">
                        <li><a href="">首页</a></li>
                        <li class="last">
                            <a href="/welcome">WELCOME</a>
                        </li>
                    </ol>
                </div>
                <div class="article-column">
                    <div id="article-container">
                        <div id="article">
                            <div class="article-games">
                                <a href="/"><img src="/resources/img/auth.png" alt=""></a>
                            </div>
                            <h2 id="article-title"> 欢迎使用{{config('app.name')}}</h2>
                            <div id="article-content">
                                <p>
                                    欢迎访问{{config('app.name')}}，首先请注意，这里不是官方的站点，{{config('app.name')}}是由{{config('app.developer_name')}}开发的第三方网站<br>在这里，您能通过在线方式生成、还原一枚安全令，这些安全令可以用于战网及其游戏的登入<br>
                                    包括且不限于{{config('app.game_list')}}<br>
                                    您可以使用任何连入国际互联网的计算机访问我们，并获得您的动态验证码，出门办事，再也不必担心手机没电/压根没带导致不能登入游戏的情况发生<br>
                                    @if(!Functions::isHTTPS())
                                        如果您想使用安全的版本，请访问<a href="https://{{config('app.simpleUrl')}}"
                                                          title="SSL加密版{{config('app.blizzard_auth_name')}}手机版">https://{{config('app.simpleUrl')}}
                                        </a>，安全的版本将保证您的各项数据不被不良程序截获
                                    @else
                                        如果您想使用快速的版本，请访问<a
                                                href="http://{{config('app.simpleUrl')}}" title="普通版{{config('app.blizzard_auth_name')}}手机版">http://{{config('app.simpleUrl')}}
                                        </a>，您可以获得较快的访问体验
                                    @endif
                                    <br>您也可以使用我们的微信小程序服务，请使用微信扫描下方的小程序码使用<br>
                                    <img src="/resources/img/mini-program-qr-code.jpg" alt=""><br>
                                </p>
                                <p>
                                    我们支持CN/EU/US三大安全令颁发服务器的安全令申请，各服务器颁发的安全令的主要使用国家或地区如下：<br>
                                    CN：中国大陆(国服)<br>
                                    EU：欧洲诸国(欧服)<br>
                                    US：美国/巴西(美服)、韩国(韩服)、台湾地区/香港地区/澳门地区/新加坡等南洋诸国(台服)
                                </p>
                                <p>
                                    通过我们申请或还原的安全令与官方APP显示的验证码一致，验证码刷新时间误差基本在3秒以内，误差取决于您连接到我们服务器的延迟
                                </p>
                                <p>
                                    若想要使用我们的服务，请<a href="/login">登入</a>或<a href="/register">注册</a>您的账号
                                </p>
                                <p>
                                    访问<a href="/addAuth">添加安全令</a>来为您的账号添加一枚安全令，访问<a href="/auth">默认安全令</a>查看您设置的默认安全令。<br>
                                    每个账号最多可以添加&nbsp;{{config('app.auth_max_count_standard_user')}}&nbsp;枚安全令<br/>
                                    如果您成为捐赠用户，您最多可以添加&nbsp;{{config('app.auth_max_count_donated_user')}}&nbsp;枚安全令<br>
                                    如果您已经<a href="/login">登入</a>且拥有一枚<a href="/auth">默认安全令</a>，那么首页将显示默认安全令的动态验证码，您可以保存成书签方便今后查看<br>
                                    在安全令页面中您可以点击刷新安全令验证码按钮刷新显示的动态验证码与时间滚动条，点击复制安全令验证码按钮复制当前的安全令验证码<br>
                                    <img src="/resources/img/auth.jpg" alt=""><br><br>
                                    访问<a href="/myAuthList">我的安全令</a>来管理您的安全令，您可以在该页面中校准安全令时间、设置默认安全令、删除已有安全令、更改安全令名称<br>
                                    <img src="/resources/img/myauth.jpg" alt="">
                                </p>
                                <p>
                                    Ps:2016年6月起，战网官方推出了一键登录功能，您可以在战网或者其客户端上登录，在确认安全令页面，现在会显示如下页面<br>
                                    <img src="/resources/img/netpageinfo.png" alt=""><br><br>
                                    现在使用{{config('app.name')}}，您只需要在弹窗中点击允许即可登录，或点击拒绝取消登录请求，而无需再输入安全令，是的，我们现已支持该功能<br>
                                    <img src="/resources/img/onkeyintro.png" alt="">
                                </p>
                                <p>
                                    如果忘记密码请前往<a href="/forgetPassword">重置密码</a>页面，
                                    如要管理账号请前往<a href="/account">我的账号</a>页面<br>
                                    在<a href="/account">我的账号</a>页面中您可以查看账号资料、修改密码、修改邮箱、下载安全令备份CSV文件等<br>
                                    请务必记住您的注册信息，该信息将在您重置密码、更改邮箱地址时使用。请
                                    <span style="color:red;">绝对</span>
                                    不要使用与您的{{config('app.game_list')}}相同的密码，以免发生纠纷<br>
                                    注册前请仔细查看<a href="/copyright">版权声明与免责条款</a>，您未来可能发生的一切账号失窃事件均与本站无关，重复一遍，请
                                    <span style="color:red;">绝对</span>
                                    不要使用与您的{{config('app.game_list')}}相同的密码
                                </p>
                                <p>
                                    更多使用问题，请参访<a href="/faq">FAQ</a>
                                </p>
                                <p>
                                    {{config('app.game_list')}}等商标及其Logo均为©美国暴雪娱乐有限公司所有
                                </p>
                                <p>
                                    源于一个简单的想法
                                </p>
                                <p style="text-align: right;margin-bottom:0px;">
                                    {{config('app.owner')}}<br>2013-06-20
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
