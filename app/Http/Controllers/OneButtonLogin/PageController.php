<?php
/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/15 0015
 * Time: 下午 16:22
 */

namespace App\Http\Controllers\OneButtonLogin;


use App\Http\Controllers\Controller;
use App\User;
use AuthBean;
use AuthUtils;
use Functions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    private $authUtils;

    function __construct()
    {
        $this->middleware('base.check');
    }

    public function get(Request $request)
    {
        $user = Auth::user();
        if (!$user->getIsLogin()) {
            return view('oneButtonLogin.error')->with("_USER", $user)->with("topNavValueText", "一键安全令")
                ->with('errorString', "您需要登录后才能使用");
        }
        $json = $request->input('json');
        if (empty($json)) {
            return view('oneButtonLogin.error')->with("_USER", $user)->with("topNavValueText", "一键安全令")
                ->with('errorString', "您没有权限使用该串进行一键登录操作");
        }
        $jsonArray = json_decode($json, true);
        if (self::checkOneButtonLoginCommitJsonArrayValid($request, $jsonArray, $user) === false) {
            return view('oneButtonLogin.error')->with("_USER", $user)->with("topNavValueText", "一键安全令")
                ->with('errorString', "您没有权限使用该串进行一键登录操作");
        }
        if (time() - $jsonArray['data']['time'] > 180) {
            return view('oneButtonLogin.error')->with("_USER", $user)->with("topNavValueText", "一键安全令")
                ->with('errorString', ">您的登录操作已经超时，请重新回到战网或其客户端提交");
        }
        return view('oneButtonLogin.index')->with("_USER", $user)->with("topNavValueText", "一键安全令")
            ->with('jsonArray', $jsonArray)->with('gotJson', $json)->with('sendTimeString',self::getTimeText($jsonArray['data']['time']));
    }

    function getTimeText($time)
    {
        $nowTime = time();
        if ($nowTime - $time < 10) {
            return "刚刚";
        }
        if ($nowTime - $time < 60) {
            return ($nowTime - $time) . "秒前";
        }
        return (ceil(($nowTime - $time) / 60) - 1) . "分钟前";
    }

    private function checkOneButtonLoginCommitJsonArrayValid(Request $request, $jsonArray, User $user)
    {
        if ($jsonArray == null) {
            return false;
        }
        if ($jsonArray['code'] != 0) {
            return false;
        }
        if ($jsonArray['data'] == null) {
            return false;
        }
        if ($jsonArray['data']['callback_url'] == null || $jsonArray['data']['callback_url'] == "") {
            return false;
        }
        if ($jsonArray['data']['request_id'] == null || $jsonArray['data']['request_id'] == "") {
            return false;
        }
        if ($jsonArray['data']['time'] == null || $jsonArray['data']['time'] == "") {
            return false;
        }
        $authId = $jsonArray['data']['auth_id'];
        return $this->isAuthValidAndBelongToUser($request, $user, $authId);
    }


    function isAuthValidAndBelongToUser(Request $request, User $user, $authId)
    {
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
}