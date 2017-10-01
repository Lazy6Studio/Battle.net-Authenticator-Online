<?php
/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/11 0011
 * Time: 下午 12:11
 */

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use CookieHelper;
use KeyConstant;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use RegisterCheckUtils;


class LogoutPageController extends Controller
{


    public function __construct()
    {
        $this->middleware('base.check');
    }


    function doLogout(Request $request)
    {//POST可能是提交数据的模式，所以判断是否是刚注册成功，是的话跳正确，否则跳失败
        $user = Auth::user();
        if (!$user->getIsLogin()) {
            return view('account.logout.notLogin')->with('_USER',$user)->with("topNavValueText", "登出");
        }
        $user = new User();
        Auth::setUser($user);
        $cookieHelper = new CookieHelper();
        $cookieHelper->removeSavedCookie();
        $request->session()->flush();
        return view('account.logout.success')->with('_USER',$user)->with("topNavValueText", "登出");
    }
}