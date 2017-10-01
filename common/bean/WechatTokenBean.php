<?php

/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/21 0021
 * Time: 上午 9:44
 */
class WechatTokenBean
{
    private $wechatTokenSessionKey;
    private $wechatTokenOpenId;
    private $isTokenValid;
    private $tokenKey;

    function __construct($key)
    {
        $this->tokenKey = $key;
    }

    /**
     * @return mixed
     */
    public function getWechatTokenSessionKey()
    {
        return $this->wechatTokenSessionKey;
    }

    /**
     * @return mixed
     */
    public function getWechatTokenOpenId()
    {
        return $this->wechatTokenOpenId;
    }

    /**
     * @return mixed
     */
    public function getIsTokenValid()
    {
        return $this->isTokenValid;
    }

    public function initWechatToken($tokenValue)
    {
        if (empty($tokenValue)) {
            return;
        }
        $json = json_decode($tokenValue,true);
        $this->wechatTokenSessionKey = $json['sessionKey'];
        $this->wechatTokenOpenId = $json['openId'];
        if (!empty($this->wechatTokenOpenId) && !empty($this->wechatTokenSessionKey)) {
            $this->isTokenValid = true;
        }
    }


    public static function getWechatTokenJson($sessionKey, $openId)
    {
        $json['sessionKey'] = $sessionKey;
        $json['openId'] = $openId;
        return json_encode($json);
    }

    /**
     * @return mixed
     */
    public function getTokenKey()
    {
        return $this->tokenKey;
    }
}