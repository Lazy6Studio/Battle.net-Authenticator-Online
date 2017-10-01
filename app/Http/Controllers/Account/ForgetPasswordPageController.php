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
use ForgetPasswordCheckUtils;
use KeyConstant;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use RegisterCheckUtils;


class ForgetPasswordPageController extends Controller
{


    public function __construct()
    {
        $this->middleware('base.check');
    }

    function get(Request $request)
    {
        $user = Auth::user();
        $forgetPasswordCheckUtils = new ForgetPasswordCheckUtils($request);
        return view('account.forgetPassword.index')->with('_USER', $user)->with("topNavValueText", "无法登入？")
            ->with("forgetPasswordCheckUtils", $forgetPasswordCheckUtils);
    }

    function post(Request $request)
    {
        $forgetPasswordCheckUtils = new ForgetPasswordCheckUtils($request);
        $forgetPasswordCheckUtils->doCheck($request);
        $user = Auth::user();
        if ($forgetPasswordCheckUtils->getForgetPasswordCheckErrorCode() == 0) {
            return view('account.forgetPassword.success')->with('_USER', $user)->with("topNavValueText", "无法登入？")
                ->with("forgetPasswordCheckUtils", $forgetPasswordCheckUtils);
        }
        return view('account.forgetPassword.index')->with('_USER', $user)->with("topNavValueText", "无法登入？")
            ->with("forgetPasswordCheckUtils", $forgetPasswordCheckUtils);
    }
}