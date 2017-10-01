<div id="lobby-account">
    <h3 class="section-title">账号信息</h3>
    <div class="lobby-box">
        <h4 class="subcategory">账号名称</h4>
        <p>{{$_USER->getUserName()}}</p>
        <h4 class="subcategory">账号权限</h4>
        <p>
            @if($_USER->getUserRight() == $_USER::USER_NORMAL)
                @if($_USER->getUserDonated()==1)
                    捐赠者账号:全权限
                @else
                    普通账号:全权限
                @endif
            @else
                共享账号:不能获取序列号和还原码
            @endif
        </p>
        <h4 class="subcategory">邮箱</h4>
        <p>
            @if($_USER->getUserRight() == $_USER::USER_NORMAL)
                {{$_USER->getUserEmail()}}
            @else
                共享账号隐藏邮箱信息
            @endif
        </p>
        <h4 class="subcategory">微信小程序绑定状态</h4>
        <p id="wechatBindInfo">
            @if(empty($_USER->getWechatOpenID()))
                未绑定{{$_USER->getWechatOpenID()}}
            @else
                已绑定 <span id="unBindWechat" class="edit">[<a style="cursor:pointer;"
                                                             onclick="unBindWechat();"> 解除绑定 </a>]</span>
            @endif
        </p>
        <h4 class="subcategory">上次登录时间</h4>
        <p>
            {{date('Y年m月d日 H:m:s',strtotime($_USER->getUserLastLoginTime()))}}
        </p>
        <h4 class="subcategory">上次登录IP地址</h4>
        <p id="lastLoginIpAddress">
            {{$_USER->getUserLastTimeLoginIP()}}
        </p>
    </div>
    <h3 class="section-title">账号状态</h3>
    <div class="lobby-box security-box">
        <h4 class="subcategory">邮箱地址确认状态</h4>
        @if($_USER->getUserEmailChecked()==1)
            <p class="has-authenticator-has-active">已确认</p>
        @else
            <p class="none-authenticator">未确认
                <span id="mailcheck" class="edit">[
                    <a style="cursor:pointer;"
                       onclick="renewemail();alert('如果您一直无法收到确认邮件,请前往邮箱设置{{config('mail.username')}}为白名单')"> 重新发送验证邮件 </a>
                    ]</span>
            </p>
        @endif
        <h4 class="subcategory">已添加安全令数量</h4>
        @if($_USER->getUserDonated()==1)
            @if($authUtils->getAuthCount()<round(config('app.auth_max_count_donated_user')*config('app.auth_hint_logo_donated_coefficient')))
                <p class='has-authenticator-has-active'>
                    {{$authUtils->getAuthCount()}}/{{config('app.auth_max_count_donated_user')}}
                </p>
            @elseif($authUtils->getAuthCount()<config('app.auth_max_count_donated_user'))
                <p class='has-authenticator-none-active'>
                    {{$authUtils->getAuthCount()}}/{{config('app.auth_max_count_donated_user')}}
                </p>
            @else
                <p class='none-authenticator'>
                    {{$authUtils->getAuthCount()}}/{{config('app.auth_max_count_donated_user')}}
                </p>
            @endif
        @else
            @if(config('app.auth_max_count_standard_user')==1)
                @if($authUtils->getAuthCount()==0)
                    <p class='has-authenticator-has-active'>
                        {{$authUtils->getAuthCount()}}/{{config('app.auth_max_count_standard_user')}}
                    </p>
                @else
                    <p class='none-authenticator'>
                        {{$authUtils->getAuthCount()}}/{{config('app.auth_max_count_standard_user')}}
                    </p>
                @endif
            @elseif(config('app.auth_max_count_standard_user')==2)
                @if($authUtils->getAuthCount()==0)
                    <p class='has-authenticator-has-active'>
                        {{$authUtils->getAuthCount()}}/{{config('app.auth_max_count_standard_user')}}
                    </p>
                @elseif($authUtils->getAuthCount()==1)
                    <p class='has-authenticator-none-active'>
                        {{$authUtils->getAuthCount()}}/{{config('app.auth_max_count_standard_user')}}
                    </p>
                @else
                    <p class='none-authenticator'>
                        {{$authUtils->getAuthCount()}}/{{config('app.auth_max_count_standard_user')}}
                    </p>
                @endif
            @else
                @if($authUtils->getAuthCount()<(round(config('app.auth_max_count_standard_user')*config('app.auth_hint_logo_normal_coefficient'))+1))
                    <p class='has-authenticator-has-active'>
                        {{$authUtils->getAuthCount()}}/{{config('app.auth_max_count_standard_user')}}
                    </p>
                @elseif($authUtils->getAuthCount()<config('app.auth_max_count_standard_user'))
                    <p class='has-authenticator-none-active'>
                        {{$authUtils->getAuthCount()}}/{{config('app.auth_max_count_standard_user')}}
                    </p>
                @else
                    <p class='none-authenticator'>
                        {{$authUtils->getAuthCount()}}/{{config('app.auth_max_count_standard_user')}}
                    </p>
                @endif
            @endif
        @endif
    </div>
</div>