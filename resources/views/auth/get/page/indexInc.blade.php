<div id="layout-middle">
    <div id="homewrapper">
        <div id="content">
            <div id="page-content">
                <div id="breadcrumb">
                    <ol class="ui-breadcrumb">
                        <li><a href="/">首页</a></li>
                        <li class="last"><a href="{{$pageUrl}}">{{$topNavValueText}}</a></li>
                    </ol>
                </div>
            </div>
            <div id="mianbiantai">
                <div id="manage-security" class="manage-security">
                    <div id="dashboard" class="dashboard">
                        <h4 class="supporting">
                            @if($fromGetDefault)默认安全令@else我的安全令@endif：{{$authBean->getAuthName()}}
                        </h4>
                        <div class="security-image">
                            <img width="160" height="230" src="resources/img/auth-icon.png?SDS=\1" alt="" title=""/>
                        </div>
                        <div>
                            <div class="auth-info-box">
                                <div class="section details">
                                    <dl>
                                        <dt class="subcategory">类别</dt>
                                        <dd>
                                            <strong class="active">
                                                @if($authBean->getAuthDefault())默认安全令@else普通安全令@endif
                                            </strong>
                                        </dd>
                                    </dl>
                                </div>
                                <div class="section actions">
                                    <ul>
                                        <li class="remove-authenticator">
                                            <a class="icon-delete"
                                               href="/deleteAuth?{{HttpFormConstant::FORM_KEY_AUTH_ID}}={{$authBean->getAuthId()}}"
                                               onclick="return confirm('该操作将删除这枚安全令，确定吗？');">
                                                删除这枚安全令
                                            </a>
                                        </li>
                                        <li class="read-faq">
                                            <a class="icon-help" href="/copyright">
                                                版权声明及免责条款
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="authenticator">
                                <span id="authcode">LOADNOW</span>
                                <div class="processbardiv">
                                    <strong id="authprogressbar" class="authprogress" style="width:0%;"></strong>
                                </div>
                            </div>
                            <div id="rightajaxzhuangtai" class="rightajaxzhuangtai"></div>
                            <div id="copydatamode" class="copydatamode"></div>
                            <button class="ui-button button1" id="creation-submit" tabindex="1"
                                    data-clipboard-text="">
                                <span class="button-left"><span class="button-right">复制令牌验证码</span></span>
                            </button>
                            <button class="ui-button button1" id="refreshcode" onclick="refreshcodegeas();" tabindex="1">
                                <span class="button-left"><span class="button-right">刷新令牌验证码</span></span>
                            </button>
                        </div>
                        <h3 class="baoguanhaomima">请妥善保管验证码，不要将其随意告知他人。<br>
                            若安全令显示不准确，请访问<a href="/myAuthList">我的安全令</a>对令牌重新进行校时。
                        </h3>
                    </div>
                </div>
                <div id="lobby-games">
                    <div id="games-list">
                        @if($authUtils->getAuthCount()<4)
                            <a class="games-title border-2 opened" href="#wow" id="aforwowjq"
                               rel="game-list-wow">我的安全令</a>
                            <ul id="game-list-wow" style="display: block;">
                                @else
                                    <a class="games-title border-2 closed" href="#wow" id="aforwowjq"
                                       rel="game-list-wow">我的安全令</a>
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
                                            <li id="addWowTrial" class="trial no-subtitle border-4">
                                    <span class="game-icon">
                                        <span class="png-fix">
                                            <img width="32" height="32" src="resources/img/bga.png" alt=""/>
                                        </span>
                                    </span>
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
                </div>
            </div>
        </div>
    </div>
</div>
