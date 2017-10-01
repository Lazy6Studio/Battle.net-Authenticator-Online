<?php
/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/12 0012
 * Time: 下午 22:00
 */


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ResendVerifyEmailUtils;

class ResendVerifyEmailController extends Controller
{
    public function __construct()
    {
        $this->middleware('base.check');
    }

    public function reSendEmail(Request $request)
    {
        $user = Auth::user();
        try {
            $resendVerifyEmail = new ResendVerifyEmailUtils($user);
            return response($resendVerifyEmail->getResendEmailErrorCode());
        } catch (\Exception $e) {
            return ResendVerifyEmailUtils::MAIL_SEND_ERROR;
        }
    }
}