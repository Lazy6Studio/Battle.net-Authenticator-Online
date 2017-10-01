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
use AuthUtils;
use Functions;
use HttpFormConstant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthDetailPageController extends Controller
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
        $isAuthBelngToThisUser = false;
        $authBean = new AuthBean();
        foreach ($this->authUtils->getAuthList() as $auth) {
            if ($auth->getAuthId() == $authId) {
                $isAuthBelngToThisUser = true;
                $authBean = $auth;
                break;
            }
        }
        if (!$isAuthBelngToThisUser || $authBean->getAuthId() != $authId) {
            return false;
        }
        return $authBean;
    }

    public function get(Request $request)
    {
        $authId = $request->input(HttpFormConstant::FORM_KEY_AUTH_ID);
        $user = Auth::user();
        if (!$user->getIsLogin()) {//未登录，跳登录
            if (!empty($authId) && Functions::isInt($authId)) {
                $encodeUrl = urlencode(base64_encode("auth?" . HttpFormConstant::FORM_KEY_AUTH_ID . "=" . $authId));
                $encodeName = urlencode(base64_encode("查看安全令"));
                return redirect("login?from=$encodeUrl&fromName=$encodeName");
            }
            $encodeUrl = urlencode(base64_encode("auth"));
            $encodeName = urlencode(base64_encode("查看安全令"));
            return redirect("login?from=$encodeUrl&fromName=$encodeName");
        }
        if (!empty($authId) && Functions::isInt($authId)) {//设置了安全令信息
            $authBean = self::isAuthValidAndBelongToUser($request, $user);
            if ($this->authUtils == null || $this->authUtils->getAuthCount() == 0) {
                return self::getNeedAddAuthErrorPage($request, $user, false);
            }
            if ($authBean != false) {//属于，返回指定安全令
                return $this->getAuthPageById($request, $user, $authBean, false);
            }
            return self::getNotBelongErrorPage($request, $user);
        }
        return $this->getDefaultAuthPage($request, $user);
    }

    public function getNotBelongErrorPage(Request $request, User $user)
    {
        if ($this->authUtils == null || $this->authUtils->getAuthCount() == 0) {
            return view('auth.get.error')->with("_USER", $user)->with("topNavValueText", "查看安全令")
                ->with('errorInfo', "您没有该安全令的所有权，您并没有添加过安全令，即将跳转到添加安全令页面")
                ->with('nextPageUrl', "addAuth");
        }
        return view('auth.get.error')->with("_USER", $user)->with("topNavValueText", "查看安全令")
            ->with('errorInfo', "您没有该安全令的所有权，即将跳转到默认安全令页面")
            ->with('nextPageUrl', "auth");
    }

    public function getNeedAddAuthErrorPage(Request $request, User $user, $fromGetDefault)
    {
        if ($fromGetDefault) {
            return "添加安全令";
        } else {
            return view('auth.get.error')->with("_USER", $user)->with("topNavValueText", "查看安全令")
                ->with('errorInfo', "您还没有添加安全令，请添加后再查看，即将跳转到添加安全令页面")
                ->with('nextPageUrl', "addAuth");
        }
    }

    public function getDefaultAuthPage(Request $request, User $user)
    {
        if ($this->authUtils == null) {
            $this->authUtils = new AuthUtils();
            $this->authUtils->getAllAuth($user);
        }
        if ($this->authUtils->getAuthCount() == 0) {
            return self::getNeedAddAuthErrorPage($request, $user, true);
        }
        return $this->getAuthPageById($request, $user, $this->authUtils->getDefaultAuth(), true);
    }

    public function getAuthPageById(Request $request, User $user, AuthBean $authBean, $fromGetDefault)
    {
        return view('auth.get.index')->with("_USER", $user)->with("topNavValueText", $fromGetDefault ? "默认安全令" : "查看安全令")
            ->with("authUtils", $this->authUtils)->with('authBean', $authBean)
            ->with('fromGetDefault', $fromGetDefault)
            ->with('pageUrl', $fromGetDefault ? "/auth" : "/auth?" . HttpFormConstant::FORM_KEY_AUTH_ID . "=" . $authBean->getAuthId());
    }
}