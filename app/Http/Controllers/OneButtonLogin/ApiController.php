<?php
/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/15 0015
 * Time: 下午 16:22
 */

namespace App\Http\Controllers\OneButtonLogin;


use App\Http\Controllers\Controller;
use App\User;
use AuthBean;
use Authenticator;
use AuthSyncInfo;
use AuthUtils;
use Functions;
use HttpFormConstant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    private $authUtils;

    function __construct()
    {
        $this->middleware('base.check');
    }

    /**
     * 通过用户查找请求中的authId是不是本人的
     * @param Request $request
     * @param User $user
     * @return AuthBean|bool
     */
    function isAuthValidAndBelongToUser(Request $request, User $user)
    {
        if (!$user->getIsLogin()) {
            return false;
        }
        $authId = $request->input(HttpFormConstant::FORM_KEY_AUTH_ID);
        if (empty($authId) || !Functions::isInt($authId)) {
            return false;
        }
        $this->authUtils = new AuthUtils();
        $this->authUtils->getAllAuth($user);
        $isAuthBelongToThisUser = false;
        $authBean = new AuthBean();
        foreach ($this->authUtils->getAuthList() as $auth) {
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


    /**
     * 通过给定用户和id，查找两个密钥是否匹配
     * @param Request $request
     * @param User $user
     * @param $authId
     * @return AuthBean|bool
     */
    function isAuthValidAndBelongToUserByGivenAuthId(Request $request, User $user, $authId)
    {
        if (empty($authId) || !Functions::isInt($authId)) {
            return false;
        }
        $this->authUtils = new AuthUtils();
        $this->authUtils->getAllAuth($user);
        $isAuthBelongToThisUser = false;
        $authBean = new AuthBean();
        foreach ($this->authUtils->getAuthList() as $auth) {
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
        set_time_limit(10);
        $user = Auth::user();
        $authBean = self::isAuthValidAndBelongToUser($request, $user);
        if ($authBean === false) {
            return response("");
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
        $serverTime = $this->authUtils->getAuthSyncInfo()->getSyncList()[strtoupper($authBean->getAuthRegion())][AuthSyncInfo::SYNC_SERVER_TIME];
        $factoryAuth = Authenticator::factory($authBean->getAuthSerial(), $authBean->getAuthSecret(), $serverTime);

        $authCode = $factoryAuth->code();
        $authPlainSerial = $factoryAuth->plain_serial();
        $blizzardApiUrl = $oneKeyLoginRequestUrl . "?serial=$authPlainSerial&code=$authCode";
        $requestJson = Functions::_curlGet($blizzardApiUrl);
        if (empty($requestJson)) {
            return response("");
        }
        $requestJsonArray = json_decode($requestJson, true);
        try {
            if (@$requestJsonArray['callback_url'] != null && $requestJsonArray['session']['request_id'] != null &&
                (time() - $requestJsonArray['session']['time_created_millis'] / 1000) < 150
            ) {
                return response()->json(
                    ['code' => 0,
                        'data' => [
                            'callback_url' => $requestJsonArray['callback_url'],
                            'request_id' => $requestJsonArray['session']['request_id'],
                            'auth_id' => $authBean->getAuthId(),
                            'message' => $requestJsonArray['message'],
                            'time' => ceil($requestJsonArray['session']['time_created_millis'] / 1000)
                        ]
                    ]
                );
            }
        } catch (\Exception $e) {
            return response("");
        }
        return response("");
    }

    public function commitPost(Request $request)
    {
        $user = Auth::user();
        if (!$user->getIsLogin()) {
            return response()->json(['code' => 0, 'message' => '未登录']);
        }
        $json = $request->input('json');
        if (empty($json)) {
            return response()->json(['code' => 1, 'message' => '参数错误']);
        }
        $jsonArray = json_decode($json, true);
        $authBean = self::checkOneButtonLoginCommitJsonArrayValid($request, $jsonArray, $user);
        if ($authBean === false) {
            return response()->json(['code' => 1, 'message' => '参数错误']);
        }
        if (time() - $jsonArray['data']['time'] > 180) {
            return response()->json(['code' => 0, 'message' => '已超时']);
        }
        $acceptMode = $request->input('accept');
        if ($acceptMode != "true") {
            $acceptMode = "false";
        }
        $serverTime = $this->authUtils->getAuthSyncInfo()->getSyncList()[strtoupper($authBean->getAuthRegion())][AuthSyncInfo::SYNC_SERVER_TIME];
        $factoryAuth = Authenticator::factory($authBean->getAuthSerial(), $authBean->getAuthSecret(), $serverTime);

        $authCode = $factoryAuth->code();
        $authPlainSerial = $factoryAuth->plain_serial();
        $newUrl = $jsonArray['data']['callback_url'];
        $data = "serial=$authPlainSerial&code=$authCode&requestId={$jsonArray['data']['request_id']}&accept={$acceptMode}";
        Functions::_curlPost($newUrl, $data);
        return response()->json(['code' => 0, 'message' => $acceptMode == "true" ? "已允许" : "已拒绝"]);
    }

    private function checkOneButtonLoginCommitJsonArrayValid(Request $request, $jsonArray, User $user)
    {
        if ($jsonArray == null) {
            return false;
        }
        if ($jsonArray['code'] != 0) {
            return false;
        }
        if ($jsonArray['data'] == null) {
            return false;
        }
        if ($jsonArray['data']['callback_url'] == null || $jsonArray['data']['callback_url'] == "") {
            return false;
        }
        if ($jsonArray['data']['request_id'] == null || $jsonArray['data']['request_id'] == "") {
            return false;
        }
        if ($jsonArray['data']['time'] == null || $jsonArray['data']['time'] == "") {
            return false;
        }
        $authId = $jsonArray['data']['auth_id'];
        return $this->isAuthValidAndBelongToUserByGivenAuthId($request, $user, $authId);
    }
}