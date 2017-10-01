<?php

/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/12 0012
 * Time: 下午 16:31
 */
class HttpFormConstant
{

    public static function getRegisterQuestionArray()
    {
        $registerQuestion[81] = "您出生的城市是哪里?";
        $registerQuestion[82] = "您手机的型号是什么?";
        $registerQuestion[83] = "您就读的第一所小学名称是?";
        $registerQuestion[84] = "您的初恋情人叫什么名字?";
        $registerQuestion[85] = "您驾照的末四位是什么?";
        $registerQuestion[86] = "您母亲的姓名叫什么?";
        $registerQuestion[87] = "您母亲的生日是哪一天?";
        $registerQuestion[88] = "您父亲的生日是哪一天?";
        return $registerQuestion;
    }

    const FORM_KEY_AUTH_ID = "authId";
}