<?php
use Illuminate\Support\Facades\Cache;

/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/15 0015
 * Time: 下午 16:00
 */
class CacheHelper
{
    /**
     * 缓存安全令服务器更新时间信息
     */
    public static final function removeCachedAuthSyncTimeInfo()
    {
        Cache::forget(KeyConstant::CACHE_KEY_AUTH_SERVER_SYNC);
    }

########################################################################################################################

    /**
     * 清除用户安全令缓存数据
     * @param $userId
     */
    public static final function removeCachedUserAuthInfo($userId)
    {
        Cache::forget(KeyConstant::CACHE_KEY_USER_AUTH_LIST . $userId);
    }

########################################################################################################################

    /**
     * 清除用户安全令缓存数据
     */
    public static final function removeCachedDonatedInfo()
    {
        Cache::forget(KeyConstant::CACHE_KEY_DONATE_INFO);
    }

########################################################################################################################

    /**
     * 清除用户缓存数据
     * @param $userId
     */
    public static function removeCachedUserInfo($userId)
    {
        Cache::forget(KeyConstant::CACHE_KEY_USER_ID . $userId);
    }

########################################################################################################################

    /**
     * 清除用户缓存数据
     * @param $openId
     * @internal param $userId
     */
    public static function removeCachedWechatOpenIdInfo($openId)
    {
        Cache::forget(KeyConstant::CACHE_KEY_USER_WECHAT_OPEN_ID . $openId);
    }


}