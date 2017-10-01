<?php
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/14 0014
 * Time: 下午 14:22
 */
class ForgetPasswordCheckUtils
{

    private $forgetPasswordCheckErrorCode = -999;

    /**
     * @return int
     */
    public function getForgetPasswordCheckErrorCode()
    {
        return $this->forgetPasswordCheckErrorCode;
    }


    private $captchaCode;
    private $userName;
    private $userEmail;
    private $questionCode;
    private $userAnswer;
    private $registerQuestion;

    function __construct(Request $request)
    {
        $this->registerQuestion = HttpFormConstant::getRegisterQuestionArray();
        $this->forgetPasswordCheckErrorCode = -999;
        $this->captchaCode = $request->input("letters_code");
        $this->userName = $request->input('firstName');
        $this->userEmail = $request->input('email');
        $this->questionCode = $request->input('question1');
        $this->userAnswer = $request->input('answer1');
    }

    function doCheck(Request $request)
    {
        $captchaUtils = new CaptchaUtils();
        if (!$captchaUtils->isCaptchaCodeValid($this->captchaCode)) {
            $this->forgetPasswordCheckErrorCode = 1;
            return;
        }
        if (empty($this->userName) || empty($this->userEmail) || empty($this->questionCode) || empty($this->userAnswer)) {
            $this->forgetPasswordCheckErrorCode = 4;
            return;
        }
        if (!Functions::isUsernameValid($this->userName)) {
            $this->forgetPasswordCheckErrorCode = 5;
            return;
        }
        if (empty($this->registerQuestion[$this->questionCode])) {
            $this->forgetPasswordCheckErrorCode = 7;
            return;
        }
        $user = new User();
        $user->initUserByUserName($this->userName);
        if (empty($user->getUserId()) || empty($user->getUserName())) {
            $this->forgetPasswordCheckErrorCode = 2;
            return;
        }
        if (!($this->questionCode == $user->getUserQuestion() && $this->userEmail == $user->getUserEmail() && $this->userAnswer == $user->getUserAnswer())) {
            $this->forgetPasswordCheckErrorCode = 3;
            return;
        }
        if ($user->getUserRight() == User::USER_BANED) {
            $this->forgetPasswordCheckErrorCode = 8;
            return;
        }
        $emailFindCode = Functions::getRandomString();
        $user->setUserEmailFindPasswordToken($emailFindCode);
        $user->setUserEmailFindPasswordMode(1);
        $_USER = Auth::user();
        if ($_USER->getIsLogin() && $_USER->getUserId() == $user->getUserId()) {
            $_USER->setUserEmailFindPasswordToken($emailFindCode);
            $_USER->setUserEmailFindPasswordMode(1);
            Auth::setUser($_USER);
        }
        if (DBHelper::updateUserFindPasswordSTokenState($user) === false) {
            return;
        }
        MailSendUtils::sendFindPasswordEmail($user);
        $this->forgetPasswordCheckErrorCode = 0;
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
                return "用户不存在。";
                break;
            case 3:
                return "输入的信息不正确。";
                break;
            case 4:
                return "输入的信息不完整。";
                break;
            case 5:
                return "用户名存在非法字符，用户名仅允许使用中文、数字、字母、下划线。";
                break;
            case 7:
                return "请选择密码找回问题。";
                break;
            case 8:
                return "账号已被封禁。";
                break;
            default:
                return "找回密码失败，未知错误。";
                break;
        }
    }
}