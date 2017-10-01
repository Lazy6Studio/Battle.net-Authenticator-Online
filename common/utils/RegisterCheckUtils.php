<?php
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/12 0012
 * Time: 下午 22:58
 */
class RegisterCheckUtils
{
    private $registerQuestion;
    private $registerErrorCode;

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
                return "缺少输入内容。";
                break;
            case 3:
                return "邮箱格式错误。";
                break;
            case 4:
                return "用户名存在非法字符，用户名仅允许使用中文、数字、字母、下划线。";
                break;
            case 5:
                return "用户名已存在。";
                break;
            case 6:
                return "请选择密码找回问题。";
                break;
            case 7:
                return "两次输入的密码不一致。";
                break;
            case 8:
                return "密码位数错误。";
                break;
            case 9:
                return "两次输入的邮箱地址不一致。";
                break;
            case 10:
                return "用户名最长不能超过32位。";
                break;
            default:
                return "注册失败，未知错误。";
                break;
        }
    }

    private $captchaCode;
    private $username;
    private $password;
    private $emailAddress;
    private $emailAddressConfirmation;
    private $questionCode;
    private $questionAnswer;
    private $reInputPassword;
    private $needLowRight;
    private $registerDate;
    private $userRight;
    private $emailCheckCode;
    private $emailFindToken;
    private $emailResetToken;
    private $userIp;

    /**
     * @return int
     */
    public function getRegisterErrorCode()
    {
        return $this->registerErrorCode;
    }

    function __construct(Request $request)
    {
        $this->registerErrorCode = -999;
        $this->registerQuestion = HttpFormConstant::getRegisterQuestionArray();
        $this->captchaCode = $request->input("letters_code");
        $this->username = $request->input("username");
        $this->password = $request->input("password");
        $this->emailAddress = $request->input("emailAddress");
        $this->emailAddressConfirmation = $request->input("emailAddressConfirmation");
        $this->questionCode = $request->input("question1", 81);
        $this->questionAnswer = $request->input("answer1");
        $this->reInputPassword = $request->input("rePassword");
        $this->needLowRight = $request->input("lowright") === "lowright";
        $this->userIp = $request->ip();
        $this->userRight = $this->needLowRight ? User::USER_SHARED : User::USER_NORMAL;
        self::checkData();
    }

    private function checkData()
    {
        $captchaUtils = new CaptchaUtils();
        if (!$captchaUtils->isCaptchaCodeValid($this->captchaCode)) {
            $this->registerErrorCode = 1;
            return;
        }
        if (empty($this->username) || empty($this->password) || empty($this->emailAddress) || empty($this->emailAddressConfirmation) || empty($this->questionCode) || empty($this->questionAnswer) || empty($this->reInputPassword)) {
            $this->registerErrorCode = 2;
            return;
        }
        if ($this->emailAddressConfirmation !== $this->emailAddress) {
            $this->registerErrorCode = 3;
            return;
        }
        if (!Functions::isEmailValid($this->emailAddress)) {
            $this->registerErrorCode = 3;
            return;
        }
        if (!Functions::isUsernameValid($this->username)) {
            $this->registerErrorCode = 4;
            return;
        }
        if (mb_strlen($this->username) > 32) {
            $this->registerErrorCode = 10;
            return;
        }
        if (DBHelper::getUserNameCount($this->username) > 0) {
            $this->registerErrorCode = 5;
            return;
        }
        if (empty($this->registerQuestion[$this->questionCode])) {
            $this->registerErrorCode = 6;
            return;
        }
        if ($this->password !== $this->reInputPassword) {
            $this->registerErrorCode = 7;
            return;
        }
        $this->password = $this->reInputPassword = Functions::getUnencryptPassword($this->password);
        if (strlen($this->password) < 8 || strlen($this->password) > 16) {
            $this->registerErrorCode = 8;
            return;
        }
    }

    public function doRegister(Request $request)
    {
        $this->registerDate = date('Y-m-d H:i:s');
        $this->emailCheckCode = Functions::getRandomString();
        $this->emailFindToken = Functions::getRandomString();
        $this->emailResetToken = Functions::getRandomString();
        $user = new User();
        $user->setUserName($this->username);
        $user->setUserPass(md5($this->password));
        $user->setUserRight($this->userRight);
        $user->setUserEmail($this->emailAddress);
        $user->setUserEmailChecked(0);
        $user->setUserRegisterTime($this->registerDate);
        $user->setUserQuestion($this->questionCode);
        $user->setUserAnswer($this->questionAnswer);
        $user->setUserEmailCheckToken($this->emailCheckCode);
        $user->setUserEmailFindPasswordToken($this->emailFindToken);
        $user->setUserEmailFindPasswordMode(0);
        $user->setUserPasswordResetToken($this->emailResetToken);
        $user->setUserPasswordResetTokenUsed(1);
        $user->setUserLastTimeLoginIP($this->userIp);
        $user->setUserThisTimeLoginIP($this->userIp);
        $user->setUserLastLoginTime($this->registerDate);
        $user->setUserThisLoginTime($this->registerDate);
        $user->setLastUsedSessionTime(time());
        $user->setUserDonated(0);
        $newUserId = DBHelper::insertNewUser($user);
        if ($newUserId == false || empty($newUserId) || !Functions::isInt($newUserId)) {
            $this->registerErrorCode = 9;
            return;
        }
        $user->setUserId($newUserId);
        $user->setIsLogin(true);
        Auth::setUser($user);
        $cookieHelper = new CookieHelper();
        $cookieHelper->saveCookie($user);
        if ($user->getUserRight() == User::USER_NORMAL) {
            $request->session()->put(KeyConstant::SESSION_USERID, $user->getUserId());
        }
        MailSendUtils::sendRegisterSuccessEmail($user, $this->password);
        $this->registerErrorCode = -99;
    }
}

