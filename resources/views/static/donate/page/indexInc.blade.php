<!--suppress HtmlUnknownTarget -->
<div class="error-page" style="overflow:hidden;padding-top: 20px;">
    <p style="font-size:42px;text-align: left;">捐赠</p>
    <p style="padding-top: 10px;text-align: left;">
        我利用闲暇时间开发了{{config('app.name')}}网站，我并没有希望这个网站能给我提供多少收入，但是我还是继续添加功能，更新代码。<br>
        有些认识我的用户也知道，我做了很多东西，但是从来都没有强制要求对这些东西收费，但是实际情况是，服务器、域名、流量,每一项都需要我自己维持一定开支。<br>
        随着用户的增加，这些花销也是越来越大，所以您的一点点捐赠对我和我的服务而言都是极大的支持，如果我能得到一些让人快乐的东西，那真是极好的！<br>
        随着我租用了阿里云服务器，网站的访问速度大大加快，但每个月需要100余元才能维持下去，希望您在有能力的情况下可以做一些捐赠，让网站的使用体验更好！
    </p>
    <br>
    <hr>
    @if(!empty(config('app.admin_username')) && $_USER->getIsLogin() && strtoupper(config('app.admin_username'))==strtoupper($_USER->getUserName()))
        <p style="font-size:32px;text-align: left;padding-top: 5px;">管理员功能</p>
        <p style="padding-top: 10px;text-align: left;">
            {{strtoupper($_USER->getUserName())}}，您好，您是管理员账户，如要添加捐赠信息，请到<a href="addDonate">添加捐赠</a>页面操作。<br>
            如果要封禁用户，请到<a href="banUser">封禁用户</a>页面操作。
        </p>
        <br>
        <hr>
    @endif
    <p style="font-size:32px;text-align: left;padding-top: 5px;">您能得到什么</p>
    <p style="padding-top: 10px;text-align: left;">
        如果您一次性捐款超过100元人民币或者15美元或与美元等额的比特币，账号安全令数量限制提高到{{config('app.auth_max_count_donated_user')}}个，
        您可以比普通用户多增加{{config('app.auth_max_count_donated_user')-config('app.auth_max_count_standard_user')}}枚安全令。<br>
        如需增加数量限制，请将您的账号名称和捐赠截图通过邮件发送至<a href="mailto:{{config('app.app_host_email')}}">我的邮箱。</a>
    </p>
    <br>
    <hr>
    <p style="font-size:32px;text-align: left;padding-top: 5px;">捐赠方式</p>
    <br>
    <div class="error-header" style="font-size:32px;margin-bottom: 20px;">
        比特币地址/Bitcoin Address
    </div>
    <div class="error-desc">1yE2qmvj89QG1gVL9zghPYsyUTWu3S222</div>
    <br>
    <br>
    <div class="error-header" style="font-size:32px;margin-bottom: 20px;">贝宝/Paypal</div>
    <div class="error-desc">
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
            <input type="hidden" name="cmd" value="_donations">
            <input type="hidden" name="business" value="ymback@sayyoulove.me">
            <input type="hidden" name="lc" value="US">
            <input type="hidden" name="item_name" value="Donate to Battlenet Authenticator Online">
            <input type="hidden" name="return" value="{{config('app.url')}}/donate"/>
            <input type="hidden" name="cancel_return" value="{{config('app.url')}}/donate"/>
            <input type="hidden" name="no_note" value="0">
            <input type="hidden" name="currency_code" value="USD">
            <input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">
            <input type="image" src="https://www.paypalobjects.com/zh_XC/i/btn/btn_donateCC_LG.gif" border="0"
                   name="submit" alt="向{{config('app.name')}}捐赠">
            <img alt="" border="0" src="https://www.paypalobjects.com/zh_XC/i/scr/pixel.gif" width="1" height="1">
        </form>
        <br>
        注意：由于Paypal需要收取手续费，每次0.3刀加总额3%左右，所以捐太少就等于捐给Paypal了，不如使用支付宝。
    </div>
    <br>
    <br>
    <div class="error-header" style="font-size:32px;margin-bottom: 20px;">支付宝/Alipay
    </div>
    <div class="error-desc">
        <img src="/resources/img/alipayqr.png">
    </div>
    <br>
    <br>
    <div class="error-header" style="font-size:32px;margin-bottom: 20px;">微信/WeChat
    </div>
    <div class="error-desc">
        <img src="/resources/img/wechatqr.png">
    </div>
    <br>
    <hr>
    <br>
    <h2>土豪榜</h2>
    <br>
    <div class="data-container type-table">
        <table>
            <thead>
            <tr class="">
                <th align="center"><span class="arrow">
                                            土豪的名字</span>
                </th>
                <th align="center"><span class="arrow">
                                            啥时候捐的
                                        </span></th>
                <th align="center"><span class="arrow">
                                            捐了啥
                                        </span></th>
                <th align="center">
                                        <span class="arrow">
                                            捐了多少
                                        </span>
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach(DonateInfoListModel::initDonateInfo() as $donateInfo)
                <tr class="parent-row">
                    <td class="normaltd authbianhaoa" valign="top">{{$donateInfo->getDonateName()}}</td>
                    <td class="normaltd authbianhaoa" valign="top">{{date("Y-m-d",$donateInfo->getDonateTime())}}</td>
                    <td class="normaltd authbianhaoa" valign="top">{{$donateInfo->getDonateBiZhong()}}</td>
                    <td class="normaltd authbianhaoa" valign="top">{{$donateInfo->getDonateCount()}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>