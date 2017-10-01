<?php

namespace App;

use Functions;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DBHelper;
use RedisHelper;

class User extends Authenticatable
{
    const TAG = "_USER";
    const USER_BANED = 999;
    const USER_SHARED = 1;
    const USER_NORMAL = 0;

    private $isLogin = false;

    private $wechatTokenBean;

    private $userId;
    private $userName;
    private $userPass;
    private $userRight;
    private $userEmail;
    private $userEmailChecked;
    private $userRegisterTime;
    private $userQuestion;
    private $userAnswer;
    private $userEmailCheckToken;
    private $userEmailFindPasswordToken;
    private $userEmailFindPasswordMode;
    private $userPasswordResetToken;
    private $userPasswordResetTokenUsed;
    private $userLastTimeLoginIP;
    private $userThisTimeLoginIP;
    private $userLastLoginTime;
    private $userThisLoginTime;
    private $lastUsedSessionTime;
    private $userDonated;
    private $lastResetPasswordTime;

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @param mixed $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * @param mixed $userPass
     */
    public function setUserPass($userPass)
    {
        $this->userPass = $userPass;
    }

    /**
     * @param mixed $userRight
     */
    public function setUserRight($userRight)
    {
        $this->userRight = $userRight;
    }

    /**
     * @param mixed $userEmail
     */
    public function setUserEmail($userEmail)
    {
        $this->userEmail = $userEmail;
    }

    /**
     * @param mixed $userEmailChecked
     */
    public function setUserEmailChecked($userEmailChecked)
    {
        $this->userEmailChecked = $userEmailChecked;
    }

    /**
     * @param mixed $userRegisterTime
     */
    public function setUserRegisterTime($userRegisterTime)
    {
        $this->userRegisterTime = $userRegisterTime;
    }

    /**
     * @param mixed $userQuestion
     */
    public function setUserQuestion($userQuestion)
    {
        $this->userQuestion = $userQuestion;
    }

    /**
     * @param mixed $userAnswer
     */
    public function setUserAnswer($userAnswer)
    {
        $this->userAnswer = $userAnswer;
    }

    /**
     * @param $userEmailCheckToken
     */
    public function setUserEmailCheckToken($userEmailCheckToken)
    {
        $this->userEmailCheckToken = $userEmailCheckToken;
    }

    /**
     * @param $userEmailFindPasswordToken
     */
    public function setUserEmailFindPasswordToken($userEmailFindPasswordToken)
    {
        $this->userEmailFindPasswordToken = $userEmailFindPasswordToken;
    }

    /**
     * @param $userEmailFindPasswordMode
     */
    public function setUserEmailFindPasswordMode($userEmailFindPasswordMode)
    {
        $this->userEmailFindPasswordMode = $userEmailFindPasswordMode;
    }

    /**
     * @param mixed $userPasswordResetToken
     */
    public function setUserPasswordResetToken($userPasswordResetToken)
    {
        $this->userPasswordResetToken = $userPasswordResetToken;
    }

    /**
     * @param mixed $userPasswordResetTokenUsed
     */
    public function setUserPasswordResetTokenUsed($userPasswordResetTokenUsed)
    {
        $this->userPasswordResetTokenUsed = $userPasswordResetTokenUsed;
    }

    /**
     * @param mixed $userDonated
     */
    public function setUserDonated($userDonated)
    {
        $this->userDonated = $userDonated;
    }

    /**
     * @param mixed $wechatOpenID
     */
    public function setWechatOpenID($wechatOpenID)
    {
        $this->wechatOpenID = $wechatOpenID;
    }

    private $wechatOpenID;

    /**
     * @param mixed $userLastTimeLoginIP
     */
    public function setUserLastTimeLoginIP($userLastTimeLoginIP)
    {
        $this->userLastTimeLoginIP = $userLastTimeLoginIP;
    }

    /**
     * @param mixed $userThisTimeLoginIP
     */
    public function setUserThisTimeLoginIP($userThisTimeLoginIP)
    {
        $this->userThisTimeLoginIP = $userThisTimeLoginIP;
    }

    /**
     * @param mixed $userLastLoginTime
     */
    public function setUserLastLoginTime($userLastLoginTime)
    {
        $this->userLastLoginTime = $userLastLoginTime;
    }

    /**
     * @param mixed $userThisLoginTime
     */
    public function setUserThisLoginTime($userThisLoginTime)
    {
        $this->userThisLoginTime = $userThisLoginTime;
    }

    /**
     * @param mixed $lastUsedSessionTime
     */
    public function setLastUsedSessionTime($lastUsedSessionTime)
    {
        $this->lastUsedSessionTime = $lastUsedSessionTime;
    }

    /**
     * @param bool $isLogin
     */
    public function setIsLogin($isLogin)
    {
        $this->isLogin = $isLogin;
    }


    public function initUserByNameAndPassword($userName, $userPass)
    {
        if (empty($userName) || empty($userPass)) {
            return;
        }
        $user = DBHelper::getUserByNameAndPassword($userName, $userPass);
        self::writeUser($user);
    }

    public function initUserByUserNameAndUserId($userName, $userId)
    {
        if (empty($userName) || empty($userId) || !Functions::isInt($userId)) {
            return;
        }
        $user = DBHelper::getUserByUserNameAndUserId($userName, $userId);
        self::writeUser($user);
    }


    public function initUserByUserId($userId)
    {
        if (empty($userId) || !Functions::isInt($userId)) {
            return;
        }
        $user = DBHelper::getUserByUserId($userId);
        self::writeUser($user);
    }


    public function initUserByWechatOpenId($openId)
    {
        if(empty($openId)){
            return;
        }
        $user = DBHelper::getUserByWechatOpenId($openId);
        self::writeUser($user);
    }

    public function initUserByUserName($userName)
    {
        if (empty($userName)) {
            return;
        }
        $user = DBHelper::getUserByUserName($userName);
        self::writeUser($user);
    }


    private function writeUser($user)
    {
        if (empty($user->user_id)) {
            return;
        }
        $this->userId = $user->user_id;
        $this->userName = $user->user_name;
        $this->userPass = $user->user_pass;
        $this->userRight = $user->user_right;
        $this->userEmail = $user->user_email;
        $this->userEmailChecked = $user->user_email_checked;
        $this->userRegisterTime = $user->user_register_time;
        $this->userQuestion = $user->user_question;
        $this->userAnswer = $user->user_answer;
        $this->userEmailCheckToken = $user->user_email_check_token;
        $this->userEmailFindPasswordToken = $user->user_email_find_password_token;
        $this->userEmailFindPasswordMode = $user->user_email_find_password_mode;
        $this->userPasswordResetToken = $user->user_password_reset_token;
        $this->userPasswordResetTokenUsed = $user->user_password_reset_token_used;
        $this->userLastTimeLoginIP = $user->user_last_login_ip;
        $this->userThisTimeLoginIP = $user->user_this_login_ip;
        $this->userLastLoginTime = $user->user_last_login_time;
        $this->userThisLoginTime = $user->user_this_login_time;
        $this->lastUsedSessionTime = $user->user_last_used_session_time;
        $this->userDonated = $user->user_donated;
        $this->wechatOpenID = $user->user_wechat_openid;
        $this->lastResetPasswordTime = $user->user_last_reset_password_time;
    }

    /**
     * @return mixed
     */
    public function getLastResetPasswordTime()
    {
        return $this->lastResetPasswordTime;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @return mixed
     */
    public function getUserPass()
    {
        return $this->userPass;
    }

    /**
     * @return mixed
     */
    public function getUserRight()
    {
        return $this->userRight;
    }

    /**
     * @return mixed
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }

    /**
     * @return mixed
     */
    public function getUserEmailChecked()
    {
        return $this->userEmailChecked;
    }

    /**
     * @return mixed
     */
    public function getUserRegisterTime()
    {
        return $this->userRegisterTime;
    }

    /**
     * @return mixed
     */
    public function getUserQuestion()
    {
        return $this->userQuestion;
    }

    /**
     * @return mixed
     */
    public function getUserAnswer()
    {
        return $this->userAnswer;
    }

    /**
     * @return mixed
     */
    public function getUserEmailCheckToken()
    {
        return $this->userEmailCheckToken;
    }

    /**
     * @return mixed
     */
    public function getUserEmailFindPasswordToken()
    {
        return $this->userEmailFindPasswordToken;
    }

    /**
     * @return mixed
     */
    public function getUserEmailFindPasswordMode()
    {
        return $this->userEmailFindPasswordMode;
    }

    /**
     * @return mixed
     */
    public function getUserPasswordResetToken()
    {
        return $this->userPasswordResetToken;
    }

    /**
     * @return mixed
     */
    public function getUserPasswordResetTokenUsed()
    {
        return $this->userPasswordResetTokenUsed;
    }

    /**
     * @return mixed
     */
    public function getUserLastTimeLoginIP()
    {
        return $this->userLastTimeLoginIP;
    }

    /**
     * @return mixed
     */
    public function getUserThisTimeLoginIP()
    {
        return $this->userThisTimeLoginIP;
    }

    /**
     * @return mixed
     */
    public function getUserLastLoginTime()
    {
        return $this->userLastLoginTime;
    }

    /**
     * @return mixed
     */
    public function getUserThisLoginTime()
    {
        return $this->userThisLoginTime;
    }

    /**
     * @return mixed
     */
    public function getLastUsedSessionTime()
    {
        return $this->lastUsedSessionTime;
    }

    /**
     * @return mixed
     */
    public function getUserDonated()
    {
        return $this->userDonated;
    }

    /**
     * @return mixed
     */
    public function getWechatOpenID()
    {
        return $this->wechatOpenID;
    }


    /**
     * @return mixed
     */
    public function getIsLogin()
    {
        return $this->isLogin;
    }

    /**
     * @return mixed
     */
    public function getWechatTokenBean()
    {
        return $this->wechatTokenBean;
    }

    /**
     * @param mixed $wechatTokenBean
     */
    public function setWechatTokenBean($wechatTokenBean)
    {
        $this->wechatTokenBean = $wechatTokenBean;
    }


}
