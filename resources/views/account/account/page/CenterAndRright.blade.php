<div id="lobby-games">
    <h3 class="section-title">安全令管理&添加</h3>
    <div id="games-list">
        @if($authUtils->getAuthCount()<5)
            <a class="games-title border-2 opened" href="#wow" id="aforwowjq" rel="game-list-wow">我的安全令</a>
            <ul id="game-list-wow" style="display: block;">
                @else
                    <a class="games-title border-2 closed" href="#wow" id="aforwowjq" rel="game-list-wow">我的安全令</a>
                    <ul id="game-list-wow" style="display: none;">
                        @endif
                        @if($authUtils->getAuthCount()>0)
                            @foreach($authUtils->getAuthList() as $auth)
                                <li class="border-4">
                        <span class="game-icon">
                            <span class="png-fix">
                                <img width="32" height="32"
                                     src="{{$authUtils->getAuthImageUrls()[$auth->getAuthImage()]}}"
                                     alt=""/>
                            </span>
                        </span>
                                    <span class="account-info">
                            <span class="account-link">
                                <strong>
                                    <a class="CN-WOW-mop-se"
                                       href="/auth?{{HttpFormConstant::FORM_KEY_AUTH_ID}}={{$auth->getAuthId()}}">
                                        @if($auth->getAuthDefault())
                                            默认安全令：{{$auth->getAuthName()}}
                                        @else
                                            安全令名称：{{$auth->getAuthName()}}
                                        @endif
                                    </a>
                                </strong>
                                <span class="account-id">序列号：
                                    @if($_USER->getUserRight() == $_USER::USER_NORMAL)
                                        {{$auth->getAuthSerial()}}
                                    @else
                                        共享账号无法获得该信息
                                    @endif
                                    </span>
                                <span class="account-region">安全令ID：{{$auth->getAuthId()}}</span>
                            </span>
                        </span>
                                </li>
                            @endforeach
                        @endif
                        @if(
                        ($_USER->getUserDonated()== 1 && $authUtils->getAuthCount()<config('app.auth_max_count_donated_user'))
                        ||
                        ($authUtils->getAuthCount()<config('app.auth_max_count_standard_user'))
                        )
                        <!--添加账号-->
                            <li id="addWowTrial" class="trial no-subtitle border-4">
                                        <span class="game-icon">
                                            <span class="png-fix"><img width="32" height="32"
                                                                       src="resources/img/bga.png" alt=""></span></span>
                                <span class="account-info">
                                            <span class="account-link">
                                                <strong>
                                                    <a class="CN-WOW-mop-se" href="/addAuth">{{config('app.name')}}</a>
                                                </strong>
                                                <span class="account-region">中国(CN) 美国(US) 欧洲(EU)</span>
                                            </span>
                    </span>
                                <strong class="action add-trial">添加令牌</strong>
                            </li>
                        @endif
                    </ul>
    </div>
    <div id="games-tools">
        @if(
        ($_USER->getUserDonated()== 1 && $authUtils->getAuthCount()<config('app.auth_max_count_donated_user'))
        ||
        ($authUtils->getAuthCount()<config('app.auth_max_count_standard_user'))
        )
            <a id="add-time" class="border-5" href="/addAuth">添加安全令</a>
        @endif
        <div style="margin-top: 7px;">
            @if($_USER->getUserRight() == $_USER::USER_NORMAL)
                <p>
                    <a class="" href="/changeEmailAddress" onclick="">
                        <span class="icon-16 icon-account-mail"></span>
                        <span class="icon-16-label">修改邮箱地址</span>
                    </a>
                </p>
                <p>
                    <a class="" href="/changePassword" onclick="">
                        <span class="icon-16 icon-account-safe"></span>
                        <span class="icon-16-label">修改密码</span>
                    </a>
                </p>
            @endif
            <p>
                <a class="" href="/myAuthList" onclick="">
                    <span class="icon-16 icon-account-auth"></span>
                    <span class="icon-16-label">我的安全令</span>
                </a>
            </p>
            @if($_USER->getUserRight() == $_USER::USER_NORMAL && $authUtils->getAuthCount()>0)
                <p>
                    <a class="" href="/MyAuthList.csv" onclick="">
                        <span class="icon-16 icon-account-download"></span>
                        <span class="icon-16-label">下载我的安全令备份CSV</span>
                    </a>
                </p>
            @endif
            <p>
                <a class="" href="/faq" onclick="">
                    <span class="icon-16 icon-account-faq"></span>
                    <span class="icon-16-label">FAQ</span></a>
            </p>
            <p>
                <a class="" href="/copyright" onclick="">
                    <span class="icon-16 icon-account-copyright"></span>
                    <span class="icon-16-label">版权声明及免责条款</span>
                </a>
            </p>
        </div>
    </div>
</div>
