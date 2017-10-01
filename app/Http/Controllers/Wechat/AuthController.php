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

class AuthController extends Controller
{

    function __construct()
    {
        $this->middleware("wechat.check");
    }

    public function getAuthInfo(Request $request)
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
        $userMaxAuthCount = $user->getUserDonated() == 1 ? config('app.auth_max_count_donated_user') : config('app.auth_max_count_standard_user');
        $authInfo = ['authId' => $authBean->getAuthId(),
            'authSerial' => $authBean->getAuthSerial(),
            'authSecret' => $authBean->getAuthSecret(),
            'authRestoreCode' => $authBean->getAuthRestoreCode(),
            'authRegion' => $authBean->getAuthRegion(),
            'authName' => $authBean->getAuthName(),
            'authImage' => "http://" . config('app.simpleUrl') . $authUtils->getAuthImageUrls()[$authBean->getAuthImage()],
            'isDefault' => $authBean->getAuthDefault(),
            'authCount' => $authUtils->getAuthCount(),
            'canAddMoreAuth' => $authUtils->getAuthCount() < $userMaxAuthCount
        ];
        $json = ['code' => 200, 'message' => "获取安全令信息成功",
            'data' => $authInfo];
        return response()->json($json);
    }

    public function getAuthDynamicCode(Request $request)
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
        if (strtoupper($authBean->getAuthRegion()) != "CN" && strtoupper($authBean->getAuthRegion()) != "EU") {
            $authBean->setAuthRegion("US");
        }
        $lastSyncTime = $authUtils->getAuthSyncInfo()->getSyncList()[strtoupper($authBean->getAuthRegion())][AuthSyncInfo::LAST_SYNC_TIME];
        $serverTime = $authUtils->getAuthSyncInfo()->getSyncList()[strtoupper($authBean->getAuthRegion())][AuthSyncInfo::SYNC_SERVER_TIME];
        $nowTimeStamp = time();
        try {
            if ($nowTimeStamp - strtotime($lastSyncTime) >= 86400) {
                $factoryAuth = Authenticator::factory($authBean->getAuthSerial(), $authBean->getAuthSecret());
                DBHelper::updateAuthSyncServerTime($factoryAuth->getsync(), $authBean->getAuthRegion(), date('Y-m-d H:i:s', $nowTimeStamp));
            } else {
                $factoryAuth = Authenticator::factory($authBean->getAuthSerial(), $authBean->getAuthSecret(), $serverTime);
            }
            $data = ['dynamicCode' => $factoryAuth->code(),
                'usedSecond' => $factoryAuth->sleeptime() / 1000];
            $json = ['code' => 200, "message" => "获取成功", 'data' => $data];
            return response()->json($json);
        } catch (\Exception $e) {
            $json = ['code' => 500, "message" => "服务器安全令计算异常"];
            return response()->json($json);
        }

    }

    public function getAuthList(Request $request)
    {
        $user = Auth::user();
        $authUtils = new AuthUtils();
        $authUtils->getAllAuth($user);
        $data = array();
        $userMaxAuthCount = $user->getUserDonated() == 1 ? config('app.auth_max_count_donated_user') : config('app.auth_max_count_standard_user');
        $data['authCount'] = $authUtils->getAuthCount();
        $data['hasAuth'] = $authUtils->getAuthCount() > 0;
        $data['canAddMoreAuth'] = $authUtils->getAuthCount() < $userMaxAuthCount;
        $data['userName'] = $user->getUserName();
        if ($authUtils->getAuthCount() > 0) {
            $authBean = $authUtils->getDefaultAuth() == null ? $authUtils->getAuthList()[0] : $authUtils->getDefaultAuth();
            $data['defaultAuthInfo'] = ['authId' => $authBean->getAuthId(),
                'authSerial' => $authBean->getAuthSerial(),
                'authSecret' => $authBean->getAuthSecret(),
                'authRestoreCode' => $authBean->getAuthRestoreCode(),
                'authRegion' => $authBean->getAuthRegion(),
                'authName' => $authBean->getAuthName(),
                'authImage' => "https://" . config('app.simpleUrl') . $authUtils->getAuthImageUrls()[$authBean->getAuthImage()],
                'isDefault' => $authBean->getAuthDefault()];
        }
        $authData = array();
        foreach ($authUtils->getAuthList() as $authBean) {
            $authData[] = ['authId' => $authBean->getAuthId(),
                'authSerial' => $authBean->getAuthSerial(),
                'authSecret' => $authBean->getAuthSecret(),
                'authRestoreCode' => $authBean->getAuthRestoreCode(),
                'authRegion' => $authBean->getAuthRegion(),
                'authName' => $authBean->getAuthName(),
                'authImage' => "https://" . config('app.simpleUrl') . $authUtils->getAuthImageUrls()[$authBean->getAuthImage()],
                'isDefault' => $authBean->getAuthDefault()];
        }
        $data['authList'] = $authData;
        $json = ['code' => 200, "message" => "获取安全令列表成功",
            "data" => $data];
        return response()->json($json);
    }


    public function getAuthCount(Request $request)
    {
        $user = Auth::user();
        $authUtils = new AuthUtils();
        $authUtils->getAllAuth($user);
        $userMaxAuthCount = $user->getUserDonated() == 1 ? config('app.auth_max_count_donated_user') : config('app.auth_max_count_standard_user');
        $data = ['authCount' => $authUtils->getAuthCount(),
            'canAddMoreAuth' => $authUtils->getAuthCount() < $userMaxAuthCount
        ];
        $json = ['code' => 200, 'message' => "已获取安全令数量",
            'data' => $data];
        return response()->json($json);
    }

    public function deleteAuth(Request $request)
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
            $jsonError = ['code' => 403, "message" => "请检查提交数据，缺少authId"];
            return response()->json($jsonError);
        }
        $authBean = self::isAuthValidAndBelongToUser($authUtils, $authId);
        if ($authBean == false) {
            $jsonError = ['code' => 403, "message" => "这枚安全令不属于你，你无权删除"];
            return response()->json($jsonError);
        }
        DBHelper::backUpDeletedAuth($authBean);
        if (!($authBean->getAuthDefault()) || $authUtils->getAuthCount() == 1) {
            DBHelper::deleteAuth($authBean);
            $authUtils = new AuthUtils();
            $authUtils->getAllAuth($user);
            $userMaxAuthCount = $user->getUserDonated() == 1 ? config('app.auth_max_count_donated_user') : config('app.auth_max_count_standard_user');
            $data = ['authCount' => $authUtils->getAuthCount(),
                'canAddMoreAuth' => $authUtils->getAuthCount() < $userMaxAuthCount
            ];
            $jsonError = ['code' => 200, "message" => "安全令删除成功",
                'data'=>$data];
            return response()->json($jsonError);
        }
        $setNextDefaultAuthId = -1;
        foreach ($authUtils->getAuthList() as $authTemp) {
            if ($authTemp->getAuthId() != $authBean->getAuthId() && !($authTemp->getAuthDefault())) {
                $setNextDefaultAuthId = $authTemp->getAuthId();
                break;
            }
        }
        DBHelper::deleteAuth($authBean);
        if ($setNextDefaultAuthId != -1) {
            DBHelper::updateAuthSetDefault($user->getUserId(), $setNextDefaultAuthId);
        }
        $authUtils = new AuthUtils();
        $authUtils->getAllAuth($user);
        $userMaxAuthCount = $user->getUserDonated() == 1 ? config('app.auth_max_count_donated_user') : config('app.auth_max_count_standard_user');
        $data = ['authCount' => $authUtils->getAuthCount(),
            'canAddMoreAuth' => $authUtils->getAuthCount() < $userMaxAuthCount
        ];
        $result = ['code' => 200, "message" => "安全令删除成功",
            'data' =>$data];
        return response()->json($result);
    }

    public function authChangeName(Request $request)
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
            $jsonError = ['code' => 403, "message" => "请检查提交数据，缺少authId"];
            return response()->json($jsonError);
        }
        $newName = $request->json('authName');
        if (!Functions::isAuthNameValid($newName)) {
            $jsonError = ['code' => 403, "message" => "请检查提交数据，authName长度不正确"];
            return response()->json($jsonError);
        }
        $authBean = self::isAuthValidAndBelongToUser($authUtils, $authId);
        if ($authBean == false) {
            $jsonError = ['code' => 403, "message" => "这枚安全令不属于你，你无权操作"];
            return response()->json($jsonError);
        }
        $result = DBHelper::updateAuthSetNewName($authBean, $newName);
        if ($result) {
            $json = ['code' => 200, "message" => "修改安全令名称成功"];
            return response()->json($json);
        }
        $json = ['code' => 500, "message" => "写入数据看失败"];
        return response()->json($json);
    }

    public function authSetDefault(Request $request)
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
            $jsonError = ['code' => 403, "message" => "请检查提交数据，缺少authId"];
            return response()->json($jsonError);
        }
        $authBean = self::isAuthValidAndBelongToUser($authUtils, $authId);
        if ($authBean == false) {
            $jsonError = ['code' => 403, "message" => "这枚安全令不属于你，你无权操作"];
            return response()->json($jsonError);
        }

        $result = DBHelper::updateAuthSetDefault($user->getUserId(), $authBean->getAuthId());
        if ($result) {
            $json = ['code' => 200, "message" => "设置安全令为默认成功"];
            return response()->json($json);
        }
        $json = ['code' => 500, "message" => "写入数据看失败"];
        return response()->json($json);
    }


    public function authSyncTime(Request $request)
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
            $jsonError = ['code' => 403, "message" => "请检查提交数据，缺少authId"];
            return response()->json($jsonError);
        }
        $authBean = self::isAuthValidAndBelongToUser($authUtils, $authId);
        if ($authBean == false) {
            $jsonError = ['code' => 403, "message" => "这枚安全令不属于你，你无权操作"];
            return response()->json($jsonError);
        }
        try {
            $createdAuth = Authenticator::factory($authBean->getAuthSerial(), $authBean->getAuthSecret());
            $time = time();
            $date = date('Y-m-d H:i:s', $time);
            if ($createdAuth->getsync()) {
                DBHelper::updateAuthSyncServerTime($createdAuth->getsync(), $authBean->getAuthRegion(), $date);
                $json = ['code' => 200, "message" => "同步安全令时间成功"];
                return response()->json($json);
            }
            $json = ['code' => 500, "message" => "获取安全令同步时间失败"];
            return response()->json($json);
        } catch (\Exception $e) {
            $json = ['code' => 500, "message" => "计算安全令数据失败"];
            return response()->json($json);
        }
    }

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

}