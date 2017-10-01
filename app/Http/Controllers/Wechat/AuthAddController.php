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

class AuthAddController extends Controller
{

    private $standardRegion;
    private $authUtils;
    private $postAuthName;
    private $postRegion;
    private $postSelectPic;

    function __construct()
    {
        $this->middleware("wechat.check");
        $this->standardRegion[0] = "CN";
        $this->standardRegion[1] = "US";
        $this->standardRegion[2] = "EU";
    }

    private function getCreateErrorView($mode)
    {
        $json = ['code' => 500, "message" => $mode . "安全令失败，请重试"];
        return $json;
    }


    public
    function checkCanAdd(Request $request, User $user)
    {
        $this->authUtils = new AuthUtils();
        $this->authUtils->getAllAuth($user);
        if ($user->getUserDonated() == 1 && $this->authUtils->getAuthCount() >= config('app.auth_max_count_donated_user')) {
            $json = ['code' => 403, "message" => "您已拥有" . $this->authUtils->getAuthCount() . "枚安全令，已到捐赠者账号的最大添加数量，无法添加新的安全令"];
            return $json;
        }
        if ($user->getUserDonated() == 0 && $this->authUtils->getAuthCount() >= config('app.auth_max_count_standard_user')) {
            $json = ['code' => 403, "message" => "您已拥有" . $this->authUtils->getAuthCount() . "枚安全令，已到普通账号的最大添加数量，无法添加新的安全令"];
            return $json;
        }
        $this->postAuthName = $request->json("authName");
        $this->postRegion = $request->json('region');
        $this->postSelectPic = $request->json('selectPic');
        if (!Functions::isAuthNameValid($this->postAuthName)) {
            $json = ['code' => 403, "message" => "请输入正确的安全令名称"];
            return $json;
        }
        if (empty($this->standardRegion[$this->postRegion])) {
            $json = ['code' => 403, "message" => "请选择正确的安全令区域"];
            return $json;
        }
        if (empty($this->authUtils->getAuthImageUrls()[$this->postSelectPic])) {
            $json = ['code' => 403, "message" => "请选择正确的安全令图片"];
            return $json;
        }
        return true;
    }


    public function byServer(Request $request)
    {
        $user = Auth::user();
        $checkResult = $this->checkCanAdd($request, $user);
        if ($checkResult !== true) {
            return response()->json($checkResult);
        }
        try {
            $auth = Authenticator::generate($this->standardRegion[$this->postRegion]);
            $authSerial = $auth->serial();
            $authSecret = $auth->secret();
            $authRestoreCode = $auth->restore_code();
            Functions::registerOnBlizzardOneButtonLogin($auth);
            $setDefault = false;
            if ($this->authUtils->getAuthCount() == 0 || $this->authUtils->getDefaultAuth() == null) {
                $setDefault = true;
            } elseif ($request->json('defaultAuthSet') == "on") {
                $setDefault = true;
            }
            if (empty($authSerial) || empty($authSecret) || empty($authRestoreCode)) {
                return self::getCreateErrorView("添加");
            }
            $newAuthId = DBHelper::addNewAuth($auth, $user, $setDefault, $this->authUtils, $this->postAuthName, $this->postSelectPic);
            if ($newAuthId === false) {
                return self::getCreateErrorView("添加");
            }
            $authUtils = new AuthUtils();
            $authUtils->getAllAuth($user);
            $userMaxAuthCount = $user->getUserDonated() == 1 ? config('app.auth_max_count_donated_user') : config('app.auth_max_count_standard_user');
            $json = ['code' => 200, "message" => "生成安全令成功",
                'data' => [
                    'authId' => $newAuthId,
                    'isDefault' => $setDefault,
                    'canAddMoreAuth' => $authUtils->getAuthCount() < $userMaxAuthCount,
                    'authCount' => $authUtils->getAuthCount()
                ]
            ];
            return response()->json($json);
        } catch (\Exception $e) {
            return self::getCreateErrorView("添加");
        }
    }

    public function authAddByRestoreCode(Request $request)
    {
        $user = Auth::user();
        $checkCanAdd = self::checkCanAdd($request, $user);
        if ($checkCanAdd !== true) {
            return $checkCanAdd;
        }
        $postAuthCodeA1 = $request->json('authcodeA1');
        $postAuthCodeB2 = $request->json('authcodeB2');
        $postAuthCodeC3 = $request->json('authcodeC3');
        $postAuthRestoreCode = strtoupper($request->json('authRestoreCode'));
        if (!Functions::isAuthCodeValid($postAuthCodeA1) || !Functions::isAuthCodeValid($postAuthCodeB2) || !Functions::isAuthCodeValid($postAuthCodeC3) ||
            !Functions::isAuthRestoreCodeValid($postAuthRestoreCode)
        ) {
            $json = ['code' => 403, "message" => "输入的序列号和还原码格式有误，请检查后再试"];
            return response()->json($json);
        }
        $authSerial = $this->standardRegion[$this->postRegion] . "-$postAuthCodeA1-$postAuthCodeB2-$postAuthCodeC3";
        $serverTime = $this->authUtils->getAuthSyncInfo()
            ->getSyncList()[$this->standardRegion[$this->postRegion]][AuthSyncInfo::SYNC_SERVER_TIME];
        $authBean = DBHelper::checkHasAuthInDbByRestoreCode($authSerial, $postAuthRestoreCode);
        try {
            if ($authBean !== false && $authBean != null && !empty($authBean->getAuthId())) {
                $auth = Authenticator::factory($authSerial, $authBean->getAuthSecret(), $serverTime);
            } else {
                $auth = Authenticator::restore($authSerial, $postAuthRestoreCode);
            }
            $authSerial = $auth->serial();
            $authSecret = $auth->secret();
            $authRestoreCode = $auth->restore_code();
            Functions::registerOnBlizzardOneButtonLogin($auth);
            $setDefault = false;
            if ($this->authUtils->getAuthCount() == 0 || $this->authUtils->getDefaultAuth() == null) {
                $setDefault = true;
            } elseif ($request->json('defaultAuthSet') == "on") {
                $setDefault = true;
            }
            if (empty($authSerial) || empty($authSecret) || empty($authRestoreCode)) {
                return self::getCreateErrorView("还原");
            }
            $newAuthId = DBHelper::addNewAuth($auth, $user, $setDefault, $this->authUtils, $this->postAuthName, $this->postSelectPic);
            if ($newAuthId === false) {
                return self::getCreateErrorView("还原");
            }
            $authUtils = new AuthUtils();
            $authUtils->getAllAuth($user);
            $userMaxAuthCount = $user->getUserDonated() == 1 ? config('app.auth_max_count_donated_user') : config('app.auth_max_count_standard_user');
            $json = ['code' => 200, "message" => "生成安全令成功",
                'data' => [
                    'authId' => $newAuthId,
                    'isDefault' => $setDefault,
                    'canAddMoreAuth' => $authUtils->getAuthCount() < $userMaxAuthCount,
                    'authCount' => $authUtils->getAuthCount()]
            ];
            return response()->json($json);
        } catch (\Exception $e) {
            $json = ['code' => 403, "message" => "输入的序列号与还原码不对应，请检查后再试"];
            return response()->json($json);
        }
    }
}