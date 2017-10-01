<?php
/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/12 0012
 * Time: 下午 22:00
 */


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use DBHelper;
use Functions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use KeyConstant;

class CheckController extends Controller
{

    /**
     * 校验用户是否存在
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function checkUserName(Request $request)
    {
        $name = $request->get("name");
        if (empty($name)) {
            return response("");
        }
        if (!Functions::isUsernameValid($name)) {
            return response("illegal");
        }
        if (DBHelper::getUserNameCount($name) > 0) {
            return response("false");
        }
        return response("true");
    }

    public function checkCpatcha(Request $request)
    {
        $code = $request->get("code");
        if (empty($code)) {
            return response("false");
        }
        $sessionCode = Session::get(KeyConstant::SESSION_CAPTCHA);
        return response(strtoupper($code) === strtoupper($sessionCode) ? "true" : "false");
    }
}