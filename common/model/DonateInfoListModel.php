<?php

/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/12 0012
 * Time: 下午 16:13
 */
class DonateInfoListModel
{
    public static function initDonateInfo()
    {
        $donateInfoList = array();
        $donateList = DBHelper::getDonateList();
        foreach ($donateList as $donate) {
            $donateInfoList[] = DonateInfo::getDonateInfo($donate);
        }
        return $donateInfoList;
    }
}


class DonateInfo
{
    private $donateName;
    private $donateTime;
    private $donateBiZhong;

    /**
     * @return mixed
     */
    public function getDonateName()
    {
        return $this->donateName;
    }

    /**
     * @return mixed
     */
    public function getDonateTime()
    {
        return $this->donateTime;
    }

    /**
     * @return mixed
     */
    public function getDonateBiZhong()
    {
        return $this->donateBiZhong;
    }

    /**
     * @return mixed
     */
    public function getDonateCount()
    {
        return $this->donateCount;
    }

    private $donateCount;

    public static function getDonateInfo($donate)
    {
        $donateInfo = new DonateInfo();
        $donateInfo->donateName = $donate->donate_name;
        $donateInfo->donateTime = $donate->donate_time;
        $donateInfo->donateBiZhong = $donate->donate_currency;
        $donateInfo->donateCount = $donate->donate_count;
        return $donateInfo;
    }
}