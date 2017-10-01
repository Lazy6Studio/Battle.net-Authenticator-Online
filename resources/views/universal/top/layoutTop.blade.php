</head>
<body>
<div id="{{$topLayoutId or 'layout-top'}}">
    <div id="topwrapper">
        @if(@!$dbError && @$_USER->getIsLogin())
            <div id="topnav">
                <ul class="top-nav">
                    <li class="top-core top-home">
                        <a href="/" title="首页"> </a>
                    </li>
                    <li class='top-core top-all'>
                        欢迎，{{strtoupper($_USER->getUserName())}}&nbsp;|&nbsp;
                        <a onclick="return confirm('您确认要登出吗');" href="/logout">登出</a>
                    </li>
                    <li class='top-core top-data'>
                        <a href='/account'>账号管理</a>
                    </li>
                    <li class='top-core top-data'>
                        <a href='/myAuthList'>我的安全令</a>
                    </li>
                    <li class='top-core top-final'>
                        <a href='/donate'>捐赠</a>
                    </li>
                </ul>
            </div>
        @else
            <div id="topnav">
                <ul class="top-nav">
                    <li class="top-core top-home">
                        <a href="/" title="首页"> </a>
                    </li>
                    <li class='top-core top-all'>
                        <a href='/login' @if(@$isIndex)onclick='return Login.open()'@endif>登入</a>
                        <span style="    font-size: 11px;">或</span> <a
                                href='/register'>注册一个账号</a>
                    </li>
                    <li class='top-core top-data'>
                        <a href="/faq">FAQ</a>
                    </li>
                    <li class='top-core top-data'>
                        <a href='/account'>账号管理</a>
                    </li>
                    <li class='top-core top-final'>
                        <a href='/donate'>捐赠</a>
                    </li>
                </ul>
            </div>
        @endif
        @if(@!$dbError)
            @if(@!empty($topNavValueText))
                <div id="header" style="height:170px;">
                    <div id="toplogo">
                        <a href="/" title="首页"><img src="/resources/img/bn-logo.png" alt=""></a>
                        <div id="navigation">
                            <div id="page-menu" class="large"><h2 id="isolated"
                                                                  class="isolated">{{$topNavValueText}}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div id="header" style="height:130px;">
                    <div id="toplogo">
                        <a href="/" title="首页"><img src="/resources/img/bn-logo.png" alt=""></a>
                    </div>
                </div>
            @endif
        @else
            <div id="header" style="height:130px;">
                <div id="toplogo">
                    <a href="/" title="首页"><img src="/resources/img/bn-logo.png" alt=""></a>
                </div>
            </div>
        @endif
    </div>
</div>

