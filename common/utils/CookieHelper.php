<?php

/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/12 0012
 * Time: 上午 8:32
 */

use App\User;
use Illuminate\Support\Facades\Cookie as Cookie;

class CookieHelper
{
    /**
     * 将Cookie输出到浏览器
     * @param $userName
     * @param $cookieValue
     * @param int $ttl
     */
    public function setCookie(CookieValue $cookieValue, $ttl)
    {
        Cookie::queue(KeyConstant::COOKIE_KEY_USER_NAME, $cookieValue->userName, $ttl);
        Cookie::queue(KeyConstant::COOKIE_KEY_USER_TOKEN, $cookieValue->cookieToken, $ttl);
    }

    /**
     * 清除Cookie
     */
    public function removeCookie()
    {
        Cookie::queue(Cookie::forget(KeyConstant::COOKIE_KEY_USER_TOKEN));
        Cookie::queue(Cookie::forget(KeyConstant::COOKIE_KEY_USER_NAME));
    }

    /**
     * Cookie键值对是否存在
     * @return bool|CookieValue
     */
    static function isCookieExist()
    {
        if (!Cookie::has(KeyConstant::COOKIE_KEY_USER_NAME) && !Cookie::has(KeyConstant::COOKIE_KEY_USER_TOKEN)) {
            return false;
        }
        $userName = Cookie::get(KeyConstant::COOKIE_KEY_USER_NAME);
        $cookieToken = Cookie::get(KeyConstant::COOKIE_KEY_USER_TOKEN);
        if (empty($userName) || empty($cookieToken)) {
            return false;
        }
        $cookieValue = new CookieValue();
        $cookieValue->userName = $userName;
        $cookieValue->cookieToken = $cookieToken;
        return $cookieValue;
    }

    /**
     * 1判断Cookie是否存在
     * 2从Redis里获取Json字符串
     * 3生成CookieBean
     * 4CookieBean判断是否有效
     * 获取CookieBean
     */
    public function getCookieBean()
    {
        $cookieValue = self::isCookieExist();
        if ($cookieValue === false) {
            return false;
        }
        $cookieJson = RedisHelper::getCookieJson($cookieValue->cookieToken);
        if ($cookieJson === false || empty($cookieJson)) {
            return false;
        }
        $cookieBean = new CookieBean();
        $cookieBean->getCookieBeanByJson($cookieValue, $cookieJson);
        if (!$cookieBean->getisCookieValid()) {
            RedisHelper::removeLoginCookie($cookieValue->cookieToken);
            self::removeCookie();
            return false;
        }
        return $cookieBean;
    }

    /**
     * 1判断Cookie是否存在
     * 2从Redis里获取Json字符串
     * 3生成CookieBean
     * 4CookieBean判断是否有效
     * 获取CookieBean
     */
    public function removeSavedCookie()
    {
        $cookieValue = self::isCookieExist();
        if ($cookieValue === false) {
            return;
        }
        RedisHelper::removeLoginCookie($cookieValue->cookieToken);
        self::removeCookie();
    }

    public function saveCookie(User $user)
    {
        $time = time();
        $cookie['userId'] = $user->getUserId();
        $cookie['userName'] = $user->getUserName();
        $cookie['loginIp'] = $user->getUserThisTimeLoginIP();
        $cookie['userCookie'] = $user->getUserId() . "_{$time}_" . Functions::getRandomString();
        if ($user->getUserRight() == User::USER_NORMAL) {
            $ttl = config("app.cookie_valid_hours");
        } else {
            $ttl = config("app.share_account_cookie_valid_hours");
        }
        $cookie['expireTimeStamp'] = $time + $ttl * 3600;
        $cookie['createTimeStamp'] = $time;
        $cookieValue = new CookieValue();
        $cookieValue->userName = $user->getUserName();
        $cookieValue->cookieToken = $cookie['userCookie'];
        self::setCookie($cookieValue, $ttl * 60);
        RedisHelper::saveLoginCookie($cookieValue->cookieToken, json_encode($cookie), $ttl * 3600);
    }

}


