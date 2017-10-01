<?php
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/15 0015
 * Time: 上午 11:24
 */
class ResendVerifyEmailUtils
{

    private $resendEmailErrorCode = -999;

    const MAIL_SEND_ERROR = 4;

    /**
     * @return int
     */
    public function getResendEmailErrorCode()
    {
        return $this->resendEmailErrorCode;
    }

    function __construct(User $user)
    {
        if (@$user == null || !$user->getIsLogin()) {
            $this->resendEmailErrorCode = 1;
            return;
        }
        if ($user->getUserEmailChecked() == 1) {
            $this->resendEmailErrorCode = 2;
            return;
        }
        $lastSendMailTime = Session::get(KeyConstant::SESSION_LAST_SEND_MAIL);
        if (!empty($lastSendMailTime) && Functions::isInt($lastSendMailTime) && (time() - $lastSendMailTime) < 60) {
            $this->resendEmailErrorCode = 3;
            return;
        }
        MailSendUtils::resendVerifyEmail($user);
        Session::put(KeyConstant::SESSION_LAST_SEND_MAIL, time());
        $this->resendEmailErrorCode = 0;
    }

    public function getErrorInfo($errorCode)
    {
        switch ($errorCode) {
            case 1:
                return "未登录";
                break;
            case 2:
                return "已经确认了";
                break;
            case 3:
                return "发送间隔太短了";
                break;
        }
    }
}