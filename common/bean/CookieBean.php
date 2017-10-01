<?php
/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/15 0015
 * Time: 上午 8:42
 */
class CookieBean
{
    private $userName;
    private $userId;
    private $userCookie;
    private $expireTimeStamp;
    private $loginIp;
    private $createTimeStamp;
    private $isCookieValid;

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
    public function getCreateTimeStamp()
    {
        return $this->createTimeStamp;
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
    public function getUserCookie()
    {
        return $this->userCookie;
    }

    /**
     * @return mixed
     */
    public function getExpireTimeStamp()
    {
        return $this->expireTimeStamp;
    }

    /**
     * @return mixed
     */
    public function getLoginIp()
    {
        return $this->loginIp;
    }

    /**
     * @return mixed
     */
    public function getisCookieValid()
    {
        return $this->isCookieValid;
    }

    /**
     * 从传来的value和jsontext解析,判断有效期，写入是否是有效ID
     * @param CookieValue $cookieValue
     * @param $savedJsonText
     */
    public function getCookieBeanByJson(CookieValue $cookieValue, $savedJsonText)
    {
        $json = json_decode($savedJsonText, true);
        if ($json['userCookie'] != $cookieValue->cookieToken) {
            return;
        }
        if (empty($json['userId']) || !Functions::isInt($json['userId'])) {
            return;
        }
        $this->userName = $json['userName'];
        $this->userId = $json['userId'];
        $this->userCookie = $json['userCookie'];
        $this->expireTimeStamp = $json['expireTimeStamp'];
        $this->loginIp = $json['loginIp'];
        $this->createTimeStamp = $json['createTimeStamp'];
        if (time() > $this->expireTimeStamp) {
            return;
        }
        $this->isCookieValid = true;
    }
}