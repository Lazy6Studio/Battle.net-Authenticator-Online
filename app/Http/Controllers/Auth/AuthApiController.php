<?php
/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/15 0015
 * Time: 下午 16:22
 */

namespace App\Http\Controllers\Auth;


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

class AuthApiController extends Controller
{
    private $authUtils;

    function __construct()
    {
        $this->middleware('base.check');
    }

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
     * 同步服务器时间
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function doSync(Request $request)
    {
        $user = Auth::user();
        $authBean = self::isAuthValidAndBelongToUser($request, $user);
        if ($authBean == false) {
            return response("false");
        }
        try {
            $createdAuth = Authenticator::factory($authBean->getAuthSerial(), $authBean->getAuthSecret());
        } catch (\Exception $e) {
            return response('false');
        }
        $time = time();
        $date = date('Y-m-d H:i:s', $time);
        if ($createdAuth->getsync()) {
            DBHelper::updateAuthSyncServerTime($createdAuth->getsync(), $authBean->getAuthRegion(), $date);
            return response(date('Y年m月d日H时i分s秒', $time));
        }
        return response('false');
    }

    /**
     * 设置默认
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setDefault(Request $request)
    {
        $user = Auth::user();
        $authBean = self::isAuthValidAndBelongToUser($request, $user);
        if ($authBean == false) {
            return response()->json([
                'oldmorenauthid' => -1,
                'result' => 0
            ]);
        }
        $oldDefaultAuthId = $this->authUtils->getDefaultAuth()->getAuthId();
        $result = DBHelper::updateAuthSetDefault($user->getUserId(), $authBean->getAuthId());
        if ($result == false) {
            return response()->json([
                'oldmorenauthid' => -1,
                'result' => 0
            ]);
        }
        return response()->json([
            'oldmorenauthid' => $oldDefaultAuthId,
            'result' => 1
        ]);
    }

    /**
     * 修改安全令名
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function changeName(Request $request)
    {
        $user = Auth::user();
        $authBean = self::isAuthValidAndBelongToUser($request, $user);
        if ($authBean == false) {
            return response("false");
        }
        $newName = $request->input('authName');
        if (!Functions::isAuthNameValid($newName)) {
            return response("false");
        }
        $result = DBHelper::updateAuthSetNewName($authBean, $newName);
        return response($result == false ? "false" : "true");
    }

    /**
     * 删除安全令
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAuth(Request $request)
    {
        $user = Auth::user();
        $authBean = self::isAuthValidAndBelongToUser($request, $user);
        if ($authBean == false) {
            return response()->json([
                'oldmorendeleted' => 0,
                'newmorenid' => -1,
                'deleteauid' => -1,
                'result' => 0
            ]);
        }
        DBHelper::backUpDeletedAuth($authBean);
        $deleteAuthId = $authBean->getAuthId();
        if (!($authBean->getAuthDefault())) {
            DBHelper::deleteAuth($authBean);
            return response()->json([
                'oldmorendeleted' => 0,
                'newmorenid' => -1,
                'deleteauid' => $deleteAuthId,
                'result' => 1
            ]);
        }
        if ($this->authUtils->getAuthCount() == 1) {
            DBHelper::deleteAuth($authBean);
            return response()->json([
                'oldmorendeleted' => 1,
                'newmorenid' => -1,
                'deleteauid' => $deleteAuthId,
                'result' => 1
            ]);
        }
        $setNextDefaultAuthId = -1;
        foreach ($this->authUtils->getAuthList() as $authTemp) {
            if ($authTemp->getAuthId() != $authBean->getAuthId() && !($authTemp->getAuthDefault())) {
                $setNextDefaultAuthId = $authTemp->getAuthId();
                break;
            }
        }
        DBHelper::deleteAuth($authBean);
        if ($setNextDefaultAuthId == -1) {
            return response()->json([
                'oldmorendeleted' => 1,
                'newmorenid' => -1,
                'deleteauid' => $deleteAuthId,
                'result' => 1
            ]);
        }
        DBHelper::updateAuthSetDefault($user->getUserId(), $setNextDefaultAuthId);
        return response()->json([
            'oldmorendeleted' => 1,
            'newmorenid' => $setNextDefaultAuthId,
            'deleteauid' => $deleteAuthId,
            'result' => 1
        ]);
    }


    public function getCode(Request $request)
    {
        $user = Auth::user();
        $authBean = self::isAuthValidAndBelongToUser($request, $user);
        if ($authBean == false) {
            return response()->json([
                'code' => "@@@@@@",
                'time' => 0,
                'success'=>false
            ]);
        }
        if (strtoupper($authBean->getAuthRegion()) != "CN" && strtoupper($authBean->getAuthRegion()) != "EU") {
            $authBean->setAuthRegion("US");
        }
        $lastSyncTime = $this->authUtils->getAuthSyncInfo()->getSyncList()[strtoupper($authBean->getAuthRegion())][AuthSyncInfo::LAST_SYNC_TIME];
        $serverTime = $this->authUtils->getAuthSyncInfo()->getSyncList()[strtoupper($authBean->getAuthRegion())][AuthSyncInfo::SYNC_SERVER_TIME];
        $nowTimeStamp = time();
        try {
            if ($nowTimeStamp - strtotime($lastSyncTime) >= 86400) {
                $factoryAuth = Authenticator::factory($authBean->getAuthSerial(), $authBean->getAuthSecret());
                DBHelper::updateAuthSyncServerTime($factoryAuth->getsync(), $authBean->getAuthRegion(), date('Y-m-d H:i:s', $nowTimeStamp));
            } else {
                $factoryAuth = Authenticator::factory($authBean->getAuthSerial(), $authBean->getAuthSecret(), $serverTime);
            }
            return response()->json([
                'code' => $factoryAuth->code(),
                'time' => $factoryAuth->sleeptime() / 1000,
                'success'=>true
            ]);
        } catch (\Exception $e) {
            echo $e;
            return response()->json([
                'code' => "@@@@@@",
                'time' => 0,
                'success'=>false
            ]);
        }
    }

}