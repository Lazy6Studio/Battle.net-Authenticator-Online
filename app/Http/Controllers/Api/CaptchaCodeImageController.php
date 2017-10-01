<?php
/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/12 0012
 * Time: 下午 21:37
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use Illuminate\Support\Facades\Session;
use KeyConstant;


class CaptchaCodeImageController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function captcha()
    {
        $phrase = new PhraseBuilder();
        $possibleLetters = '23456789bcdfghjkmnpqrstvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
        $code = $phrase->build(6,$possibleLetters);
        $builder = new CaptchaBuilder($code, $phrase);
        $builder->setMaxAngle(15);
        $builder->setMaxBehindLines(2);
        $builder->setMaxFrontLines(2);
        $builder->buildAgainstOCR(200, 70);
        //获取验证码的内容
        $phrase = $builder->getPhrase();

        //把内容存入session
        Session::put(KeyConstant::SESSION_CAPTCHA, $phrase);
        //生成图片
        header("Cache-Control: no-cache, must-revalidate");
        header('Content-Type: image/jpeg');
        $builder->output(100);
    }
}