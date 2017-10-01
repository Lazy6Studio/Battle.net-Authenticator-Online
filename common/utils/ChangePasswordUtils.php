<?php
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/14 0014
 * Time: 上午 10:31
 */
class ChangePasswordUtils
{
    private $changePasswordErrorCode = -999;

    /**
     * @return int
     */
    public function getChangePasswordErrorCode()
    {
        return $this->changePasswordErrorCode;
    }

    private $captchaCode;
    private $oldPassword;
    private $newPassword;
    private $newPasswordVerify;

    function __construct(Request $request)
    {
        $this->changePasswordErrorCode = -999;
        $this->captchaCode = $request->input("letters_code");
        $this->oldPassword = $request->input("oldPassword");
        $this->newPassword = $request->input("newPassword");
        $this->newPasswordVerify = $request->input("newPasswordVerify");
    }

    public function doChange(Request $request, User $user)
    {
        $captchaUtils = new CaptchaUtils();
        if (!$captchaUtils->isCaptchaCodeValid($this->captchaCode)) {
            $this->changePasswordErrorCode = 1;
            return;
        }
        if (empty($this->oldPassword) || empty($this->newPassword) || empty($this->newPasswordVerify)) {
            $this->changePasswordErrorCode = 2;
            return;
        }
        if (Functions::checkPostUserNameAndPasswordHasUser($user, $this->oldPassword) === false) {
            $this->changePasswordErrorCode = 3;
            return;
        }
        if ($this->newPassword != $this->newPasswordVerify) {
            $this->changePasswordErrorCode = 4;
            return;
        }
        $this->newPassword = Functions::getUnencryptPassword($this->newPassword);
        if (strlen($this->newPassword) < 8 || strlen($this->newPassword) > 16) {
            $this->changePasswordErrorCode = 5;
            return;
        }
        $user->setUserPass(md5($this->newPassword));
        $result = DBHelper::updateUserPassword($user);
        if ($result === false) {
            $this->changePasswordErrorCode = 99;
            return;
        }
        Auth::setuser($user);
        MailSendUtils::sendChangePasswordSuccessEmail($user, $this->newPassword);
        $this->changePasswordErrorCode = 0;
    }

    public static function getErrorString($errorId)
    {
        switch ($errorId) {
            case 0:
                //正确
                return "";
                break;
            case 1:
                return "验证码输入错误。";
                break;
            case 2:
                return "内容输入错误。";
                break;
            case 3:
                return "旧密码输入错误。";
                break;
            case 4:
                return "两次输入的密码不一致。";
                break;
            case 5:
                return "密码位数错误。";
                break;
            default:
                return "修改密码失败，未知错误。";
                break;
        }
    }
}