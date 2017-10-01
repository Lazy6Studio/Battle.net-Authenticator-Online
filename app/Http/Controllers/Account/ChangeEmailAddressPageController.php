<?php
/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/11 0011
 * Time: 下午 12:11
 */

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use ChangeEmailAddressUtils;
use ChangePasswordUtils;
use KeyConstant;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use RegisterCheckUtils;


class ChangeEmailAddressPageController extends Controller
{


    public function __construct()
    {
        $this->middleware('base.check');
    }

    function get(Request $request)
    {
        $user = Auth::user();
        if (!$user->getIsLogin()) {
            return view('account.changeMailAddress.needLogin')->with('_USER', $user)->with("topNavValueText", "修改邮箱");
        }
        if ($user->getUserRight() == User::USER_SHARED) {
            return view('account.changeMailAddress.lowRight')->with('_USER', $user)->with("topNavValueText", "修改邮箱");
        }
        return view('account.changeMailAddress.index')->with('_USER', $user)->with("topNavValueText", "修改邮箱")
            ->with("errorString", "");
    }

    function post(Request $request)
    {
        $user = Auth::user();
        if (!$user->getIsLogin()) {
            return view('account.changeMailAddress.needLogin')->with('_USER', $user)->with("topNavValueText", "修改邮箱");
        }
        if ($user->getUserRight() == User::USER_SHARED) {
            return view('account.changeMailAddress.lowRight')->with('_USER', $user)->with("topNavValueText", "修改邮箱");
        }
        $changeMailAddressUtils = new ChangeEmailAddressUtils($request);
        $changeMailAddressUtils->doCheck($request, $user);
        if ($changeMailAddressUtils->getChangeEmilAddressErrorCode() == 0) {
            return view('account.changeMailAddress.success')->with('_USER', $user)->with("topNavValueText", "修改邮箱");
        }
        return view('account.changeMailAddress.index')->with('_USER', $user)->with("topNavValueText", "修改邮箱")
            ->with("errorString", $changeMailAddressUtils->getErrorString($changeMailAddressUtils->getChangeEmilAddressErrorCode()));
    }
}