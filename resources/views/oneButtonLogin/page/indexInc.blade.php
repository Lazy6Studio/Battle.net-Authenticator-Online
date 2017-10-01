<div id="embedded-login">
    <h1>Battle.net</h1>
    <a id="embedded-close" href="javascript:void(0);" onclick="closeiframe()"></a>
    <div id="contentloged" class="login" style="padding-top: 0px;">
        <h2 style="text-align: center;color: #80c024; font-size: 40px;margin-top: 12px;">{{$jsonArray['data']['request_id']}}</h2>
        <br>
        <h4 style="text-align: center;color: #d0d6d6; font-size: 24px;margin-top: 16px;">{{$jsonArray['data']['message']}}</h4>
        <h4 style="text-align: center;color: #d0d6d6; font-size: 24px;margin-top: 24px;">{{$sendTimeString}}</h4>
        <br><br><br>
        <div>
            <table cellSpacing=0 cellPadding=0 width="90%" align=right border=1 rules=rows frame=hsides
                   style="border-collapse:collapse; width: 100%;margin-top: 24px;" bordercolor="#000000">
                <tr align="center">
                    <td>
                        <a href="javascript:void(0);" id="add-time" class="border-5"
                           style="width: 60%;white-space:nowrap;" onclick="commitComfirmInfo(true);">允许</a>
                    </td>
                    <td>
                        <a href="javascript:void(0);" id="cancel-onekey" class="border-5"
                           style="width: 60%;white-space:nowrap;" onclick="commitComfirmInfo(false);">拒绝</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <h4 style="text-align: center;color: #d0d6d6; font-size: 18px;margin-top: 24px;" id="resultInfo"></h4>
    <div style="width:100%; position:fixed; left:0; bottom:0;">
        <h5 style="width:100%;text-align: center;font-size: 12px;color: #b2bac7;">本弹窗因您在战网或其客户端上发起了要求验证器允许登录的请求而弹出<br>关闭本弹窗将忽略这次请求,您也可以关闭并直接前去输入安全令8位验证码
        </h5>
    </div>
</div>
