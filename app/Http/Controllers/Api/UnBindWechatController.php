<?php
/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/12 0012
 * Time: 下午 22:00
 */


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use DBHelper;
use Functions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use KeyConstant;

class UnBindWechatController extends Controller
{

    public function __construct()
    {
        $this->middleware('base.check');
    }

    public function get(Request $request)
    {
        $user = Auth::user();
        if (!$user->getIsLogin()) {
            return response("false");
        }
        $result = DBHelper::updateUserUnBindWechatOpenId($user);

        if ($result == false) {
            return response("false");
        }
        return response("true");
    }
}