<?php

namespace App\Http\Middleware;

use AuthUtils;
use Closure;
use CookieBean;
use Functions;
use Illuminate\Http\Request as Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use KeyConstant;
use App\User;
use DBHelper;
use CookieHelper;
use RedisHelper;
use WechatTokenBean;

class WechatUserCheckMiddleware
{
    const TAG = "WechatUserCheckMiddleware";

    /**
     * @param Request $request
     * @param Closure $next
     * @param bool $isInLoginApi
     * @param bool $isInRegisterOrBandApi
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $isInLoginApi = false, $isInRegisterOrBandApi = false)
    {
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $json = ['code' => 500,
                'message' => "服务器数据库错误"];
            return response()->json($json);
        }
        return $this->doCheck($request, $next, $isInLoginApi, $isInRegisterOrBandApi);
    }

    public function doCheck(Request $request, Closure $next, $isInLoginApi, $isInRegisterOrBandApi)
    {
        $token = $request->json(KeyConstant::WECHAT_KEY_SESSION_TOKEN);
        if (empty($token)) {
            if ($isInLoginApi) {//登录，服务器向微信请求串，这时候需要走下一步
                return $next($request);
            } else {
                $jsonNotLogin = ['code' => 401, "message" => "Token失效，请重新登录"];
                return response()->json($jsonNotLogin);
            }
        }
        $tokenValue = RedisHelper::getWechatTokenJson($token);
        $wechatTokenBean = new WechatTokenBean($token);
        $wechatTokenBean->initWechatToken($tokenValue);
        if (!$wechatTokenBean->getIsTokenValid()) {//有token却不对，那就不对了，要求客户端重新登录
            if ($isInLoginApi) {//登录，服务器向微信请求串，这时候需要走下一步
                return $next($request);
            }
            $jsonNotLogin = ['code' => 401, "message" => "Token失效，请重新登录"];
            return response()->json($jsonNotLogin);
        }
        $openId = $wechatTokenBean->getWechatTokenOpenId();//有token，判断是否绑定，
        $user = new User();
        Auth::setUser($user);
        $user->setWechatTokenBean($wechatTokenBean);
        $user->initUserByWechatOpenId($openId);
        if ($isInRegisterOrBandApi) {
            return $next($request);
        }
        if (empty($user->getUserId()) || !Functions::isInt($user->getUserId())) {
            $jsonNotLogin = ['code' => 428, "message" => "需要先绑定账户", "data" => [
                "token_wechat_session_v1" => $token
            ]];
            return response()->json($jsonNotLogin);
        }
        if ($isInLoginApi) {
            $authUtils = new AuthUtils();
            $authUtils->getAllAuth($user);
            $jsonLogin = ['code' => 200, 'message' => '登录成功', "data" => [
                "token_wechat_session_v1" => $token,
                'hasAuth' => $authUtils->getAuthCount() > 0
            ]];
            return response()->json($jsonLogin);
        }
        if ($user->getUserRight() == User::USER_BANED) {
            $jsonNotLogin = ['code' => 403, "message" => "账户已被封禁"];
            return response()->json($jsonNotLogin);
        }
        $user->setIsLogin(true);
        return $next($request);
    }

}
