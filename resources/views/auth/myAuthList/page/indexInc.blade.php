<div id="layout-middle">
    <div id="homewrapper">
        <div id="content">
            <div id="page-header">
                <h2 class="subcategory">
                    {{config('app.blizzard_auth_name')}}
                </h2>
                <h3 class="headline"> 我的全部安全令 </h3>
                <div id="youshangfangtianjiaABC" class="youshangfangtianjia">
                    @if(
                    ($_USER->getUserDonated()== 1 && $authUtils->getAuthCount()<config('app.auth_max_count_donated_user'))
                    ||
                    ($authUtils->getAuthCount()<config('app.auth_max_count_standard_user'))
                    )
                        <a class="ui-button button1" href="/addAuth">
                            <span
                                    class="button-left"><span class="button-right">
                                    添加一个安全令
                                </span>
                            </span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div id="page-content" class="page-content">
            <div id="order-history">
                <div class="data-container type-table">
                    <table>
                        <thead>
                        <tr class="">
                            <th align="left"><span class="arrow">
                                        安全令编号</span>
                            </th>
                            <th align="left"><span class="arrow">
                                        名称(双击文字可修改)
                                    </span></th>
                            <th align="left">
                                    <span class="arrow">
                                        序列号
                                    </span>
                            </th>
                            <th align="left">
                                    <span class="arrow">
                                        还原码
                                    </span>
                            </th>
                            <th align="left">
                                    <span class="arrow">
                                        最后一次与暴雪服务器同步时间
                                    </span>
                            </th>
                            <th align="center"><span class="arrow">
                                        重新同步
                                    </span></th>
                            <th align="center"><span class="arrow">
                                        默认安全令
                                    </span></th>
                            <th align="center"><span class="arrow">
                                        删除
                                    </span></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($authUtils->getAuthList() as $auth)
                            <tr class="parent-row" id="henxiangtr{{$auth->getAuthId()}}">
                                <td onclick="location.href='/auth?{{HttpFormConstant::FORM_KEY_AUTH_ID}}={{$auth->getAuthId()}}'"
                                    class="normaltd authbianhao" valign="top">
                                    <img class="tdimgauth"
                                         src="{{$authUtils->getAuthImageUrls()[$auth->getAuthImage()]}}" alt="">
                                    <a class="authida"
                                       href="/auth?{{HttpFormConstant::FORM_KEY_AUTH_ID}}={{$auth->getAuthId()}}">{{$auth->getAuthId()}}</a>
                                </td>
                                @if($auth->getAuthDefault())
                                    <td class="normaltd authmincheng" valign="top">
                                        <span id="morenpicspan{{$auth->getAuthId()}}"><img class='morenauthleftpic' src='/resources/img/moren.png'/></span>
                                        <span ondblclick="ShowElement(this,{{$auth->getAuthId()}})" id="authnamecode{{$auth->getAuthId()}}">{{$auth->getAuthName()}}</span>
                                    </td>
                                @else
                                    <td class="normaltd authmincheng" valign="top">
                                        <span id="morenpicspan{{$auth->getAuthId()}}"></span><span ondblclick="ShowElement(this,{{$auth->getAuthId()}})" id="authnamecode{{$auth->getAuthId()}}">{{$auth->getAuthName()}}</span>
                                    </td>
                                @endif
                                @if($_USER->getUserRight() == $_USER::USER_NORMAL)
                                    <td onclick="location.href='/auth?{{HttpFormConstant::FORM_KEY_AUTH_ID}}={{$auth->getAuthId()}}'"
                                        class="normaltd authxuliehao" valign="top">
                                        <span>
                                            {{$auth->getAuthSerial()}}
                                        </span>
                                    </td>
                                @else
                                    <td onclick="location.href='/auth?{{HttpFormConstant::FORM_KEY_AUTH_ID}}={{$auth->getAuthId()}}'"
                                        class="normaltd authxuliehao" valign="top">
                                        <span>
                                            共享账号无权查看
                                        </span>
                                    </td>
                                @endif
                                @if($_USER->getUserRight() == $_USER::USER_NORMAL)
                                    <td onclick="location.href='/auth?{{HttpFormConstant::FORM_KEY_AUTH_ID}}={{$auth->getAuthId()}}'"
                                        class="normaltd authhuanyuanma" valign="top">
                                        <span>
                                            {{$auth->getAuthRestoreCode()}}
                                        </span>
                                    </td>
                                @else
                                    <td onclick="location.href='/auth?{{HttpFormConstant::FORM_KEY_AUTH_ID}}={{$auth->getAuthId()}}'"
                                        class="normaltd authhuanyuanma" valign="top">
                                        <span>
                                            无权查看
                                        </span>
                                    </td>
                                @endif
                                <td onclick="location.href='/auth?{{HttpFormConstant::FORM_KEY_AUTH_ID}}={{$auth->getAuthId()}}'"
                                    class="normaltd authshangcitongbushijian" valign="top">
                                    <span id="authshangcitongbushijian{{$auth->getAuthId()}}">{{$authUtils->getLastSyncTimeString($auth)}}</span>
                                </td>
                                <td valign="top" class="align-center">
                                    <button id="authsyncbutton{{$auth->getAuthId()}}" class="ui-button button1"
                                            onclick="authsync({{$auth->getAuthId()}})">
                                    <span class="button-left">
                                        <span id="jiaochenshijian{{$auth->getAuthId()}}"
                                              class="button-right">校正时间</span>
                                    </span>
                                    </button>
                                </td>
                                @if($auth->getAuthDefault())
                                    <td valign="top" class="align-center">
                                        <button id="morenauthbutton{{$auth->getAuthId()}}"
                                                class="ui-button button1" disabled="disabled"
                                                onclick="authmoren({{$auth->getAuthId()}})">
                                            <span class="button-left">
                                                <span id="morenanquanlin{{$auth->getAuthId()}}"
                                                      class="button-right">已为默认</span>
                                            </span>
                                        </button>
                                    </td>
                                @else
                                    <td valign="top" class="align-center">
                                        <button id="morenauthbutton{{$auth->getAuthId()}}"
                                                class="ui-button button1"
                                                onclick="authmoren({{$auth->getAuthId()}})">
                                            <span class="button-left">
                                                <span id="morenanquanlin{{$auth->getAuthId()}}"
                                                      class="button-right">设置默认
                                                </span>
                                            </span>
                                        </button>
                                    </td>
                                @endif
                                <td valign="top" class="align-center">
                                    <button id="authdelbutton{{$auth->getAuthId()}}" class="ui-button button1"
                                            onclick="if(confirm('该操作将删除这枚安全令，确定吗？'))
                                                    authdelete({{$auth->getAuthId()}});else return false;">
                                        <span class="button-left"><span id="shanchuauth{{$auth->getAuthId()}}"
                                                                        class="button-right">确认删除</span>
                                        </span>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>