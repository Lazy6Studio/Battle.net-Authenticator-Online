<?php

namespace App\Http\Controllers\Wechat;


use App\Http\Controllers\Controller;
use App\User;
use AuthUtils;
use DBHelper;
use Functions;
use Illuminate\Http\Request;
use RedisHelper;
use WechatTokenBean;

class LoginController extends Controller
{

    function __construct()
    {
        $this->middleware("wechat.check:true,false");
    }

    public function post(Request $request)
    {
        $code = $request->json('code');
        if (empty($code)) {
            $jsonNotLogin = ['code' => 403, "message" => "请带code提交"];
            return response()->json($jsonNotLogin);
        }
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=%s";
        $url = sprintf($url, config('app.wechat_app_id'), config('app.wechat_app_secret'), $code, "authorization_code");
        $result = json_decode(Functions::_curlGet($url), true);
        if (empty($result['openid']) || empty($result['session_key'])) {
            $jsonNotLogin = ['code' => 403, "message" => "code已失效，请重试"];
            return response()->json($jsonNotLogin);
        }
        $token = md5(Functions::getRandomString() . time());
        $tokenValue = WechatTokenBean::getWechatTokenJson($result['session_key'], $result['openid']);
        RedisHelper::saveWechatToken($token, $tokenValue);
        $user = new User();
        $user->initUserByWechatOpenId($result['openid']);
        if (empty($user->getUserId()) || !Functions::isInt($user->getUserId())) {
            $jsonNotLogin = ['code' => 428, "message" => "需要先绑定账户", "data" => [
                "token_wechat_session_v1" => $token
            ]];
            return response()->json($jsonNotLogin);
        }
        self::updateUserLoginTimeAndIp($request, $user);
        $authUtils = new AuthUtils();
        $authUtils->getAllAuth($user);
        $userMaxAuthCount = $user->getUserDonated() == 1 ? config('app.auth_max_count_donated_user') : config('app.auth_max_count_standard_user');
        $jsonLogin = ['code' => 200, 'message' => '登录成功', "data" => [
            "token_wechat_session_v1" => $token,
            'hasAuth' => $authUtils->getAuthCount() > 0,
            'canAddMoreAuth' => $authUtils->getAuthCount() < $userMaxAuthCount,
            'authCount' => $authUtils->getAuthCount(),
            'userName' =>$user->getUserName()
        ]];
        return response()->json($jsonLogin);
    }

    public static function updateUserLoginTimeAndIp(Request $request, User $user)
    {
        $user->setUserLastLoginTime($user->getUserThisLoginTime());
        $user->setUserThisLoginTime(date('Y-m-d H:i:s'));
        $user->setUserLastTimeLoginIP($user->getUserThisTimeLoginIP());
        $user->setUserThisTimeLoginIP($request->ip());
        $user->setLastUsedSessionTime(time());
        DBHelper::updateUserLoginTimeAndIp($user);
    }

}