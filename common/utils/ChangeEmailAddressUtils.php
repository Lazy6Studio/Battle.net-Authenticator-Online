<?php
use App\User;
use Illuminate\Http\Request;

/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/14 0014
 * Time: 下午 23:23
 */
class ChangeEmailAddressUtils
{
    private $changeEmilAddressErrorCode = -999;
    private $captchaCode;
    private $userEmail;
    private $questionCode;
    private $userAnswer;
    private $registerQuestion;

    /**
     * @return int
     */
    public function getChangeEmilAddressErrorCode()
    {
        return $this->changeEmilAddressErrorCode;
    }

    function __construct(Request $request)
    {
        $this->registerQuestion = HttpFormConstant::getRegisterQuestionArray();
        $this->changeEmilAddressErrorCode = -999;
        $this->captchaCode = $request->input("letters_code");
        $this->userEmail = $request->input('email');
        $this->questionCode = $request->input('question1');
        $this->userAnswer = $request->input('answer1');
    }

    function doCheck(Request $request, User $user)
    {
        $captchaUtils = new CaptchaUtils();
        if (!$captchaUtils->isCaptchaCodeValid($this->captchaCode)) {
            $this->changeEmilAddressErrorCode = 1;
            return;
        }
        if (empty($this->userEmail) || empty($this->questionCode) || empty($this->userAnswer)) {
            $this->changeEmilAddressErrorCode = 2;
            return;
        }
        if(!Functions::isEmailValid($this->userEmail)){
            $this->changeEmilAddressErrorCode = 3;
            return;
        }
        if (empty($this->registerQuestion[$this->questionCode])) {
            $this->changeEmilAddressErrorCode = 4;
            return;
        }
        if ($user->getUserRight() == User::USER_BANED) {
            $this->changeEmilAddressErrorCode = 5;
            return;
        }
        if($user->getUserQuestion()!=$this->questionCode || $user->getUserAnswer()!=$this->userAnswer){
            $this->changeEmilAddressErrorCode = 6;
            return;
        }
        $oldMailAddress = $user->getUserEmail();
        if($oldMailAddress==$this->userEmail){
            $this->changeEmilAddressErrorCode = 7;
            return;
        }
        $user->setUserEmail($this->userEmail);
        $user->setUserEmailCheckToken(Functions::getRandomString());
        $user->setUserEmailChecked(0);
        DBHelper::updateUserChangeEmailAddress($user);
        MailSendUtils::sendChangeEmailAddressMail($user,$oldMailAddress);
        $this->changeEmilAddressErrorCode = 0;
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
                return "邮箱格式错误。";
                break;
            case 4:
                return "请选择密码找回问题。";
                break;
            case 5:
                return "账号已被封禁。";
                break;
            case 6:
                return "安全问题及答案错误。";
                break;
            case 7:
                return "您当前的邮箱与新邮箱地址相同。";
                break;
            default:
                return "修改邮箱失败，未知错误。";
                break;
        }
    }
}