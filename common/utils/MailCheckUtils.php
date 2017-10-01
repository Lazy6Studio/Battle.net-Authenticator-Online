<?php
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/14 0014
 * Time: 下午 13:21
 */
class MailCheckUtils
{
    private $mailCheckErrorCode = -999;
    private $userId;
    private $checkCode;

    function __construct(Request $request)
    {
        $this->mailCheckErrorCode = -999;
        $this->userId = $request->input('userId');
        $this->checkCode = $request->input('checkCode');
    }

    /**
     * @return int
     */
    public function getMailCheckErrorCode()
    {
        return $this->mailCheckErrorCode;
    }


    function doCheck(Request $request, User $mainUser)
    {
        if (empty($this->userId) || empty($this->checkCode) || !Functions::isInt($this->userId)) {
            $this->mailCheckErrorCode = 1;
            return;
        }
        $user = new User();
        $user->initUserByUserId($this->userId);
        if (empty($user->getUserId()) || empty($user->getUserName())) {
            $this->mailCheckErrorCode = 4;
            return;
        }
        if ($user->getUserEmailChecked() == 1) {
            $this->mailCheckErrorCode = 2;
            return;
        }
        if ($user->getUserEmailCheckToken() != $this->checkCode) {
            $this->mailCheckErrorCode = 3;
            return;
        }
        $this->mailCheckErrorCode = 0;
        $user->setUserEmailChecked(1);
        $user->setUserEmailCheckToken(Functions::getRandomString());
        DBHelper::updateUserEmailCheckedState($user);
    }

    public static function getErrorString($errorId)
    {
        switch ($errorId) {
            case 0:
                //正确
                return "邮件地址确认成功";
                break;
            case 1:
                return "邮件地址确认失败，参数错误";
                break;
            case 2:
                return "该账号邮件地址已经确认，无需重复确认";
                break;
            case 3:
                return "邮件地址确认失败，密钥错误";
                break;
            case 4:
                return "邮件地址确认失败，用户不存在";
                break;
            default:
                return "邮件地址确认失败，未知错误";
                break;
        }
    }


}
