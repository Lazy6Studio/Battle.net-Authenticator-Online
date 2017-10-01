<?php

namespace App\Http\Controllers\Wechat;


use App\Http\Controllers\Controller;
use App\User;
use AuthUtils;
use CaptchaUtils;
use DBHelper;
use Functions;
use HttpFormConstant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MailSendUtils;
use RedisHelper;
use WechatTokenBean;

class AccountController extends Controller
{

    function __construct()
    {
        $this->middleware('wechat.check:false,true');
    }

    public function bind(Request $request)
    {
        $user = Auth::user();
        if (!empty($user->getUserId()) && Functions::isInt($user->getUserId())) {
            $json = ['code' => 403, 'message' => "您的微信已有绑定，如要绑定其他账号，请先取消绑定"];
            return response()->json($json);
        }
        $username = $request->json("username");
        $password = $request->json('password');
        $wechatNickname = $request->json('wechatNickname');
        if (empty($username) || empty($password)) {
            $json = ['code' => 403, 'message' => empty($username) ? '用户名为空' : '密码为空'];
            return response()->json($json);
        }
        $user->initUserByNameAndPassword($username, md5($password));
        if (empty($user->getUserId()) || !Functions::isInt($user->getUserId())) {
            $json = ['code' => 403, 'message' => '用户名或密码输入错误'];
            return response()->json($json);
        }
        $user->setWechatOpenID($user->getWechatTokenBean()->getWechatTokenOpenId());
        if ($user->getUserRight() != User::USER_NORMAL) {
            $json = ['code' => 403, 'message' => $user->getUserRight() == User::USER_SHARED ? "共享账号不能绑定到微信小程序" : "您的账号已被封禁，无法使用"];
            return response()->json($json);
        }
        $user->setUserLastLoginTime($user->getUserThisLoginTime());
        $user->setUserThisLoginTime(date('Y-m-d H:i:s'));
        $user->setUserLastTimeLoginIP($user->getUserThisTimeLoginIP());
        $user->setUserThisTimeLoginIP($request->ip());
        $user->setLastUsedSessionTime(time());
        $result = DBHelper::updateUserBindWechatOpenId($user);
        if ($result == false) {
            $json = ['code' => 500, 'message' => '服务器数据库错误'];
            return response()->json($json);
        }
        $authUtils = new AuthUtils();
        $authUtils->getAllAuth($user);
        $userMaxAuthCount = $user->getUserDonated() == 1 ? config('app.auth_max_count_donated_user') : config('app.auth_max_count_standard_user');
        $json = ['code' => 200, 'message' => '小程序账号绑定成功',
            'data' => [
                'hasAuth' => $authUtils->getAuthCount() > 0,
                'authCount'=>$authUtils->getAuthCount(),
                'canAddMoreAuth' => $authUtils->getAuthCount() < $userMaxAuthCount,
                'userName' => $user->getUserName()
            ]];
        MailSendUtils::sendWechatBindEmail($user, $wechatNickname);
        return response()->json($json);
    }

    public function register(Request $request)
    {
        $user = Auth::user();
        if (!empty($user->getUserId()) && Functions::isInt($user->getUserId())) {
            $json = ['code' => 403, 'message' => "您的微信已有绑定，如要注册其他账号，请先取消绑定"];
            return response()->json($json);
        }
        $registerQuestion = HttpFormConstant::getRegisterQuestionArray();
        $username = $request->json('username');
        if (empty($username)) {
            $json = ['code' => 403, 'message' => "请输入用户名"];
            return response()->json($json);
        }
        if (!Functions::isUsernameValid($username)) {
            $json = ['code' => 403, 'message' => "用户名格式错误"];
            return response()->json($json);
        }

        if (mb_strlen($username) > 32) {
            $json = ['code' => 403, 'message' => "用户名不能超过32位"];
            return response()->json($json);
        }
        if (DBHelper::getUserNameCount($username) > 0) {
            $json = ['code' => 403, 'message' => "用户已存在，请直接绑定账号"];
            return response()->json($json);
        }
        $password = $request->json('password');
        if (strlen($password) < 8 || strlen($password) > 16) {
            $json = ['code' => 403, 'message' => strlen($password) == 0 ? "请输入密码" : "密码长度错误"];
            return response()->json($json);
        }
        $userEmail = $request->json('email');
        if (!Functions::isEmailValid($userEmail)) {
            $json = ['code' => 403, 'message' => "邮箱地址格式错误"];
            return response()->json($json);
        }
        $questionCode = $request->json('question');
        if (empty($registerQuestion[$questionCode])) {
            $json = ['code' => 403, 'message' => "安全问题选择错误"];
            return response()->json($json);
        }
        $questionAnswer = $request->json('answer');
        if (empty($questionAnswer)) {
            $json = ['code' => 403, 'message' => "请输入安全问题答案"];
            return response()->json($json);
        }
        $wechatNickname = $request->json('wechatNickname');
        $user = Auth::user();
        return $this->doRegister($user, $username, $password, $userEmail, $questionCode, $questionAnswer, $request->ip(), $wechatNickname);
    }

    private function doRegister(User $user, $username, $password, $userEmail, $questionCode, $questionAnswer, $ip, $wechatNickname)
    {
        $registerDate = date('Y-m-d H:i:s');
        $user->setWechatOpenID($user->getWechatTokenBean()->getWechatTokenOpenId());

        $user->setUserName($username);
        $user->setUserPass(md5($password));
        $user->setUserEmail($userEmail);
        $user->setUserQuestion($questionCode);
        $user->setUserAnswer($questionAnswer);
        $user->setUserRight(User::USER_NORMAL);
        $user->setUserEmailChecked(0);

        $user->setUserRegisterTime($registerDate);
        $user->setUserEmailCheckToken(Functions::getRandomString());
        $user->setUserEmailFindPasswordToken(Functions::getRandomString());
        $user->setUserEmailFindPasswordMode(0);
        $user->setUserPasswordResetToken(Functions::getRandomString());
        $user->setUserPasswordResetTokenUsed(1);
        $user->setUserLastTimeLoginIP($ip);
        $user->setUserThisTimeLoginIP($ip);
        $user->setUserLastLoginTime($registerDate);
        $user->setUserThisLoginTime($registerDate);
        $user->setLastUsedSessionTime(time());
        $user->setUserDonated(0);
        $newUserId = DBHelper::insertNewUser($user);
        if ($newUserId == false || empty($newUserId) || !Functions::isInt($newUserId)) {
            $json = ['code' => 500, 'message' => '服务器数据库错误'];
            return response()->json($json);
        }
        $user->setUserId($newUserId);
        MailSendUtils::sendRegisterSuccessEmail($user, $password, $wechatNickname);
        $json = ['code' => 200, 'message' => '注册成功，您的账号已与小程序绑定',
            'data' => ['hasAuth' => false,
                'userName' => $user->getUserName()]
        ];
        return response()->json($json);
    }

}