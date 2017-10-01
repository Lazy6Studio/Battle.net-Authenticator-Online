<?php

namespace App\Http\Controllers\Wechat;


use App\Http\Controllers\Controller;
use App\User;
use AuthBean;
use Authenticator;
use AuthSyncInfo;
use AuthUtils;
use DBHelper;
use Functions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RedisHelper;
use WechatTokenBean;

class UserInfoController extends Controller
{

    function __construct()
    {
        $this->middleware("wechat.check");
    }

    public function userInfo(Request $request)
    {
        $user = Auth::user();
        $data = [
            'userId' => $user->getUserId(),
            'userName' => $user->getUserName(),
            'userEmail' => $user->getUserEmail(),
            'userEmailChecked' => $user->getUserEmailChecked() == 1,//邮箱是否确认成功(邮件点击链接)
            'userRight' => $user->getUserRight(),//0：普通，1：共享，999：封禁
            'userRegisterTime' => $user->getUserRegisterTime(),
            'userLastLoginTime' => $user->getUserLastLoginTime(),
            'userThisLoginTime' => $user->getUserThisLoginTime(),
            'userLastLoginIp' => $user->getUserLastTimeLoginIP(),
            'userThisLoginIp' => $user->getUserThisTimeLoginIP(),
            'userDonated' => $user->getUserDonated() == 1
        ];
        $json = ['code' => 200, 'message' => "获取用户信息成功",
            'data' => $data];
        return response()->json($json);
    }

}