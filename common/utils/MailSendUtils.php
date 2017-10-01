<?php
use App\User;
use Illuminate\Support\Facades\Mail;

/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/14 0014
 * Time: 下午 22:48
 */
class MailSendUtils
{


    /**
     * 发送注册成功邮件
     * @param User $user
     * @param $unMd5Password
     * @param string $wechatNickname
     * @return mixed
     */
    public static function sendRegisterSuccessEmail(User $user, $unMd5Password, $wechatNickname = "")
    {
        $registerQuestion = HttpFormConstant::getRegisterQuestionArray();
        $mailCheckUrl = config('app.url') . "mailCheck?userId={$user->getUserId()}&checkCode={$user->getUserEmailCheckToken()}";
        $mailText = "本邮件为系统自动发送，您的" . config('app.name') . "账号已经创建<br><br>" .
        "您的用户名：{$user->getUserName()}<br><br>" .
        "您的用户ID：{$user->getUserId()}<br><br>" .
        "您的密码：" . Functions::emailHidePassword($unMd5Password) . " (只显示前三位)<br><br>" .
        "您的安全问题：" . $registerQuestion[$user->getUserQuestion()] . "<br><br>" .
        "您的问题答案：(出于安全考虑，该信息已隐藏)<br><br>" .
        "您的账号类型：" . ($user->getUserRight() == User::USER_NORMAL ? "普通账号" : "共享账号") . "<br>" .
        "您的注册邮箱：{$user->getUserEmail()}<br><br>" .
        "您的注册时间：{$user->getUserRegisterTime()}<br><br>" .
        empty($wechatNickname) ? "" : ("您绑定的微信账户昵称：$wechatNickname<br/>") .
            "您的账号已经创建，为了今后能顺利管理账号，请点击以下链接确认您的邮箱地址<br><br>" .
            "<a href='$mailCheckUrl' target='_blank'>$mailCheckUrl</a><br><br>" .
            "如果这不是您操作的，请删除本邮件，绝对不要点击以上链接。<br><br>" .
            "本邮件为自动发送，请不要回复，因为没人会看的。<br><br>" .
            config('app.owner') . "<br><br>" .
            date('Y-m-d');
        $data = ['topNavValueText' => "注册成功通知与邮箱验证提示", '_USER' => $user, "unMd5Password" => $unMd5Password,
            "registerQuestion" => $registerQuestion, "mailCheckUrl" => $mailCheckUrl, "mailText" => $mailText, 'wechatNickname' => $wechatNickname];
        Mail::send(['email.registerSuccess', 'email.text'], $data, function ($message) use ($user) {
            $message->to($user->getUserEmail(), $user->getUserName())
                ->subject(config('app.name') . '注册成功通知与邮箱验证提示');
        });
        return true;
    }

    /**
     * 发送修改密码邮件
     * @param User $user
     * @param $unMd5Password
     * @return mixed
     */
    public static function sendChangePasswordSuccessEmail(User $user, $unMd5Password)
    {
        $registerQuestion = HttpFormConstant::getRegisterQuestionArray();
        $url = config('app.url') . "forgetPassword";
        $mailText = "本邮件为系统自动发送，您已经成功地修改了您的密码<br><br>" .
            "您的用户名：{$user->getUserName()}<br><br>" .
            "您的用户ID：{$user->getUserId()}<br><br>" .
            "您的账号类型：" . ($user->getUserRight() == User::USER_NORMAL ? "普通账号" : "共享账号") . "<br>" .
            "您的注册邮箱：{$user->getUserEmail()}<br><br>" .
            "您的注册时间：{$user->getUserRegisterTime()}<br><br>" .
            "您的新密码：" . Functions::emailHidePassword($unMd5Password) . " (只显示前三位)<br><br>" .
            "修改时间：" . date('Y-m-d H:i:s') . "<br><br>" .
            "如果这不是您操作的，请前往网站<a href='$url' target='_blank'>重置您的密码</a>。<br><br>" .
            "本邮件为自动发送，请不要回复，因为没人会看的。<br><br>" .
            config('app.owner') . "<br><br>" .
            date('Y-m-d');
        $data = ['topNavValueText' => "账户密码修改通知", '_USER' => $user, "unMd5Password" => $unMd5Password,
            "registerQuestion" => $registerQuestion, "mailText" => $mailText];
        return Mail::send(['email.changePasswordSuccess', 'email.text'], $data, function ($message) use ($user) {
            $message->to($user->getUserEmail(), $user->getUserName())
                ->subject(config('app.name') . '账户密码修改通知');
        });
    }

    /**
     * 发送找回密码邮件
     * @param User $user
     * @return mixed
     */
    public static function sendFindPasswordEmail(User $user)
    {
        $registerQuestion = HttpFormConstant::getRegisterQuestionArray();
        $findUrl = config('app.url') . "findPasswordInMailLink?userId={$user->getUserId()}&findPasswordCheckToken={$user->getUserEmailFindPasswordToken()}";
        $mailText = "本邮件为系统自动发送，您正在申请重置您账号的密码<br><br>" .
            "您的用户名：{$user->getUserName()}<br><br>" .
            "您的用户ID：{$user->getUserId()}<br><br>" .
            "您的账号类型：" . ($user->getUserRight() == User::USER_NORMAL ? "普通账号" : "共享账号") . "<br>" .
            "您的注册邮箱：{$user->getUserEmail()}<br><br>" .
            "您的注册时间：{$user->getUserRegisterTime()}<br><br>" .
            "您还需要最后一步，点击下方的链接前往密码重置页面，并按照页面要求填写。<br><br>" .
            "<a href='$findUrl' target='_blank'>$findUrl</a><br><br>" .
            "如果这不是您操作的，请删除本邮件，绝对不要点击以上链接。<br><br>" .
            "本邮件为自动发送，请不要回复，因为没人会看的。<br><br>" .
            config('app.owner') . "<br><br>" .
            date('Y-m-d');
        $data = ['topNavValueText' => "账户密码重置链接", '_USER' => $user, "registerQuestion" => $registerQuestion, "mailText" => $mailText,
            "findUrl" => $findUrl];
        Mail::send(['email.findPassword', 'email.text'], $data, function ($message) use ($user) {
            $message->to($user->getUserEmail(), $user->getUserName())
                ->subject(config('app.name') . '账户密码重置链接');
        });
        return true;
    }

    /**
     * 发送用户重置密码成功邮件
     * @param User $user
     * @param $unMd5Password
     * @return mixed
     */
    public static function sendResetPasswordEmail(User $user, $unMd5Password)
    {
        $registerQuestion = HttpFormConstant::getRegisterQuestionArray();
        $url = config('app.url') . "forgetPassword";
        $mailText = "本邮件为系统自动发送，您已经成功地重置了您的密码。<br><br>" .
            "您的用户名：{$user->getUserName()}<br><br>" .
            "您的用户ID：{$user->getUserId()}<br><br>" .
            "您的账号类型：" . ($user->getUserRight() == User::USER_NORMAL ? "普通账号" : "共享账号") . "<br>" .
            "您的注册邮箱：{$user->getUserEmail()}<br><br>" .
            "您的注册时间：{$user->getUserRegisterTime()}<br><br>" .
            "您的新密码：" . Functions::emailHidePassword($unMd5Password) . " (只显示前三位)<br><br>" .
            "如果这不是您操作的，请前往网站<a href='$url' target='_blank'>重置您的密码</a>。<br><br>" .
            "本邮件为自动发送，请不要回复，因为没人会看的。<br><br>" .
            config('app.owner') . "<br><br>" .
            date('Y-m-d');
        $data = ['topNavValueText' => "密码重置成功提醒", '_USER' => $user, "registerQuestion" => $registerQuestion, "mailText" => $mailText,
            "unMd5Password" => $unMd5Password];
        Mail::send(['email.resetPasswordSuccess', 'email.text'], $data, function ($message) use ($user) {
            $message->to($user->getUserEmail(), $user->getUserName())
                ->subject(config('app.name') . '密码重置成功提醒');
        });
        return true;
    }

    /**
     * 发送修改邮箱地址需重新确认邮件
     * @param User $user
     * @param $oldEmailAddress
     * @return mixed
     */
    public static function sendChangeEmailAddressMail(User $user, $oldEmailAddress)
    {
        $registerQuestion = HttpFormConstant::getRegisterQuestionArray();
        $mailCheckUrl = config('app.url') . "mailCheck?userId={$user->getUserId()}&checkCode={$user->getUserEmailCheckToken()}";
        $mailText = "本邮件为系统自动发送，您正在申请更改注册邮箱为当前邮箱<br><br>" .
            "您的用户名：{$user->getUserName()}<br><br>" .
            "您的用户ID：{$user->getUserId()}<br><br>" .
            "您的账号类型：" . ($user->getUserRight() == User::USER_NORMAL ? "普通账号" : "共享账号") . "<br>" .
            "您的注册时间：{$user->getUserRegisterTime()}<br><br>" .
            "您此前的邮箱地址为：$oldEmailAddress<br><br>" .
            "您现在的邮箱地址为：{$user->getUserEmail()}<br><br>" .
            "您的邮箱已经成功修改，为了今后能顺利管理账号，请点击以下链接确认您的邮箱地址<br><br>" .
            "<a href='$mailCheckUrl' target='_blank'>$mailCheckUrl</a><br><br>" .
            "如果这不是您操作的，请删除本邮件，不要点击以上链接，并进入我的账号页面更改邮箱地址及密码。<br><br>" .
            "本邮件为自动发送，请不要回复，因为没人会看的。<br><br>" .
            config('app.owner') . "<br><br>" .
            date('Y-m-d');
        $data = ['topNavValueText' => "验证新邮箱地址", '_USER' => $user, "registerQuestion" => $registerQuestion,
            "mailText" => $mailText, "mailCheckUrl" => $mailCheckUrl, "oldEmailAddress" => $oldEmailAddress];
        Mail::send(['email.changeEmailAddressSuccess', 'email.text'], $data, function ($message) use ($user) {
            $message->to($user->getUserEmail(), $user->getUserName())
                ->subject(config('app.name') . '验证新邮箱地址');
        });
        return true;
    }

    /**
     * 重新发送验证邮件
     * @param $user
     * @return bool
     */
    public static function resendVerifyEmail(User $user)
    {
        $registerQuestion = HttpFormConstant::getRegisterQuestionArray();
        $mailCheckUrl = config('app.url') . "mailCheck?userId={$user->getUserId()}&checkCode={$user->getUserEmailCheckToken()}";
        $mailText = "本邮件为系统自动发送，您需要验证" . config('app.name') . "账号的邮箱地址<br><br>" .
            "您的用户名：{$user->getUserName()}<br><br>" .
            "您的用户ID：{$user->getUserId()}<br><br>" .
            "您的账号类型：" . ($user->getUserRight() == User::USER_NORMAL ? "普通账号" : "共享账号") . "<br>" .
            "您的注册邮箱：{$user->getUserEmail()}<br><br>" .
            "您的注册时间：{$user->getUserRegisterTime()}<br><br>" .
            "为了今后能顺利管理账号，请点击以下链接确认您的邮箱地址<br><br>" .
            "<a href='$mailCheckUrl' target='_blank'>$mailCheckUrl</a><br><br>" .
            "如果这不是您操作的，请删除本邮件，绝对不要点击以上链接，并进入我的账号页面更改邮箱地址及密码。<br><br>" .
            "本邮件为自动发送，请不要回复，因为没人会看的。<br><br>" .
            config('app.owner') . "<br><br>" .
            date('Y-m-d');
        $data = ['topNavValueText' => "验证邮箱地址", '_USER' => $user,
            "registerQuestion" => $registerQuestion, "mailCheckUrl" => $mailCheckUrl, "mailText" => $mailText];
        Mail::send(['email.verifyEmailAddress', 'email.text'], $data, function ($message) use ($user) {
            $message->to($user->getUserEmail(), $user->getUserName())
                ->subject(config('app.name') . '验证邮箱地址');
        });
        return true;
    }

    public static function sendWechatBindEmail($user, $wechatNickname)
    {
        $registerQuestion = HttpFormConstant::getRegisterQuestionArray();
        $mailText = "本邮件为系统自动发送，您的账号已经绑定到微信小程序了<br><br>" .
            "您的用户名：{$user->getUserName()}<br><br>" .
            "您的用户ID：{$user->getUserId()}<br><br>" .
            "您的账号类型：" . ($user->getUserRight() == User::USER_NORMAL ? "普通账号" : "共享账号") . "<br>" .
            "您的注册邮箱：{$user->getUserEmail()}<br><br>" .
            "您的注册时间：{$user->getUserRegisterTime()}<br><br>" .
            (empty($wechatNickname) ? "" : "您绑定的微信账户昵称：$wechatNickname<br><br>") .
            "如果这不是您操作的，请到我的账号页面解除微信绑定。<br><br>" .
            "本邮件为自动发送，请不要回复，因为没人会看的。<br><br>" .
            config('app.owner') . "<br><br>" .
            date('Y-m-d');
        $data = ['topNavValueText' => "微信小程序账号绑定通知", '_USER' => $user,
            "registerQuestion" => $registerQuestion, "mailText" => $mailText, "wechatNickname" => $wechatNickname];
        Mail::send(['email.wechatBindSuccess', 'email.text'], $data, function ($message) use ($user) {
            $message->to($user->getUserEmail(), $user->getUserName())
                ->subject(config('app.name') . '微信小程序账号绑定通知');
        });
        return true;
    }


}