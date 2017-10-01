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
use RegisterCheckUtils;


class RegisterPageController extends Controller
{


    public function __construct()
    {
        $this->middleware('base.check');
    }

    function get(Request $request)
    {//GET不可能提交数据的
        $user = Auth::user();
        if ($user->getIsLogin()) {
            return view('account.register.logined')->with('_USER', $user)->with("topNavValueText", "注册");
        }
        return view('account.register.index')->with('_USER', $user)->with("topNavValueText", "注册");
    }

    function post(Request $request)
    {//POST可能是提交数据的模式，所以判断是否是刚注册成功，是的话跳正确，否则跳失败
        $user = Auth::user();
        if ($user->getIsLogin()) {
            return view('account.register.logined')->with('_USER', $user)->with("topNavValueText", "注册");
        }
        $registerCheckUtils = new RegisterCheckUtils($request);
        if ($registerCheckUtils->getRegisterErrorCode() > 0) {
            return view('account.register.index')->with('_USER', $user)
                ->with("topNavValueText", "注册")
                ->with("registerErrorInfo", $registerCheckUtils->getErrorString($registerCheckUtils->getRegisterErrorCode()));
        }
        $registerCheckUtils->doRegister($request);
        $user = Auth::user();
        if ($registerCheckUtils->getRegisterErrorCode() > 0) {
            return view('account.register.index')->with('_USER', $user)
                ->with("topNavValueText", "注册")
                ->with("registerErrorInfo", $registerCheckUtils->getErrorString($registerCheckUtils->getRegisterErrorCode()));
        }
        return view('account.register.success')->with('_USER',$user)
            ->with("topNavValueText", "注册");
    }
}