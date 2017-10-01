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
use CaptchaUtils;
use DBHelper;
use Functions;
use HttpFormConstant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthAddTaskController extends Controller
{
    private $standardRegion;
    private $authUtils;
    private $postAuthName;
    private $postRegion;
    private $postSelectPic;

    function __construct()
    {
        $this->middleware('base.check');
        $this->standardRegion[21] = "CN";
        $this->standardRegion[22] = "US";
        $this->standardRegion[23] = "EU";
    }

    private function getInputErrorView(User $user)
    {
        return view('auth.addResult.index')->with('_USER', $user)->with("topNavValueText", "添加安全令")
            ->with("errorString", "填写的内容有误，请返回添加安全令页面检查后再试")
            ->with('jsString', "")
            ->with('jumpBack', true)
            ->with("jumpToUrl", "addAuth");
    }

    private function getCreateErrorView(User $user)
    {
        return view('auth.addResult.index')->with('_USER', $user)->with("topNavValueText", "添加安全令")
            ->with("errorString", "生成安全令失败，请返回添加安全令页面重试")
            ->with('jsString', "")
            ->with('jumpBack', true)
            ->with("jumpToUrl", "addAuth");
    }

    private function getRestoreErrorView(User $user)
    {
        return view('auth.addResult.index')->with('_USER', $user)->with("topNavValueText", "添加安全令")
            ->with("errorString", "还原安全令失败，请返回添加安全令页面重试")
            ->with('jsString', "")
            ->with('jumpBack', true)
            ->with("jumpToUrl", "addAuth");
    }

    public function checkCanAdd(Request $request, User $user)
    {
        if (!$user->getIsLogin()) {//未登录，跳登录
            $encodeUrl = urlencode(base64_encode("addAuth"));
            $encodeName = urlencode(base64_encode("添加安全令"));
            return redirect("login?from=$encodeUrl&fromName=$encodeName");
        }
        $captchaUtils = new CaptchaUtils();
        if (!$captchaUtils->isCaptchaCodeValid($request->input("letters_code"))) {
            return view('auth.addResult.index')->with('_USER', $user)->with("topNavValueText", "添加安全令")
                ->with("errorString", "验证码输入错误，添加失败")
                ->with('jsString', "")
                ->with('jumpBack', true)
                ->with("jumpToUrl", "addAuth");
        }
        $this->authUtils = new AuthUtils();
        $this->authUtils->getAllAuth($user);
        if ($user->getUserDonated() == 1 && $this->authUtils->getAuthCount() >= config('app.auth_max_count_donated_user')) {
            return view('auth.addResult.index')->with('_USER', $user)->with("topNavValueText", "添加安全令")
                ->with("errorString", "您已经拥有" . $this->authUtils->getAuthCount() . "枚安全令，已到捐赠者账号的最大添加数量，添加失败")
                ->with('jsString', "如要添加新的安全令，请到我的安全令中删除已有的安全令<br>")
                ->with("jumpToUrl", "myAuthList");
        }
        if ($user->getUserDonated() == 0 && $this->authUtils->getAuthCount() >= config('app.auth_max_count_standard_user')) {
            return view('auth.add.error')->with('_USER', $user)->with("topNavValueText", "添加安全令")
                ->with("errorString", "您已经拥有" . $this->authUtils->getAuthCount() . "枚安全令，已到普通账号的最大添加数量，添加失败")
                ->with('jsString', "如要添加新的安全令，请<a href=\"/donate\">捐赠</a>提升权限，或到我的安全令中删除已有的安全令")
                ->with("jumpToUrl", "myAuthList");
        }
        $this->postAuthName = $request->input("authname");
        $this->postRegion = $request->input('region');
        $this->postSelectPic = $request->input('selectpic');
        if (!Functions::isAuthNameValid($this->postAuthName) || empty($this->standardRegion[$this->postRegion])
            || empty($this->authUtils->getAuthImageUrls()[$this->postSelectPic])
        ) {
            return self::getInputErrorView($user);
        }
        return true;
    }


    public function addByServer(Request $request)
    {
        $user = Auth::user();
        $checkCanAdd = self::checkCanAdd($request, $user);
        if ($checkCanAdd !== true) {
            return $checkCanAdd;
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
            } elseif ($request->input('morenauthset') == "on") {
                $setDefault = true;
            }
            if (empty($authSerial) || empty($authSecret) || empty($authRestoreCode)) {
                return self::getCreateErrorView($user);
            }
            $newAuthId = DBHelper::addNewAuth($auth, $user, $setDefault, $this->authUtils, $this->postAuthName, $this->postSelectPic);
            if ($newAuthId === false) {
                return self::getCreateErrorView($user);
            }
            $jumpToUrl = $setDefault ? "auth" : "auth?" . HttpFormConstant::FORM_KEY_AUTH_ID . "=$newAuthId";
            return view('auth.addResult.index')->with('_USER', $user)->with("topNavValueText", "添加安全令")
                ->with("errorString", "生成安全令成功，即将跳转到该安全令页面")
                ->with('jsString', "")
                ->with("jumpToUrl", $jumpToUrl);
        } catch (\Exception $e) {
            return self::getCreateErrorView($user);
        }
    }


    public function addBySecret(Request $request)
    {
        $user = Auth::user();
        $checkCanAdd = self::checkCanAdd($request, $user);
        if ($checkCanAdd !== true) {
            return $checkCanAdd;
        }
        $postAuthCodeA2 = $request->input('authcodeA2');
        $postAuthCodeB2 = $request->input('authcodeB2');
        $postAuthCodeC2 = $request->input('authcodeC2');
        $postAuthSecret = $request->input('authkey');
        if (!Functions::isAuthCodeValid($postAuthCodeA2) || !Functions::isAuthCodeValid($postAuthCodeB2) || !Functions::isAuthCodeValid($postAuthCodeC2) ||
            !Functions::isAuthSecretValid($postAuthSecret)
        ) {
            return self::getInputErrorView($user);
        }
        $authSerial = $this->standardRegion[$this->postRegion] . "-$postAuthCodeA2-$postAuthCodeB2-$postAuthCodeC2";
        $serverTime = $this->authUtils->getAuthSyncInfo()
            ->getSyncList()[$this->standardRegion[$this->postRegion]][AuthSyncInfo::SYNC_SERVER_TIME];
        try {
            $auth = Authenticator::factory($authSerial, $postAuthSecret, $serverTime);
            $authSerial = $auth->serial();
            $authSecret = $auth->secret();
            $authRestoreCode = $auth->restore_code();
            Functions::registerOnBlizzardOneButtonLogin($auth);
            $setDefault = false;
            if ($this->authUtils->getAuthCount() == 0 || $this->authUtils->getDefaultAuth() == null) {
                $setDefault = true;
            } elseif ($request->input('morenauthset') == "on") {
                $setDefault = true;
            }
            if (empty($authSerial) || empty($authSecret) || empty($authRestoreCode)) {
                return self::getRestoreErrorView($user);
            }
            $newAuthId = DBHelper::addNewAuth($auth, $user, $setDefault, $this->authUtils, $this->postAuthName, $this->postSelectPic);
            if ($newAuthId === false) {
                return self::getRestoreErrorView($user);
            }
            $jumpToUrl = $setDefault ? "auth" : "auth?" . HttpFormConstant::FORM_KEY_AUTH_ID . "=$newAuthId";
            return view('auth.addResult.index')->with('_USER', $user)->with("topNavValueText", "添加安全令")
                ->with("errorString", "还原安全令成功，即将跳转到该安全令页面")
                ->with('jsString', "")
                ->with("jumpToUrl", $jumpToUrl);
        } catch (\Exception $e) {
            return self::getRestoreErrorView($user);
        }
    }


    public function addByRestoreCode(Request $request)
    {
        $user = Auth::user();
        $checkCanAdd = self::checkCanAdd($request, $user);
        if ($checkCanAdd !== true) {
            return $checkCanAdd;
        }
        $postAuthCodeA3 = $request->input('authcodeA3');
        $postAuthCodeB3 = $request->input('authcodeB3');
        $postAuthCodeC3 = $request->input('authcodeC3');
        $postAuthRestoreCode = $request->input('authrestore');
        if (!Functions::isAuthCodeValid($postAuthCodeA3) || !Functions::isAuthCodeValid($postAuthCodeB3) || !Functions::isAuthCodeValid($postAuthCodeC3) ||
            !Functions::isAuthRestoreCodeValid($postAuthRestoreCode)
        ) {
            return self::getInputErrorView($user);
        }
        $authSerial = $this->standardRegion[$this->postRegion] . "-$postAuthCodeA3-$postAuthCodeB3-$postAuthCodeC3";
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
            } elseif ($request->input('morenauthset') == "on") {
                $setDefault = true;
            }
            if (empty($authSerial) || empty($authSecret) || empty($authRestoreCode)) {
                return self::getRestoreErrorView($user);
            }
            $newAuthId = DBHelper::addNewAuth($auth, $user, $setDefault, $this->authUtils, $this->postAuthName, $this->postSelectPic);
            if ($newAuthId === false) {
                return self::getRestoreErrorView($user);
            }
            $jumpToUrl = $setDefault ? "auth" : "auth?" . HttpFormConstant::FORM_KEY_AUTH_ID . "=$newAuthId";
            return view('auth.addResult.index')->with('_USER', $user)->with("topNavValueText", "添加安全令")
                ->with("errorString", "还原安全令成功，即将跳转到该安全令页面")
                ->with('jsString', "")
                ->with("jumpToUrl", $jumpToUrl);
        } catch (\Exception $e) {
            return view('auth.addResult.index')->with('_USER', $user)->with("topNavValueText", "添加安全令")
                ->with("errorString", "输入的序列号与还原码不对应，请返回添加安全令页面重试")
                ->with('jsString', "")
                ->with('jumpBack', true)
                ->with("jumpToUrl", "addAuth");
        }
    }
}