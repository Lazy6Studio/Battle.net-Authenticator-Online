<?php

use App\User;

class DBHelper
{
    const TABLE_USER = "t_user";
    const TABLE_AUTH_DATA = "t_auth";
    const TABLE_DELETED_AUTH_DATA = "t_deleted_auth";
    const TABLE_DONATE_DATA = "t_donate";
    const TABLE_SYNCTIME_DATA = "t_sync_time";

    /**
     * 获取用户数量
     * @return mixed
     */
    static function getUserCount()
    {
        return Cache::remember(KeyConstant::CACHE_KEY_USER_COUNT, 15, function () {
            return DB::table(self::TABLE_USER)->count();
        });
    }

    /**
     * 获取安全令数量
     * @return mixed
     */
    static function getAuthCount()
    {
        return Cache::remember(KeyConstant::CACHE_KEY_AUTH_COUNT, 15, function () {
            return DB::table(self::TABLE_AUTH_DATA)->count();
        });
    }

    /**
     * 获取用户名称数量
     * @param $name
     */
    public static function getUserNameCount($name)
    {
        return DB::table(self::TABLE_USER)->where('user_name', $name)->count();
    }

    /**
     * 使用用户ID获取用户
     * @param $userId
     * @return mixed
     */
    static function getUserByUserId($userId)
    {
        return Cache::rememberForever(KeyConstant::CACHE_KEY_USER_ID . $userId, function () use ($userId) {
            return DB::table(self::TABLE_USER)->where('user_id', $userId)->first();
        });
    }

    /**
     * 使用OPENID获取用户
     * @param $openId
     * @return mixed
     */
    public static function getUserByWechatOpenId($openId)
    {
        $userId = Cache::rememberForever(KeyConstant::CACHE_KEY_USER_WECHAT_OPEN_ID . $openId, function () use ($openId) {
            $data = DB::table(self::TABLE_USER)->where('user_wechat_openid', $openId)->first();
            if ($data != false) {
                $data = $data->user_id;
            }
            return $data;
        });
        if (empty($userId)) {
            return false;
        }
        if (Functions::isInt($userId)) {
            $userId = self::getUserByUserId($userId);
        }
        return $userId;
    }


    /**
     * 使用用户名及ID查找用户
     * @param $userName
     * @param $userId
     * @return mixed
     */
    public static function getUserByUserNameAndUserId($userName, $userId)
    {
        $value = self::getUserByUserId($userId);
        if ($value->user_name == $userName) {
            return $value;
        }
        return false;
    }

    /**
     * 使用用户名及ID查找用户
     * @param $userName
     * @param $userId
     * @return mixed
     */
    public static function getUserByUserName($userName)
    {
        return DB::table(self::TABLE_USER)->where('user_name', $userName)->first();
    }

    /**
     * 使用用户名密码获取用户
     * @param $userName
     * @param $userPass
     * @return null
     */
    static function getUserByNameAndPassword($userName, $userPass)
    {
        return DB::table(self::TABLE_USER)->where('user_name', $userName)->where('user_pass', $userPass)->first();
    }

    /**
     * 更新用户上次使用Cookie或者正常登陆的时间，用于校验提交密码的正确性
     * @param User $user
     * @return mixed
     */
    public static function updateUserLastUseSessionTime(User $user)
    {
        if (empty($user->getUserId())) {
            return false;
        }
        CacheHelper::removeCachedUserInfo($user->getUserId());
        return DB::table(self::TABLE_USER)->where('user_id', $user->getUserId())->update(['user_last_used_session_time' => $user->getLastUsedSessionTime()]);
    }

    /**
     * 更新用户密码
     * @param User $user
     * @return mixed
     */
    public static function updateUserPassword(User $user)
    {
        if (empty($user->getUserId())) {
            return false;
        }
        CacheHelper::removeCachedUserInfo($user->getUserId());
        return DB::table(self::TABLE_USER)->where('user_id', $user->getUserId())->update(['user_pass' => $user->getUserPass()]);
    }

    /**
     * 更新用户上次使用Cookie或者正常登陆的时间，用于校验提交密码的正确性
     * @param User $user
     * @param $time
     * @return mixed
     */
    public static function updateUserLastUseSessionTimeByName(User $user, $time)
    {
        if (empty($user->getUserId())) {
            return false;
        }
        CacheHelper::removeCachedUserInfo($user->getUserId());
        return DB::table(self::TABLE_USER)->where('user_name', $user->getUserName())->update(['user_last_used_session_time' => $time]);
    }

    /**
     * 更新用户上次使用Cookie或者正常登陆的时间，用于校验提交密码的正确性
     * @param User $user
     * @return mixed
     */
    public static function getUserLastUseSessionTimeByName(User $user)
    {
        if (empty($user->getUserId())) {
            return false;
        }
        return DB::table(self::TABLE_USER)->select('user_last_used_session_time')->where('user_name', $user->getUserName())->first();
    }


    /**
     * 更新用户登录时间和IP
     * @param User $user
     * @return mixed
     */
    public static function updateUserLoginTimeAndIp(User $user)
    {
        if (empty($user->getUserId())) {
            return false;
        }
        CacheHelper::removeCachedUserInfo($user->getUserId());
        return DB::table(self::TABLE_USER)->where('user_id', $user->getUserId())->update(
            [
                'user_last_login_time' => $user->getUserLastLoginTime(),
                'user_this_login_time' => $user->getUserThisLoginTime(),
                'user_last_login_ip' => $user->getUserLastTimeLoginIP(),
                'user_this_login_ip' => $user->getUserThisTimeLoginIP(),
                'user_last_used_session_time' => $user->getLastUsedSessionTime()
            ]
        );
    }

    /**
     * 获取捐赠列表
     * @return mixed
     */
    public static function getDonateList()
    {
        return Cache::rememberForever(KeyConstant::CACHE_KEY_DONATE_INFO, function () {
            return DB::table(self::TABLE_DONATE_DATA)->get();
        });
    }


    /**
     * 添加用户
     * @param User $user
     * @return mixed
     */
    public static function insertNewUser(User $user)
    {
        if (!empty($user->getWechatOpenID())) {
            CacheHelper::removeCachedWechatOpenIdInfo($user->getWechatOpenID());
        }
        return DB::table(self::TABLE_USER)->insertGetId(
            [
                'user_name' => $user->getUserName(),
                'user_pass' => $user->getUserPass(),
                'user_right' => $user->getUserRight(),
                'user_email' => $user->getUserEmail(),
                'user_email_checked' => $user->getUserEmailChecked(),
                'user_register_time' => $user->getUserRegisterTime(),
                'user_question' => $user->getUserQuestion(),
                'user_answer' => $user->getUserAnswer(),
                'user_email_check_token' => $user->getUserEmailCheckToken(),
                'user_email_find_password_token' => $user->getUserEmailFindPasswordToken(),
                'user_email_find_password_mode' => $user->getUserEmailFindPasswordMode(),
                'user_password_reset_token' => $user->getUserPasswordResetToken(),
                'user_password_reset_token_used' => $user->getUserPasswordResetTokenUsed(),
                'user_last_login_ip' => $user->getUserLastTimeLoginIP(),
                'user_this_login_ip' => $user->getUserThisTimeLoginIP(),
                'user_last_login_time' => $user->getUserLastLoginTime(),
                'user_this_login_time' => $user->getUserThisLoginTime(),
                'user_last_used_session_time' => $user->getLastUsedSessionTime(),
                'user_donated' => $user->getUserDonated(),
                'user_wechat_openid' => empty($user->getWechatOpenID()) ? null : $user->getWechatOpenID()
            ]
        );
    }

    /**
     * 设置用户邮箱校验状态
     * @param User $user
     * @return mixed
     */
    public static function updateUserEmailCheckedState(User $user)
    {
        if (empty($user->getUserId())) {
            return false;
        }
        CacheHelper::removeCachedUserInfo($user->getUserId());
        return DB::table(self::TABLE_USER)->where('user_id', $user->getUserId())->update(
            [
                'user_email_checked' => $user->getUserEmailChecked(),
                'user_email_check_token' => $user->getUserEmailCheckToken()
            ]
        );
    }

    /**
     * 设置找回密码Token
     * @param $user
     * @return bool
     */
    public static function updateUserFindPasswordSTokenState(User $user)
    {
        if (empty($user->getUserId())) {
            return false;
        }
        CacheHelper::removeCachedUserInfo($user->getUserId());
        return DB::table(self::TABLE_USER)->where('user_id', $user->getUserId())->update(
            [
                'user_email_find_password_token' => $user->getUserEmailFindPasswordToken(),
                'user_email_find_password_mode' => $user->getUserEmailFindPasswordMode()
            ]
        );
    }

    /**
     * 设置重置密码
     * @param $user
     * @return bool
     */
    public static function updateUserResetPasswordSTokenState(User $user)
    {
        if (empty($user->getUserId())) {
            return false;
        }
        CacheHelper::removeCachedUserInfo($user->getUserId());
        return DB::table(self::TABLE_USER)->where('user_id', $user->getUserId())->update(
            [
                'user_password_reset_token' => $user->getUserPasswordResetToken(),
                'user_password_reset_token_used' => $user->getUserPasswordResetTokenUsed()
            ]
        );
    }

    /**
     * 重置密码模式的修改密码
     * @param $user
     * @return bool
     */
    public static function updateUserChangePasswordByReset(User $user)
    {
        if (empty($user->getUserId())) {
            return false;
        }
        CacheHelper::removeCachedUserInfo($user->getUserId());
        return DB::table(self::TABLE_USER)->where('user_id', $user->getUserId())->update(
            [
                'user_email_find_password_token' => $user->getUserEmailFindPasswordToken(),
                'user_email_find_password_mode' => $user->getUserEmailFindPasswordMode(),
                'user_password_reset_token' => $user->getUserPasswordResetToken(),
                'user_password_reset_token_used' => $user->getUserPasswordResetTokenUsed(),
                'user_pass' => $user->getUserPass(),
                'user_last_reset_password_time' => time()
            ]
        );
    }

    /**
     * 修改邮箱地址
     * @param $user
     * @return bool
     */
    public static function updateUserChangeEmailAddress(User $user)
    {
        if (empty($user->getUserId())) {
            return false;
        }
        CacheHelper::removeCachedUserInfo($user->getUserId());
        return DB::table(self::TABLE_USER)->where('user_id', $user->getUserId())->update(
            [
                'user_email' => $user->getUserEmail(),
                'user_email_checked' => $user->getUserEmailChecked(),
                'user_email_check_token' => $user->getUserEmailCheckToken()
            ]
        );
    }


    public static function updateUserSetDonate(User $user)
    {
        if (empty($user->getUserId())) {
            return false;
        }
        CacheHelper::removeCachedUserInfo($user->getUserId());
        return DB::table(self::TABLE_USER)->where('user_id', $user->getUserId())->update(
            [
                'user_right' => $user->getUserRight(),
                'user_donated' => $user->getUserDonated()
            ]
        );
    }

    /**
     * 封禁用户
     * @param User $user
     * @return bool
     */
    public static function banUser(User $user)
    {
        if (empty($user->getUserId())) {
            return false;
        }
        CacheHelper::removeCachedUserInfo($user->getUserId());
        if (!empty($user->getWechatOpenID())) {
            CacheHelper::removeCachedWechatOpenIdInfo($user->getWechatOpenID());
            $user->setWechatOpenID(null);
        }
        return DB::table(self::TABLE_USER)->where('user_id', $user->getUserId())->update(
            [
                'user_right' => $user->getUserRight(),
                'user_wechat_openid' => $user->getWechatOpenID()
            ]
        );
    }

    /**
     * 绑定到小程序OPENID
     * @param $user
     * @return bool
     */
    public static function updateUserBindWechatOpenId(User $user)
    {
        if (empty($user->getUserId())) {
            return false;
        }
        CacheHelper::removeCachedUserInfo($user->getUserId());
        CacheHelper::removeCachedWechatOpenIdInfo($user->getWechatOpenID());
        return DB::table(self::TABLE_USER)->where('user_id', $user->getUserId())->update(
            [
                'user_wechat_openid' => $user->getWechatOpenID(),
                'user_last_login_time' => $user->getUserLastLoginTime(),
                'user_this_login_time' => $user->getUserThisLoginTime(),
                'user_last_login_ip' => $user->getUserLastTimeLoginIP(),
                'user_this_login_ip' => $user->getUserThisTimeLoginIP(),
                'user_last_used_session_time' => $user->getLastUsedSessionTime()
            ]
        );
    }

    /**
     * 解绑WECHAT
     * @param User $user
     * @return bool
     */
    public static function updateUserUnBindWechatOpenId(User $user)
    {
        if (empty($user->getUserId())) {
            return false;
        }
        CacheHelper::removeCachedUserInfo($user->getUserId());
        CacheHelper::removeCachedWechatOpenIdInfo($user->getWechatOpenID());
        return DB::table(self::TABLE_USER)->where('user_id', $user->getUserId())->update(
            [
                'user_wechat_openid' => null
            ]
        );
    }


    /**
     * 获取Auth同步时间信息
     */
    public static function getAuthSyncTimeInfo()
    {
        return Cache::remember(KeyConstant::CACHE_KEY_AUTH_SERVER_SYNC, 1440, function () {
            return DB::table(self::TABLE_SYNCTIME_DATA)->get();
        });
    }

    /**
     * @param $userId
     * @return mixed
     * 获取用户所有的安全令数据
     */
    public static function getAllAuthOfUser($userId)
    {
        return Cache::rememberForever(KeyConstant::CACHE_KEY_USER_AUTH_LIST . $userId, function () use ($userId) {
            return DB::table(self::TABLE_AUTH_DATA)->where('user_id', $userId)->orderBy('auth_id', 'asc')->get();
        });
    }

    /**
     * 更新安全令，设置为默认
     * @param $userId
     * @param $authId
     * @param bool $needSetGivenIdDefault
     * @return
     */
    public static function updateAuthSetDefault($userId, $authId, $needSetGivenIdDefault = true)
    {
        CacheHelper::removeCachedUserAuthInfo($userId);
        $result = DB::table(self::TABLE_AUTH_DATA)
            ->where([
                ['auth_id', '<>', $authId],
                ['user_id', '=', $userId]
            ])->update(['auth_default' => 0]);
        if (!$needSetGivenIdDefault) {
            return $result;
        }
        return DB::table(self::TABLE_AUTH_DATA)
            ->where('auth_id', $authId)
            ->update(['auth_default' => 1]);
    }

    /**
     * 同步安全令服务器时间
     * @param $sync
     * @param $region
     * @param $date
     * @return
     */
    public static function updateAuthSyncServerTime($sync, $region, $date)
    {
        CacheHelper::removeCachedAuthSyncTimeInfo();
        return DB::table(self::TABLE_SYNCTIME_DATA)->where('region', strtoupper($region))
            ->update(['sync' => $sync, 'last_sync' => $date]);
    }

    /**
     * @param $authBean
     * @param $newName
     */
    public static function updateAuthSetNewName(AuthBean $authBean, $newName)
    {
        CacheHelper::removeCachedUserAuthInfo($authBean->getUserId());
        return DB::table(self::TABLE_AUTH_DATA)->where('auth_id', $authBean->getAuthId())
            ->update(['auth_name' => $newName]);
    }

    /**
     * 备份删除了的安全令，用户万一误删，可以找回
     * 其实也不是我想保存，我要这个没卵用，但是经常有邮件过来，我误删了，怎么办，我只能做个备份了
     * @param $authBean
     */
    public static function backUpDeletedAuth(AuthBean $authBean)
    {
        return DB::table(self::TABLE_DELETED_AUTH_DATA)->insert(
            [
                'auth_id' => $authBean->getAuthId(),
                'user_id' => $authBean->getUserId(),
                'auth_default' => 0,
                'auth_name' => $authBean->getAuthName(),
                'serial' => $authBean->getAuthSerial(),
                'region' => $authBean->getAuthRegion(),
                'secret' => $authBean->getAuthSecret(),
                'restore_code' => $authBean->getAuthRestoreCode(),
                'auth_img' => $authBean->getAuthImage()
            ]
        );
    }

    /**
     * 删除安全令
     * @param $authBean
     */
    public static function deleteAuth(AuthBean $authBean)
    {
        CacheHelper::removeCachedUserAuthInfo($authBean->getUserId());
        return DB::table(self::TABLE_AUTH_DATA)->where('auth_id', $authBean->getAuthId())->delete();
    }

    public static function addNewAuth(Authenticator $auth, User $user, $setDefault, AuthUtils $authUtils, $authName, $selectPic)
    {
        $newAuthId = DB::table(self::TABLE_AUTH_DATA)->insertGetId(
            [
                'user_id' => $user->getUserId(),
                'auth_default' => $setDefault ? 1 : 0,
                'auth_name' => $authName,
                'serial' => $auth->serial(),
                'region' => strtoupper($auth->region()),
                'secret' => strtolower($auth->secret()),
                'restore_code' => strtoupper($auth->restore_code()),
                'auth_img' => $selectPic
            ]
        );
        if ($authUtils->getAuthCount() == 0 || $setDefault = false) {
            CacheHelper::removeCachedUserAuthInfo($user->getUserId());
        } else {
            self::updateAuthSetDefault($user->getUserId(), $newAuthId, false);
        }
        return $newAuthId;
    }


    public static function checkHasAuthInDbByRestoreCode($authSerial, $restoreCode)
    {
        $result = DB::table(self::TABLE_AUTH_DATA)->where([
            ['serial', '=', $authSerial],
            ['restore_code', '=', strtoupper($restoreCode)]
        ])->first();
        if ($result != false) {
            return AuthBean::getAuthBeanByDBResult($result);
        }
        $result = DB::table(self::TABLE_DELETED_AUTH_DATA)->where([
            ['serial', '=', $authSerial],
            ['restore_code', '=', strtoupper($restoreCode)]
        ])->first();
        if ($result == false) {
            return $result;
        }
        return AuthBean::getAuthBeanByDBResult($result);
    }

    /**
     * 添加捐赠信息
     * @param $donateName
     * @param $donateTime
     * @param $donateCurrency
     * @param $donateCount
     */
    public static function addDonateInfo($donateName, $donateTime, $donateCurrency, $donateCount)
    {
        CacheHelper::removeCachedDonatedInfo();
        return DB::table(self::TABLE_DONATE_DATA)->insert(
            [
                'donate_name' => $donateName,
                'donate_time' => $donateTime,
                'donate_currency' => $donateCurrency,
                'donate_count' => $donateCount
            ]
        );
    }


}