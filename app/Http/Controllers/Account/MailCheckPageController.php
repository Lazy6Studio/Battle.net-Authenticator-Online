<?php
/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/11 0011
 * Time: 下午 12:11
 */

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use ChangePasswordUtils;
use KeyConstant;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use MailCheckUtils;
use RegisterCheckUtils;


class MailCheckPageController extends Controller
{


    public function __construct()
    {
        $this->middleware('base.check');
    }

    function doCheck(Request $request)
    {
        $user = Auth::user();
        $mailCheckUtils = new MailCheckUtils($request);
        $mailCheckUtils->doCheck($request, $user);
        if ($user->getIsLogin()) {
            return view('account.mailCheck.index')->with("_USER", $user)->with("topNavValueText", "邮件地址确认")
                ->with("checkResult", $mailCheckUtils->getErrorString($mailCheckUtils->getMailCheckErrorCode()) . "，即将转到主页")
                ->with('jumpUrl', config('app.url'));
        }
        switch ($mailCheckUtils->getMailCheckErrorCode()){
            case 0:
            case 2:
            return view('account.mailCheck.index')->with("_USER", $user)->with("topNavValueText", "邮件地址确认")
                ->with("checkResult", $mailCheckUtils->getErrorString($mailCheckUtils->getMailCheckErrorCode()) . "，即将转到登入页面")
                ->with('jumpUrl', config('app.url') . "login");
                break;
            default:
                return view('account.mailCheck.index')->with("_USER", $user)->with("topNavValueText", "邮件地址确认")
                    ->with("checkResult", $mailCheckUtils->getErrorString($mailCheckUtils->getMailCheckErrorCode()) . "，即将转到主页")
                    ->with('jumpUrl', config('app.url'));
                break;
        }
    }
}