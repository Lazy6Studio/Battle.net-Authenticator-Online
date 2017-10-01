<?php

namespace App\Http\Controllers\Wechat;


use App\Http\Controllers\Controller;
use App\User;
use DBHelper;
use Functions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RedisHelper;
use WechatTokenBean;

class UnBindController extends Controller
{

    function __construct()
    {
        $this->middleware("wechat.check");
    }

    public function unBind(Request $request)
    {
        $user = Auth::user();
        $result = DBHelper::updateUserUnBindWechatOpenId($user);

        if ($result == false) {
            $json = ['code' => 500, 'message' => '服务器数据库错误'];
            return response()->json($json);
        }
        $json = ['code' => 200, 'message' => '小程序账号解绑成功'];
        return response()->json($json);
    }

}