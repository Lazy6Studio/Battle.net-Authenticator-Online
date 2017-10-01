<?php
/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/12 0012
 * Time: 下午 13:16
 */

namespace App\Http\Controllers\StaticPage;

use App\Http\Controllers\Controller;
use App\User;
use DBHelper;
use Functions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DonatePageController extends Controller
{

    public function __construct()
    {
        $this->middleware('base.check');
    }

    function seeDonate(Request $request)
    {
        return view('static.donate.index')->with('_USER', Auth::user())->with("topNavValueText", "捐赠");
    }

    function addDonatePage(Request $request)
    {
        $user = Auth::user();
        if (!$user->getIsLogin()) {
            return view('static.donate.addDonateResult')->with('_USER', Auth::user())->with("topNavValueText", "添加捐赠")
                ->with("errorString", "请登录后再添加");
        }
        if (empty(config('app.admin_username'))) {
            return view('static.donate.addDonateResult')->with('_USER', Auth::user())->with("topNavValueText", "添加捐赠")
                ->with("errorString", "请先配置管理员信息再添加");
        }
        if (strtoupper(config('app.admin_username')) != strtoupper($user->getUserName())) {
            return view('static.donate.addDonateResult')->with('_USER', Auth::user())->with("topNavValueText", "添加捐赠")
                ->with("errorString", "您不是管理员，无法添加捐赠信息");
        }
        return view('static.donate.addDonate')->with('_USER', Auth::user())->with("topNavValueText", "添加捐赠");
    }

    function addDonatePost(Request $request)
    {
        $user = Auth::user();
        if (!$user->getIsLogin()) {
            return view('static.donate.addDonateResult')->with('_USER', Auth::user())->with("topNavValueText", "添加捐赠")
                ->with("errorString", "请登录后再添加");
        }
        if (empty(config('app.admin_username'))) {
            return view('static.donate.addDonateResult')->with('_USER', Auth::user())->with("topNavValueText", "添加捐赠")
                ->with("errorString", "请先配置管理员信息再添加");
        }
        if (strtoupper(config('app.admin_username')) != strtoupper($user->getUserName())) {
            return view('static.donate.addDonateResult')->with('_USER', Auth::user())->with("topNavValueText", "添加捐赠")
                ->with("errorString", "您不是管理员，无法添加捐赠信息");
        }
        $donateName = $request->input('donateName', "匿名土豪");
        $donateTime = $request->input('donateTime');
        $donateCurrency = $request->input("donateCurrency");
        $donateCount = $request->input("donateCount");
        $donateUserName = $request->input('userName');
        if (empty($donateCurrency)) {
            return view('static.donate.addDonate')->with('_USER', Auth::user())->with("topNavValueText", "添加捐赠")
                ->with("errorString", "请填写币种信息。");
        }
        if (empty($donateCount)) {
            return view('static.donate.addDonate')->with('_USER', Auth::user())->with("topNavValueText", "添加捐赠")
                ->with("errorString", "请填写捐赠数量。");
        }
        if (empty($donateTime) || strtotime($donateTime) == false) {
            $donateTime = time();
        } else {
            $donateTime = strtotime($donateTime);
        }
        $result = DBHelper::addDonateInfo($donateName, $donateTime, $donateCurrency, $donateCount);
        if ($result === false) {
            return view('static.donate.addDonateResult')->with('_USER', Auth::user())->with("topNavValueText", "添加捐赠")
                ->with("errorString", "添加捐赠信息失败，数据库写入错误");
        }
        if (!empty($donateUserName)) {
            $user = new User();
            $user->initUserByUserName($donateUserName);
            if (!empty($user->getUserId()) && Functions::isInt($user->getUserId())) {//该操作自动设置用户解禁
                if($user->getUserRight()==User::USER_BANED){
                    $user->setUserRight(User::USER_NORMAL);
                }
                $user->setUserDonated(1);
                DBHelper::updateUserSetDonate($user);
            }
        }
        return view('static.donate.addDonateResult')->with('_USER', Auth::user())->with("topNavValueText", "添加捐赠")
            ->with("errorString", "添加捐赠信息成功");
    }
}