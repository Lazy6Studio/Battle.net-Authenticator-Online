<?php
use App\User;
use Illuminate\Http\Request;

/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/14 0014
 * Time: 下午 15:19
 */
class FindPasswordMailCheckUtils
{
    private $findPasswordMailCheckErrorCode = -999;
    private $userId;
    private $findPasswordCheckToken;
    private $createdResetToken;

    /**
     * @return array|string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getFindPasswordMailCheckErrorCode()
    {
        return $this->findPasswordMailCheckErrorCode;
    }

    /**
     * @return mixed
     */
    public function getCreatedResetToken()
    {
        return $this->createdResetToken;
    }

    function __construct(Request $request)
    {
        $this->findPasswordMailCheckErrorCode = -999;
        $this->userId = $request->input('userId');
        $this->findPasswordCheckToken = $request->input('findPasswordCheckToken');
        self::doCheck($request);
    }


    public function doCheck($request)
    {
        if (empty($this->userId) || empty($this->findPasswordCheckToken) || !Functions::isInt($this->userId)) {
            $this->findPasswordMailCheckErrorCode = 1;
            return;
        }
        if (!Functions::isFindPasswordTokenValid($this->findPasswordCheckToken)) {
            $this->findPasswordMailCheckErrorCode = 1;
            return;
        }
        $user = new User();
        $user->initUserByUserId($this->userId);
        if (empty($user->getUserId()) || !Functions::isInt($user->getUserId()) ||
            empty($user->getUserName())
        ) {
            $this->findPasswordMailCheckErrorCode = 3;
            return;
        }
        if ($user->getUserEmailFindPasswordToken() != $this->findPasswordCheckToken) {
            $this->findPasswordMailCheckErrorCode = 3;
            return;
        }
        if ($user->getUserEmailFindPasswordMode() != 1) {
            $this->findPasswordMailCheckErrorCode = 2;
            return;
        }
        if ($user->getUserRight() == User::USER_BANED) {
            $this->findPasswordMailCheckErrorCode = 5;
            return;
        }
        $user->setUserPasswordResetToken(Functions::getRandomString());
        $user->setUserPasswordResetTokenUsed(0);
        $result = DBHelper::updateUserResetPasswordSTokenState($user);
        if ($result === false) {
            $this->findPasswordMailCheckErrorCode = 4;
            return;
        }
        $this->createdResetToken = $user->getUserPasswordResetToken();
        $this->findPasswordMailCheckErrorCode = 0;
    }


    public function getErrorString($errorId)
    {
        switch ($errorId) {
            case 0:
                //正确
                return "";
                break;
            case 1:
                return "参数有误，请返回重试";
                break;
            case 2:
                return "密钥已过期，请返回重试";
                break;
            case 3:
                return "密钥错误，请返回重试";
                break;
            case 4:
                return "重置密码密钥生成失败，请返回重试";
                break;
            case 5:
                return "账号已被封禁，请返回重试";
                break;
            default:
                return "未知错误，请返回重试";
                break;
        }
    }

}