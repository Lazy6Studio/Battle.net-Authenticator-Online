<body style="margin: 0;padding: 0;border: 0px none;outline: 0px none;">
<div style="background:#040404 url({{config('app.url')}}resources/img/bnbg.jpg) 50% 0 no-repeat;color: #4a4a4a;font-family:微软雅黑,Microsoft YaHei,Helvetica,Tahoma,StSun,宋体,SimSun,sans-serif;font-size: 12px;margin: 0px;padding: 0px;border: 0px none;outline: 0px none;">
    <div id="layout-top"
         style="background: url({{config('app.url')}}resources/img/bgtop.png) repeat-x scroll center bottom transparent;position: relative;margin: 0;padding: 0;border: 0;outline: 0;">
        <div id="topwrapper"
             style="width: 990px;text-align: left;position: relative;background: transparent top left repeat-y;margin: 0 auto;padding: 0;border: 0;">
            <div id="topnav" style="margin: 0;padding: 0;border: 0;outline: 0;">
                <ul style="position:relative;z-index: 75;font-size: 11px;display: inline-block;text-align: right;float: right;list-style: none outside none;margin: 0;padding: 0;border: 0;outline: 0;">
                    <li style="margin: 0;border: 0;outline: 0;font-size: 12px;position: relative;background: url({{config('app.url')}}resources/img/background.png) no-repeat 0 0;color: #8694A1;display: block;float: left;line-height: 32px;padding: 0 0 7px 0;">
                        <a href="{{config('app.url')}}" title="首页" target="_blank"
                           style="display: block;line-height: 33px;border-left: 0;text-indent: -9999px;width: 48px;padding: 0 0 0 7px;background: url({{config('app.url')}}resources/img/background.png) no-repeat 0 -80px;color:#00B6FF;font-size: 11px;text-decoration: none;"> </a>
                    </li>
                    <li
                            style="margin: 0;border: 0;outline: 0;padding: 0 15px 7px 15px;font-size: 12px;position: relative;background: url({{config('app.url')}}resources/img/background.png) repeat-x 0 -200px;color: #8694A1;display: block;float: left;line-height: 32px;">
                        欢迎使用{{config('app.name')}}邮件通知服务
                    </li>
                    <li
                            style="margin: 0;border: 0;outline: 0;line-height: 33px;padding: 0 1.5em;border-left: 1px solid #33373B;-moz-border-radius-topleft: 3px;-moz-border-radius-bottomright: 3px;-webkit-border-bottom-right-radius: 3px;-webkit-border-top-right-radius: 3px;border-radius: 0px 3px 3px 0px;
                                    font-size: 12px;position: relative;background: url({{config('app.url')}}resources/img/background.png) repeat-x 0 -200px;color: #8694A1;display: block;float: left;">
                        <a href='{{config('app.url')}}donate' target="_blank"
                           style="color: #00B6FF;font-size: 11px;text-decoration: none;">捐赠</a>
                    </li>
                </ul>
            </div>
            @if(@!$dbError)
                @if(@!empty($topNavValueText))
                    <div id="header" style="height:170px;clear: both;margin: 0;padding: 0;border: 0;outline: 0;">
                        <div id="toplogo" style="margin: 0;padding: 0;border: 0;outline: 0;">
                            <a href="{{config('app.url')}}" title="首页" target="_blank"
                               style="color: #0072a3;text-decoration: none;"><img style="user-select: none;"
                                                                                  src="{{config('app.url')}}resources/img/bn-logo.png"
                                                                                  alt=""></a>
                            <div id="navigation"
                                 style="margin: 0;border: 0;outline: 0;padding: 15px 20px 0 0;height: 61px;">
                                <div id="page-menu" style="margin: 0;padding: 0;border: 0;outline: 0;">
                                    <h2 id="isolated"
                                        style="padding: 0;outline: 0;border: 0 none;color: rgb(137,149,154);font-weight: normal;float: left;margin: 0 20px 0 0;padding-right: 20px;font-size: 36px;">{{$topNavValueText}}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div id="header" style="height:170px;clear: both;margin: 0;padding: 0;border: 0;outline: 0;">
                        <div id="toplogo" style="margin: 0;padding: 0;border: 0;outline: 0;">
                            <a href="{{config('app.url')}}" title="首页" target="_blank"
                               style="color: #0072a3;text-decoration: none;"><img style="user-select: none;"
                                                                                  src="{{config('app.url')}}resources/img/bn-logo.png"
                                                                                  alt=""></a>
                        </div>
                    </div>
                @endif
            @else
                <div id="header" style="height:170px;clear: both;margin: 0;padding: 0;border: 0;outline: 0;">
                    <div id="toplogo" style="margin: 0;padding: 0;border: 0;outline: 0;">
                        <a href="{{config('app.url')}}" title="首页" target="_blank"
                           style="color: #0072a3;text-decoration: none;"><img style="user-select: none;"
                                                                              src="{{config('app.url')}}resources/img/bn-logo.png"
                                                                              alt=""></a>
                    </div>
                </div>
            @endif
        </div>
    </div>

