<?php

/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/15 0015
 * Time: 上午 10:57
 */
class AuthImageBean
{
    public static function getAuthImages()
    {
        $imgUrl = array();
        $imgUrl[0] = "/resources/img/bga.png";
        $imgUrl[1] = "/resources/img/wow-32.png";
        $imgUrl[2] = "/resources/img/s2-32.png";
        $imgUrl[3] = "/resources/img/d3-32.png";
        $imgUrl[4] = "/resources/img/pegasus-32.png";
        $imgUrl[5] = "/resources/img/heroes-32.png";
        $imgUrl[6] = "/resources/img/overwatch-32.png";
        return $imgUrl;
    }
}