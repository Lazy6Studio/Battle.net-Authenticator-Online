<?php
/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/11 0011
 * Time: 下午 12:11
 */

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use ChangePasswordUtils;
use KeyConstant;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use RegisterCheckUtils;


class ChangePasswordPageController extends Controller
{


    public function __construct()
    {
        $this->middleware('base.check');
    }

    function get(Request $request)
    {
        $user = Auth::user();
        if (!$user->getIsLogin()) {
            return view('account.changePassword.needLogin')->with('_USER', $user)->with("topNavValueText", "修改密码");
        }
        if ($user->getUserRight() == User::USER_BANED) {
            return view('account.changePassword.cannotChange')->with("reason", "您的账号已被封禁，无法修改密码")->with('_USER', $user)->with("topNavValueText", "修改密码");
        }
        if ($user->getUserRight() == User::USER_SHARED) {
            return view('account.changePassword.cannotChange')->with("reason", "共享账号无法修改密码")->with('_USER', $user)->with("topNavValueText", "修改密码");
        }
        $changePasswordUtils = new ChangePasswordUtils($request);
        return view('account.changePassword.index')->with("changePasswordUtils", $changePasswordUtils)->with('_USER', $user)->with("topNavValueText", "修改密码");
    }

    function post(Request $request)
    {
        $user = Auth::user();
        if (!$user->getIsLogin()) {
            return view('account.changePassword.needLogin')->with('_USER', $user)->with("topNavValueText", "修改密码");
        }
        if ($user->getUserRight() == User::USER_BANED) {
            return view('account.changePassword.cannotChange')->with("reason", "您的账号已被封禁，无法修改密码")->with('_USER', $user)->with("topNavValueText", "修改密码");
        }
        if ($user->getUserRight() == User::USER_SHARED) {
            return view('account.changePassword.cannotChange')->with("reason", "共享账号无法修改密码")->with('_USER', $user)->with("topNavValueText", "修改密码");
        }
        $changePasswordUtils = new ChangePasswordUtils($request);
        $changePasswordUtils->doChange($request, $user);
        if ($changePasswordUtils->getChangePasswordErrorCode() == 0) {
            return view('account.changePassword.success')->with("changePasswordUtils", $changePasswordUtils)->with('_USER', $user)->with("topNavValueText", "修改密码成功");
        }
        return view('account.changePassword.index')->with("changePasswordUtils", $changePasswordUtils)->with('_USER', $user)->with("topNavValueText", "修改密码");
    }
}