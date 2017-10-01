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
use DBHelper;
use Functions;
use HttpFormConstant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthDeletePageController extends Controller
{
    private $authUtils;

    function __construct()
    {
        $this->middleware('base.check');
    }

    function isAuthValidAndBelongToUserByGivenAuthId(Request $request, User $user, $authId)
    {
        if (!$user->getIsLogin()) {
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
                $encodeUrl = urlencode(base64_encode("deleteAuth?" . HttpFormConstant::FORM_KEY_AUTH_ID . "=" . $authId));
                $encodeName = urlencode(base64_encode("删除安全令"));
                return redirect("login?from=$encodeUrl&fromName=$encodeName");
            }
            $encodeUrl = urlencode(base64_encode("auth"));
            $encodeName = urlencode(base64_encode("查看安全令"));
            return redirect("login?from=$encodeUrl&fromName=$encodeName");
        }
        if ($user->getUserRight() != User::USER_NORMAL) {
            return view('auth.delete.index')->with("_USER", $user)->with("topNavValueText", "删除安全令")
                ->with('errorString', "共享账号不能删除安全令，即将返回主页")->with("jumpToUrl", "");
        }
        if (empty($authId) || !Functions::isInt($authId)) {
            return view('auth.delete.index')->with("_USER", $user)->with("topNavValueText", "删除安全令")
                ->with('errorString', "安全令编号错误，请检查后再试，即将返回我的安全令页面")->with("jumpToUrl", "myAuthList");
        }
        $authBean = self::isAuthValidAndBelongToUserByGivenAuthId($request, $user, $authId);
        if ($authBean == false) {
            return view('auth.delete.index')->with("_USER", $user)->with("topNavValueText", "删除安全令")
                ->with('errorString', "您没有该安全令的所有权，请检查后再试，即将返回我的安全令页面")->with("jumpToUrl", "myAuthList");
        }
        if (!($authBean->getAuthDefault()) || $this->authUtils->getAuthCount() == 1) {
            $deleteResult = DBHelper::deleteAuth($authBean);
            if ($deleteResult) {
                return view('auth.delete.index')->with("_USER", $user)->with("topNavValueText", "删除安全令")
                    ->with('errorString', "删除成功，如为误删，请联系管理员找回，即将返回我的安全令页面")->with("jumpToUrl", "myAuthList");
            }
            return view('auth.delete.index')->with("_USER", $user)->with("topNavValueText", "删除安全令")
                ->with('errorString', "删除失败，请稍后重试，即将返回该安全令页面")->with("jumpToUrl", "auth?" . HttpFormConstant::FORM_KEY_AUTH_ID . "=" . $authId);
        }
        $setNextDefaultAuthId = -1;
        foreach ($this->authUtils->getAuthList() as $authTemp) {
            if ($authTemp->getAuthId() != $authBean->getAuthId() && !($authTemp->getAuthDefault())) {
                $setNextDefaultAuthId = $authTemp->getAuthId();
                break;
            }
        }
        $deleteResult = DBHelper::deleteAuth($authBean);
        if ($deleteResult) {
            $deleteResult = DBHelper::updateAuthSetDefault($user->getUserId(), $setNextDefaultAuthId);
            return view('auth.delete.index')->with("_USER", $user)->with("topNavValueText", "删除安全令")
                ->with('errorString', "删除成功，如为误删，请联系管理员找回，即将返回我的安全令页面")->with("jumpToUrl", "myAuthList");
        }
        return view('auth.delete.index')->with("_USER", $user)->with("topNavValueText", "删除安全令")
            ->with('errorString', "删除失败，请稍后重试，即将返回该安全令页面")->with("jumpToUrl", "auth?" . HttpFormConstant::FORM_KEY_AUTH_ID . "=" . $authId);
    }
}