<?php
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/13 0013
 * Time: 下午 15:39
 */
class LoginCheckUtils
{
    private $loginErrorCode = -999;

    private $inputUsername;
    private $inputPassword;
    private $captchaCode;
    private $persistLogin;

    /**
     * @return int
     */
    public function getLoginErrorCode()
    {
        return $this->loginErrorCode;
    }

    function __construct(Request $request)
    {
        $this->loginErrorCode = -999;
        $this->inputUsername = $request->input("username");
        $this->inputPassword = $request->input("password");
        $this->captchaCode = $request->input("letters_code");
        $this->persistLogin = $request->input("persistLogin");
    }

    public function doCheck(Request $request)
    {
        $captchaUtils = new CaptchaUtils();
        if (!$captchaUtils->isCaptchaCodeValid($this->captchaCode)) {
            $this->loginErrorCode = 1;
            return;
        }
        if (empty($this->inputUsername) || empty($this->inputPassword) || !Functions::isUsernameValid($this->inputUsername)) {
            $this->loginErrorCode = 2;
            return;
        }
        $user = new User();
        $user->initUserByUserName($this->inputUsername);
        if (empty($user->getUserId()) || !Functions::isInt($user->getUserId())) {
            $this->loginErrorCode = 2;
            return;
        }
        $user = Functions::checkPostUserNameAndPasswordHasUser($user, $this->inputPassword);
        if ($user === false) {
            $this->loginErrorCode = 2;
            return;
        }
        if (!($user instanceof User)) {
            $this->loginErrorCode = -999;
            return;
        }
        if ($user->getUserRight() == User::USER_BANED) {
            $this->loginErrorCode = 3;
            return;
        }
        if ($user->getUserRight() == User::USER_SHARED) {
            if (time() - strtotime($user->getUserThisLoginTime()) < 1800) {
                Auth::setUser($user);
                $this->loginErrorCode = -1;
                return;
            }
        }
        $user->setIsLogin(true);
        $user->setUserLastLoginTime($user->getUserThisLoginTime());
        $user->setUserThisLoginTime(date('Y-m-d H:i:s'));
        $user->setUserLastTimeLoginIP($user->getUserThisTimeLoginIP());
        $user->setUserThisTimeLoginIP($request->ip());
        $user->setLastUsedSessionTime(time());
        DBHelper::updateUserLoginTimeAndIp($user);
        Auth::setUser($user);
        $this->loginErrorCode = 0;
        if ($this->persistLogin == "on") {
            $cookieHelper = new CookieHelper();
            $cookieHelper->saveCookie($user);
        }
        $request->session()->put(KeyConstant::SESSION_USERID, $user->getUserId());
        return;
    }


    public static function getErrorString($errorId)
    {
        switch ($errorId) {
            case 0:
                //正确
                return "";
                break;
            case 1:
                return "验证码输入错误!";
                break;
            case 2:
                return "用户名或密码输入错误!";
                break;
            case 3:
                return "账号已被封禁!";
                break;
            case -1:
                return "共享账号距离上次登录时间不足!";
                break;
            default:
                return "登录失败,未知错误!";
                break;
        }
    }
}