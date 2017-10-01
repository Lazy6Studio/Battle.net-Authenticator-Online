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
use HttpFormConstant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RedisHelper;
use WechatTokenBean;

class OnButtonAuthController extends Controller
{

    function __construct()
    {
        $this->middleware("wechat.check");
    }

    /**
     * 通过用户查找请求中的authId是不是本人的
     * @param AuthUtils $authUtils
     * @param $authId
     * @return AuthBean|bool
     */
    private function isAuthValidAndBelongToUser(AuthUtils $authUtils, $authId)
    {
        $isAuthBelongToThisUser = false;
        $authBean = new AuthBean();
        foreach ($authUtils->getAuthList() as $auth) {
            if ($auth->getAuthId() == $authId) {
                $isAuthBelongToThisUser = true;
                $authBean = $auth;
                break;
            }
        }
        if (!$isAuthBelongToThisUser || $authBean->getAuthId() != $authId) {
            return false;
        }
        return $authBean;
    }


    public function getRequestInfo(Request $request)
    {
        $user = Auth::user();
        $authUtils = new AuthUtils();
        $authUtils->getAllAuth($user);
        if ($authUtils->getAuthCount() < 1) {
            $jsonError = ['code' => 404, "message" => "你没有可用的安全令，请先添加"];
            return response()->json($jsonError);
        }
        $authId = $request->json('authId', "");
        if (empty($authId)) {
            $authBean = $authUtils->getDefaultAuth();
            if ($authBean == null) {
                $authBean = $authUtils->getAuthList()[0];
            }
        } else {
            $authBean = self::isAuthValidAndBelongToUser($authUtils, $authId);
            if ($authBean == false) {
                $jsonError = ['code' => 403, "message" => "这枚安全令不属于你，你无权查看"];
                return response()->json($jsonError);
            }
        }
        switch (strtoupper($authBean->getAuthRegion())) {
            case "CN":
                $oneKeyLoginRequestUrl = rand(0, 1) == 1 ? "https://cn.battle.net/login/authenticator/pba" : "https://www.battlenet.com.cn/login/authenticator/pba";
                break;
            case "EU":
                $oneKeyLoginRequestUrl = "https://eu.battle.net/login/authenticator/pba";
                break;
            default:
                $oneKeyLoginRequestUrl = "https://us.battle.net/login/authenticator/pba";
                break;
        }
        $serverTime = $authUtils->getAuthSyncInfo()->getSyncList()[strtoupper($authBean->getAuthRegion())][AuthSyncInfo::SYNC_SERVER_TIME];
        $factoryAuth = Authenticator::factory($authBean->getAuthSerial(), $authBean->getAuthSecret(), $serverTime);

        $authCode = $factoryAuth->code();
        $authPlainSerial = $factoryAuth->plain_serial();
        $blizzardApiUrl = $oneKeyLoginRequestUrl . "?serial=$authPlainSerial&code=$authCode";
        $requestJson = Functions::_curlGet($blizzardApiUrl);
        if (empty($requestJson)) {
            $jsonError = ['code' => 404, "message" => "无一键安全令请求"];
            return response()->json($jsonError);
        }
        $requestJsonArray = json_decode($requestJson, true);
        if ($requestJsonArray['callback_url'] != null && $requestJsonArray['session']['request_id'] != null &&
            (time() - $requestJsonArray['session']['time_created_millis'] / 1000) < 150
        ) {
            $data = ['callbackUrl' => $requestJsonArray['callback_url'],
                'requestId' => $requestJsonArray['session']['request_id'],
                'authId' => $authBean->getAuthId(),
                'message' => $requestJsonArray['message'],
                'time' => ceil($requestJsonArray['session']['time_created_millis'] / 1000)
            ];
            $json = ['code' => 200, "message" => "已获取一键安全令请求",
                'data' => $data];
            return response()->json($json);
        }
        $jsonError = ['code' => 404, "message" => "无一键安全令请求"];
        return response()->json($jsonError);
    }

    public function commit(Request $request)
    {
        $authId = $request->json('authId', "");
        $callbackUrl = $request->json('callbackUrl');
        $requestId = $request->json("requestId");
        $time = $request->json('time');
        if (empty($authId) || empty($callbackUrl) || empty($requestId) || empty($time)) {
            $jsonError = ['code' => 403, "message" => "提交数据有误"];
            return response()->json($jsonError);
        }
        $user = Auth::user();
        $authUtils = new AuthUtils();
        $authUtils->getAllAuth($user);
        if ($authUtils->getAuthCount() < 1) {
            $jsonError = ['code' => 404, "message" => "你没有可用的安全令，请先添加"];
            return response()->json($jsonError);
        }
        $authBean = self::isAuthValidAndBelongToUser($authUtils, $authId);
        if ($authBean == false) {
            $jsonError = ['code' => 403, "message" => "这枚安全令不属于你，你无权查看"];
            return response()->json($jsonError);
        }
        if (time() - $time > 180) {
            return response()->json(['code' => 403, 'message' => '已超时']);
        }
        $acceptMode = $request->json('accept');
        if ($acceptMode != "true") {
            $acceptMode = "false";
        }
        $serverTime = $authUtils->getAuthSyncInfo()->getSyncList()[strtoupper($authBean->getAuthRegion())][AuthSyncInfo::SYNC_SERVER_TIME];
        $factoryAuth = Authenticator::factory($authBean->getAuthSerial(), $authBean->getAuthSecret(), $serverTime);

        $authCode = $factoryAuth->code();
        $authPlainSerial = $factoryAuth->plain_serial();
        $data = "serial=$authPlainSerial&code=$authCode&requestId={$requestId}&accept={$acceptMode}";
        Functions::_curlPost($callbackUrl, $data);
        $json = ['code'=>200,"message"=> $acceptMode == "true" ? "已允许" : "已拒绝"];
        return response()->json($json);
    }
}