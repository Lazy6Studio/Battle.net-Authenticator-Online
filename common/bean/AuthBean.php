<?php

/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/15 0015
 * Time: 上午 8:56
 */
class AuthBean
{
    private $authId;
    private $userId;
    private $authDefault;
    private $authName;
    private $authSerial;
    private $authRegion;
    private $authSecret;
    private $authRestoreCode;
    private $authImage;

    /**
     * @param mixed $authId
     */
    public function setAuthId($authId)
    {
        $this->authId = $authId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @param mixed $authDefault
     */
    public function setAuthDefault($authDefault)
    {
        $this->authDefault = $authDefault;
    }

    /**
     * @param mixed $authName
     */
    public function setAuthName($authName)
    {
        $this->authName = $authName;
    }

    /**
     * @param mixed $authSerial
     */
    public function setAuthSerial($authSerial)
    {
        $this->authSerial = $authSerial;
    }

    /**
     * @param mixed $authRegion
     */
    public function setAuthRegion($authRegion)
    {
        $this->authRegion = $authRegion;
    }

    /**
     * @param mixed $authSecret
     */
    public function setAuthSecret($authSecret)
    {
        $this->authSecret = $authSecret;
    }

    /**
     * @param mixed $authRestoreCode
     */
    public function setAuthRestoreCode($authRestoreCode)
    {
        $this->authRestoreCode = $authRestoreCode;
    }

    /**
     * @param mixed $authImage
     */
    public function setAuthImage($authImage)
    {
        $this->authImage = $authImage;
    }

    /**
     * @return mixed
     */
    public function getAuthId()
    {
        return $this->authId;
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
    public function getAuthDefault()
    {
        return $this->authDefault;
    }

    /**
     * @return mixed
     */
    public function getAuthName()
    {
        return $this->authName;
    }

    /**
     * @return mixed
     */
    public function getAuthSerial()
    {
        return $this->authSerial;
    }

    /**
     * @return mixed
     */
    public function getAuthRegion()
    {
        return $this->authRegion;
    }

    /**
     * @return mixed
     */
    public function getAuthSecret()
    {
        return $this->authSecret;
    }

    /**
     * @return mixed
     */
    public function getAuthRestoreCode()
    {
        return $this->authRestoreCode;
    }

    /**
     * @return mixed
     */
    public function getAuthImage()
    {
        return $this->authImage;
    }

    public static function getAuthBeanByDBResult($auth)
    {
        $authBean = new AuthBean();
        $authBean->setAuthId($auth->auth_id);
        $authBean->setUserId($auth->user_id);
        $authBean->setAuthDefault($auth->auth_default == 1);
        $authBean->setAuthName($auth->auth_name);
        $authBean->setAuthSerial($auth->serial);
        $authBean->setAuthRegion($auth->region);
        $authBean->setAuthSecret($auth->secret);
        $authBean->setAuthRestoreCode($auth->restore_code);
        $authBean->setAuthImage($auth->auth_img);
        return $authBean;
    }

}