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
use ResetPasswordUtils;


class ResetPasswordPageController extends Controller
{


    public function __construct()
    {
        $this->middleware('base.check');
    }

    function get(Request $request)
    {
        $user = Auth::user();
        $resetPasswordUtils = new ResetPasswordUtils($request);
        $resetPasswordUtils->doGetCheck();
        if ($resetPasswordUtils->getResetPasswordErrorCode() == 0) {
            return view('account.resetPassword.index')->with('_USER', $user)->with("topNavValueText", "重置密码")
                ->with("resetPasswordUtils", $resetPasswordUtils)->with("errorString", "");
        }
        return view('account.resetPassword.error')->with('_USER', $user)->with("topNavValueText", "重置密码")
            ->with("errorString", $resetPasswordUtils->getErrorString($resetPasswordUtils->getResetPasswordErrorCode()))
            ->with("jumpUrl", config('app.url') . "forgetPassword");
    }

    function post(Request $request)
    {
        $user = Auth::user();
        $resetPasswordUtils = new ResetPasswordUtils($request);
        $resetPasswordUtils->doPostCheck($request);
        if ($resetPasswordUtils->getResetPasswordErrorCode() == 0) {
            return view('account.resetPassword.error')->with('_USER', $user)->with("topNavValueText", "重置密码")
                ->with("errorString", $resetPasswordUtils->getErrorString($resetPasswordUtils->getResetPasswordErrorCode()))
                ->with("jumpUrl", config('app.url') . "login");
        }
        if ($resetPasswordUtils->getResetPasswordErrorCode() == 6 || $resetPasswordUtils->getResetPasswordErrorCode() == 7) {
            return view('account.resetPassword.index')->with('_USER', $user)->with("topNavValueText", "重置密码")
                ->with("resetPasswordUtils", $resetPasswordUtils)->with("errorString", $resetPasswordUtils->getErrorString($resetPasswordUtils->getResetPasswordErrorCode()));
        }
        return view('account.resetPassword.error')->with('_USER', $user)->with("topNavValueText", "重置密码")
            ->with("errorString", $resetPasswordUtils->getErrorString($resetPasswordUtils->getResetPasswordErrorCode()))
            ->with("jumpUrl", config('app.url') . "forgetPassword");
    }
}