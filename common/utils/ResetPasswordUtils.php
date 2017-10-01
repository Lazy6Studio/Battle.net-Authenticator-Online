<?php
use App\User;
use Illuminate\Http\Request;

/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/14 0014
 * Time: 下午 16:20
 */
class ResetPasswordUtils
{
    private $resetPasswordErrorCode;
    private $userId;
    private $token;
    private $emailAddress;
    private $newPassword;
    private $newPasswordVerify;
    private $user;

    /**
     * @return int
     */
    public function getResetPasswordErrorCode()
    {
        return $this->resetPasswordErrorCode;
    }

    /**
     * @return array|string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return array|string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    function __construct(Request $request)
    {
        $this->resetPasswordErrorCode = -999;
        $this->userId = $request->input('userId');
        $this->token = $request->input('token');
        $this->emailAddress = $request->input("oldPassword", "");
        $this->newPassword = $request->input("newPassword", "");
        $this->newPasswordVerify = $request->input("newPasswordVerify", "");
    }

    function doGetCheck()
    {
        if (empty($this->userId) || empty($this->token) || !Functions::isInt($this->userId) || !Functions::isFindPasswordTokenValid($this->token)) {
            $this->resetPasswordErrorCode = 1;
            return;
        }
        $user = new User();
        $user->initUserByUserId($this->userId);
        if (empty($user->getUserName())) {
            $this->resetPasswordErrorCode = 1;
            return;
        }
        if ($user->getUserRight() == User::USER_BANED) {
            $this->resetPasswordErrorCode = 2;
            return;
        }
        $this->user = $user;
        if ($this->token == $user->getUserPasswordResetToken() && $user->getUserPasswordResetTokenUsed() == 0) {
            $this->resetPasswordErrorCode = 0;
        } else {
            $this->resetPasswordErrorCode = 3;
        }
    }

    function doPostCheck(Request $request)
    {
        if (empty($this->userId) || empty($this->token) || !Functions::isInt($this->userId) || !Functions::isFindPasswordTokenValid($this->token) ||
            empty($this->emailAddress) || empty($this->newPassword) || empty($this->newPasswordVerify)
        ) {
            $this->resetPasswordErrorCode = 4;
            return;
        }
        if (!Functions::isEmailValid($this->emailAddress)) {
            $this->resetPasswordErrorCode = 5;
            return;
        }
        if ($this->newPassword !== $this->newPasswordVerify) {
            $this->resetPasswordErrorCode = 6;
            return;
        }
        $user = new User();
        $user->initUserByUserId($this->userId);
        if (empty($user->getUserId()) || !Functions::isInt($user->getUserId()) ||
            empty($user->getUserName())
        ) {
            $this->resetPasswordErrorCode = 8;
            return;
        }
        if ($this->emailAddress != $user->getUserEmail()) {
            $this->resetPasswordErrorCode = 5;
            return;
        }
        if ($user->getUserRight() == User::USER_BANED) {
            $user->setUserPasswordResetToken(Functions::getRandomString());
            $user->setUserPasswordResetTokenUsed(1);
            $user->setUserEmailFindPasswordToken(Functions::getRandomString());
            $user->setUserEmailFindPasswordMode(0);
            DBHelper::updateUserChangePasswordByReset($user);
            $this->resetPasswordErrorCode = 2;
            return;
        }
        $this->user = $user;
        if ($this->user->getUserPasswordResetTokenUsed() != 0) {
            $this->resetPasswordErrorCode = 9;
            return;
        }
        if ($this->token != $this->user->getUserPasswordResetToken()) {
            $this->resetPasswordErrorCode = 4;
            return;
        }
        $this->newPassword = Functions::getUnencryptPassword($this->newPasswordVerify);
        if (strlen($this->newPassword) < 8 || strlen($this->newPassword) > 16) {
            $this->resetPasswordErrorCode = 7;
            return;
        }
        $this->user->setUserPass(md5($this->newPassword));
        $this->user->setUserPasswordResetToken(Functions::getRandomString());
        $this->user->setUserPasswordResetTokenUsed(1);
        $this->user->setUserEmailFindPasswordToken(Functions::getRandomString());
        $this->user->setUserEmailFindPasswordMode(0);
        DBHelper::updateUserChangePasswordByReset($this->user);
        if ($request->session()->get(KeyConstant::SESSION_USERID) == $this->userId) {
            $request->session()->flush();
            $cookieHelper = new CookieHelper();
            $cookieHelper->removeSavedCookie();
            $cookieHelper->removeCookie();
        }
        MailSendUtils::sendResetPasswordEmail($this->user,$this->newPassword);
        $this->resetPasswordErrorCode = 0;
    }

    public function getErrorString($errorId)
    {
        switch ($errorId) {
            case 0:
                return "密码重置成功，即将返回登入页。";
                break;
            case 1:
                return "数据错误，请返回重新申请密码重置。";
                break;
            case 2:
                return "账号已被封禁，无法重置密码。";
                break;
            case 3:
                return "令牌已失效，请返回重新申请密码重置。";
                break;
            case 4:
                return "提交数据有误，请返回重新申请密码重置。";
                break;
            case 5:
                return "邮箱地址有误，请返回重新申请密码重置。";
                break;
            case 6:
                return "两次输入的密码不一致";
                break;
            case 7:
                return "密码位数错误";
                break;
            case 8:
                return "用户不存在，请返回重新申请密码重置。";
                break;
            case 9:
                return "令牌失效，请返回重新申请密码重置。";
                break;
            default:
                return "未知错误，请返回重新申请密码重置。";
                break;
        }
    }
}