<?php
/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/12 0012
 * Time: 下午 13:16
 */

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\User;
use DBHelper;
use Functions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class BanUserPageController extends Controller
{

    public function __construct()
    {
        $this->middleware('base.check');
    }

    function get(Request $request)
    {
        $user = Auth::user();
        if (!$user->getIsLogin()) {
            return view('account.banUser.banUserResult')->with('_USER', Auth::user())->with("topNavValueText", "封禁用户")
                ->with("errorString", "请登录后再操作");
        }
        if (empty(config('app.admin_username'))) {
            return view('account.banUser.banUserResult')->with('_USER', Auth::user())->with("topNavValueText", "封禁用户")
                ->with("errorString", "请先配置管理员信息再操作");
        }
        if (strtoupper(config('app.admin_username')) != strtoupper($user->getUserName())) {
            return view('account.banUser.banUserResult')->with('_USER', Auth::user())->with("topNavValueText", "封禁用户")
                ->with("errorString", "您不是管理员，无法封禁用户信息");
        }
        return view('account.banUser.index')->with('_USER', Auth::user())->with("topNavValueText", "封禁用户");
    }

    function post(Request $request)
    {
        $user = Auth::user();
        if (!$user->getIsLogin()) {
            return view('account.banUser.banUserResult')->with('_USER', Auth::user())->with("topNavValueText", "封禁用户")
                ->with("errorString", "请登录后再添加");
        }
        if (empty(config('app.admin_username'))) {
            return view('account.banUser.banUserResult')->with('_USER', Auth::user())->with("topNavValueText", "封禁用户")
                ->with("errorString", "请先配置管理员信息再添加");
        }
        if (strtoupper(config('app.admin_username')) != strtoupper($user->getUserName())) {
            return view('account.banUser.banUserResult')->with('_USER', Auth::user())->with("topNavValueText", "封禁用户")
                ->with("errorString", "您不是管理员，无法封禁用户信息");
        }
        $banUserName = $request->input('userName');
        if (strtoupper($banUserName) == strtoupper(config('app.admin_username'))) {
            return view('account.banUser.banUserResult')->with('_USER', Auth::user())->with("topNavValueText", "封禁用户")
                ->with("errorString", "您不能封禁自己");
        }
        $user = new User();
        $user->initUserByUserName($banUserName);
        if (empty($user->getUserId()) || !Functions::isInt($user->getUserId())) {
            return view('account.banUser.banUserResult')->with('_USER', Auth::user())->with("topNavValueText", "封禁用户")
                ->with("errorString", "用户：" . $banUserName . "不存在，请检查后再试");
        }
        $user->setUserRight(User::USER_BANED);
        $result = DBHelper::banUser($user);
        if ($result === false) {
            return view('account.banUser.banUserResult')->with('_USER', Auth::user())->with("topNavValueText", "封禁用户")
                ->with("errorString", "封禁用户失败，数据库写入错误");
        }
        return view('account.banUser.banUserResult')->with('_USER', Auth::user())->with("topNavValueText", "封禁用户")
            ->with("errorString", "封禁用户成功");
    }
}