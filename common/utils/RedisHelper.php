<?php

/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/11 0011
 * Time: 下午 21:44
 */
use Illuminate\Support\Facades\Redis;

class RedisHelper
{

    static function getCookieJson($cookieToken)
    {
        return Redis::get($cookieToken);
    }

    static function saveLoginCookie($cookieToken, $json, $ttl = -999)
    {
        if ($ttl == -999) {
            $ttl = config("app.cookie_valid_hours") * 3600;
        }
        Redis::setex($cookieToken, $ttl, $json);
    }

    static function removeLoginCookie($cookieToken)
    {
        Redis::del($cookieToken);
    }

    static function getWechatTokenJson($wechatTokenKey)
    {
        return Redis::get($wechatTokenKey);
    }

    public static function saveWechatToken($token, $tokenValue)
    {
        Redis::setex($token, 20 * 86400, $tokenValue);
    }
}