<?php
/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/11 0011
 * Time: 下午 12:11
 */

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use KeyConstant;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use LoginCheckUtils;
use RegisterCheckUtils;


class LoginPageController extends Controller
{


    public function __construct()
    {
        $this->middleware('base.check');
    }

    function iframeGet(Request $request)
    {
        $user = Auth::user();
        if ($user->getIsLogin()) {
            return view('account.iframeLogin.logined')->with("_USER", $user)->with("topNavValueText", "登入");
        }
        $loginCheckUtils = new LoginCheckUtils($request);
        return view('account.iframeLogin.index')->with("_USER", $user)->with("topNavValueText", "登入")
            ->with("loginCheckUtils", $loginCheckUtils);
    }

    function iframePost(Request $request)
    {
        $user = Auth::user();
        if ($user->getIsLogin()) {
            return view('account.iframeLogin.logined')->with("_USER", $user)->with("topNavValueText", "登入");
        }
        $loginCheckUtils = new LoginCheckUtils($request);
        $loginCheckUtils->doCheck($request);
        $user = Auth::user();
        if ($loginCheckUtils->getLoginErrorCode() == 0) {
            return view('account.iframeLogin.success')->with("_USER", $user)->with("topNavValueText", "登入成功");
        }
        if ($loginCheckUtils->getLoginErrorCode() == -1) {
            return view('account.iframeLogin.lowRight')->with("_USER", $user)->with("topNavValueText", "共享账号等待登录");
        }
        return view('account.iframeLogin.index')->with("_USER", $user)
            ->with("topNavValueText", "登入")->with("loginCheckUtils", $loginCheckUtils);
    }


    function normalGet(Request $request)
    {
        $user = Auth::user();
        if ($user->getIsLogin()) {
            return view('account.login.logined')->with("_USER", $user)->with("topNavValueText", "登入");
        }
        $loginCheckUtils = new LoginCheckUtils($request);
        return view('account.login.index')->with("_USER", $user)->with("topNavValueText", "登入")
            ->with("loginCheckUtils", $loginCheckUtils)
            ->with('from', $request->input('from', ""))
            ->with('fromName', $request->input('fromName', urlencode(base64_encode("首页"))));
    }

    function normalPost(Request $request)
    {
        $user = Auth::user();
        if ($user->getIsLogin()) {
            return view('account.login.logined')->with("_USER", $user)->with("topNavValueText", "登入");
        }
        $loginCheckUtils = new LoginCheckUtils($request);
        $loginCheckUtils->doCheck($request);
        $user = Auth::user();
        if ($loginCheckUtils->getLoginErrorCode() == 0) {
            return view('account.login.success')->with("_USER", $user)->with("topNavValueText", "登入成功")
                ->with('from', base64_decode($request->input('from', "")))
                ->with('fromName', base64_decode($request->input('fromName',base64_encode("首页"))));
        }
        if ($loginCheckUtils->getLoginErrorCode() == -1) {
            return view('account.login.lowRight')->with("_USER", $user)->with("topNavValueText", "共享账号等待登录");
        }
        return view('account.login.index')->with("_USER", $user)
            ->with("topNavValueText", "登入")->with("loginCheckUtils", $loginCheckUtils)
            ->with('from', ($request->input('from', "")))
            ->with('fromName', ($request->input('fromName', "")));
    }
}