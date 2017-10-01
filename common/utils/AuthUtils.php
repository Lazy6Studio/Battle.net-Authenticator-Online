<?php
use App\User;

/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/15 0015
 * Time: 上午 8:41
 */
class AuthUtils
{

    private $authSyncInfo;
    private $authList = array();
    private $defaultAuth;

    private $hasAuth;
    private $authCount = 0;
    private $authImageUrls;


    function __construct()
    {
        $this->authSyncInfo = new AuthSyncInfo();
        $this->authImageUrls = AuthImageBean::getAuthImages();
    }

    /**
     * @return array
     */
    public function getAuthImageUrls()
    {
        return $this->authImageUrls;
    }

    /**
     * @return int
     */
    public function getAuthCount()
    {
        return $this->authCount;
    }

    /**
     * @return AuthSyncInfo
     */
    public function getAuthSyncInfo()
    {
        return $this->authSyncInfo;
    }

    /**
     * @return array
     */
    public function getAuthList()
    {
        return $this->authList;
    }

    /**
     * @return mixed
     */
    public function getDefaultAuth()
    {
        return $this->defaultAuth;
    }

    /**
     * @return mixed
     */
    public function getHasAuth()
    {
        return $this->hasAuth;
    }

    private function doSort($authListFromDb)
    {
        if ($authListFromDb[0]->auth_default == 1) {
            return $authListFromDb;
        }
        if (count($authListFromDb) == 1) {
            $authListFromDb[0]->auth_default = 1;
            return $authListFromDb;
        }
        $defaultPosition = 0;
        for ($i = 0; $i < count($authListFromDb); $i++) {
            if ($authListFromDb[$i]->auth_default == 1) {
                $defaultPosition = $i;
                break;
            }
        }
        $authTemp = $authListFromDb[$defaultPosition];
        for ($i = $defaultPosition; $i > 0; $i--) {
            $authListFromDb[$i] = $authListFromDb[$i - 1];
        }
        $authListFromDb[0] = $authTemp;
        return $authListFromDb;
    }

    public function getAllAuth(User $user, $defaultFirst = true)
    {
        $authListFromDb = DBHelper::getAllAuthOfUser($user->getUserId());
        if ($authListFromDb == false || count($authListFromDb) < 1) {
            $this->hasAuth = false;
            $this->authCount = 0;
            return;
        }
        $this->hasAuth = true;
        $hasFindDefault = false;
        if ($defaultFirst) {
            $authListFromDb = self::doSort($authListFromDb);
        }
        foreach ($authListFromDb as $auth) {
            $authBean = AuthBean::getAuthBeanByDBResult($auth);
            if ($authBean->getAuthDefault()) {
                $hasFindDefault = true;
                $this->defaultAuth = $authBean;
            }
            if ($authBean->getAuthImage() < 0 || $authBean->getAuthImage() >= count($this->authImageUrls)) {
                $authBean->setAuthImage(0);
            }
            $this->authList[] = $authBean;
        }
        if (!$hasFindDefault) {
            $authId = $this->authList[0]->getAuthId();
            DBHelper::updateAuthSetDefault($user->getUserId(), $authId);
            $this->authList[0]->setAuthDefault(true);
            $this->defaultAuth = $this->authList[0];
        }
        $this->authCount = count($this->authList);
    }

    public function getLastSyncTimeString(AuthBean $authBean)
    {
        return date("Y年m月d日H时i分s秒", strtotime($this->authSyncInfo->getSyncList()[strtoupper($authBean->getAuthRegion())][AuthSyncInfo::LAST_SYNC_TIME]));
    }
}



