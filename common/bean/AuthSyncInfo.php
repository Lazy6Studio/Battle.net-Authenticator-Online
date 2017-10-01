<?php

/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/15 0015
 * Time: 上午 9:00
 */
class AuthSyncInfo
{
    private $syncList = array();
    const SYNC_SERVER_TIME = 'syncServerTime';
    const LAST_SYNC_TIME = 'lastSyncTime';

    /**
     * @return array
     */
    public function getSyncList()
    {
        return $this->syncList;
    }

    function __construct()
    {
        $syncInfos = DBHelper::getAuthSyncTimeInfo();
        if ($syncInfos === false) {
            return;
        }
        foreach ($syncInfos as $syncInfo) {
            $this->syncList[strtoupper($syncInfo->region)][self::SYNC_SERVER_TIME] = $syncInfo->sync;
            $this->syncList[strtoupper($syncInfo->region)][self::LAST_SYNC_TIME] = $syncInfo->last_sync;
        }
    }
}