<?php
use Illuminate\Support\Facades\Session;

/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/10 0010
 * Time: 下午 16:58
 */
class CaptchaUtils
{
    public function isCaptchaCodeValid($captcha)
    {

        $result = !empty($captcha) && strtoupper(Session::get(KeyConstant::SESSION_CAPTCHA)) === strtoupper($captcha);
        Session::put(KeyConstant::SESSION_CAPTCHA, Functions::getRandomString());
        return $result;
    }
}