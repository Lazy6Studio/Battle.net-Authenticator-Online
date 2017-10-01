<?php

namespace App\Http\Middleware;

use Closure;
use CookieBean;
use Functions;
use Illuminate\Http\Request as Request;
use Illuminate\Support\Facades\Auth;
use KeyConstant;
use App\User;
use DBHelper;
use CookieHelper;
use RedisHelper;

class UserCheckMiddleware
{
    const TAG = "UserCheckMiddleware";

    /**
     * 如果是封禁页面，传值:true
     * @param Request $request
     * @param Closure $next
     * @param bool $disableRedirect
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $disableRedirect = false)
    {
        self::checkUser($request);
        if (!$disableRedirect) {
            return $next($request);
        }
        if (Auth::user() != null && Auth::user() instanceof User) {
            if (Auth::user()->getUserRight() == User::USER_BANED) {
                return response(view('error.dbError', compact('topNavValueText')));
            }
        }
    }

    /**
     * 生成User
     * 判断是否有session
     * 存在的话，获取UserId
     * 判断UserId，生成用户，设置登录状态为真
     *
     * 判断Cookie存在，
     * @param Request $request
     */
    private function checkUser(Request $request)
    {
        if ($request->session()->has(KeyConstant::SESSION_USERID)) {
            $user = new User();
            Auth::setUser($user);
            $user->initUserByUserId($request->session()->get(KeyConstant::SESSION_USERID));
            if (!empty($user->getUserId()) && Functions::isInt($user->getUserId())) {
                if ($user->getUserRight() == User::USER_BANED) {
                    $user = new User();
                    Auth::setUser($user);
                    $request->session()->flush();
                    $cookieHelper = new CookieHelper();
                    $cookieHelper->removeSavedCookie();
                    return;
                }
                $user->setIsLogin(true);
                $user->setLastUsedSessionTime(time());
            }
            return;
        }
        $user = new User();
        Auth::setUser($user);
        $cookieHelper = new CookieHelper();
        $cookieBean = $cookieHelper->getCookieBean();
        if ($cookieBean === false) {
            return;
        }
        //从Cookie去寻找用户
        $user->initUserByUserId($cookieBean->getUserId());
        if (empty($user->getUserId()) || !Functions::isInt($user->getUserId()) ||
            empty($user->getUserName()) || $cookieBean->getUserName() !== $user->getUserName()
        ) {
            self::removeLoginState($request, $cookieHelper);
            $request->session()->flush();
            RedisHelper::removeLoginCookie($cookieBean->getUserCookie());
            $cookieHelper->removeCookie();
            return;
        }
        if ($user->getLastResetPasswordTime() >= $cookieBean->getCreateTimeStamp()) {
            self::removeLoginState($request, $cookieHelper);
            return;
        }
        if ($user->getUserRight() == User::USER_BANED) {
            $user = new User();
            Auth::setUser($user);
            self::removeLoginState($request, $cookieHelper);
            return;
        }
        if ($user->getUserRight() == User::USER_NORMAL) {
            $request->session()->put(KeyConstant::SESSION_USERID, $user->getUserId());
        }
        $user->setIsLogin(true);
        $user->setLastUsedSessionTime(time());
        $user->setUserLastLoginTime($user->getUserThisLoginTime());
        $user->setUserLastTimeLoginIP($user->getUserThisTimeLoginIP());
        $user->setUserThisLoginTime(date('Y-m-d H:i:s'));
        $user->setUserThisTimeLoginIP($request->ip());
        DBHelper::updateUserLastUseSessionTime($user);
    }

    function removeLoginState(Request $request, CookieHelper $cookieHelper)
    {
        $request->session()->flush();
        $cookieHelper->removeSavedCookie();
        $cookieHelper->removeCookie();
    }
}
