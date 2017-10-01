<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'IndexPageController');


#静态页面
Route::get('about', 'StaticPage\AboutPageController');//关于
Route::get('copyright', 'StaticPage\CopyRightPageController');//版权声明
Route::get('faq', 'StaticPage\FaqPageController');//FAQ
Route::get('404', 'StaticPage\ErrorPageController@error404');//404
Route::get('error', 'StaticPage\ErrorPageController@error');//普通错误
Route::get('dbError', 'StaticPage\DBErrorPageController@dbError');//普通错误
Route::get('timeout', 'StaticPage\ErrorPageController@timeout');//超时
Route::get('welcome', 'StaticPage\WelcomePageController');//欢迎


#和用户账户相关
Route::get('register', "Account\RegisterPageController@get");//注册
Route::post('register', "Account\RegisterPageController@post");//注册提交
Route::any('logout', "Account\LogoutPageController@doLogout");//登出
Route::get('iframeLogin', "Account\LoginPageController@iframeGet");//弹窗登入
Route::post('iframeLogin', "Account\LoginPageController@iframePost");//弹窗登入提交
Route::get('login', "Account\LoginPageController@normalGet");//登入
Route::post('login', "Account\LoginPageController@normalPost");//登入提交
Route::get('changePassword', "Account\ChangePasswordPageController@get");//修改密码
Route::post('changePassword', "Account\ChangePasswordPageController@post");//修改密码提交
Route::get('mailCheck', "Account\MailCheckPageController@doCheck");//校验邮箱地址
Route::get('forgetPassword', 'Account\ForgetPasswordPageController@get');//忘记密码
Route::post('forgetPassword', 'Account\ForgetPasswordPageController@post');//忘记密码提交
Route::get('findPasswordInMailLink', 'Account\FindPasswordInMailLinkPageController@get');//忘记密码使用邮件链接重置校验密钥
Route::get('resetPassword', 'Account\ResetPasswordPageController@get');//重置密码
Route::post('resetPassword', 'Account\ResetPasswordPageController@post');//重置密码提交
Route::get('changeEmailAddress', 'Account\ChangeEmailAddressPageController@get');//修改邮箱地址
Route::post('changeEmailAddress', 'Account\ChangeEmailAddressPageController@post');//修改邮箱地址提交

#与安全令相关页面
Route::get('MyAuthList.csv', 'Auth\MyAuthListController@getCsv');
Route::get('myAuthList', 'Auth\MyAuthListController@getPage');
Route::get('auth', 'Auth\AuthDetailPageController@get');
Route::get('deleteAuth', 'Auth\AuthDeletePageController@get');
Route::get('addAuth', 'Auth\AuthAddPageController@get');
Route::post('addAuthByServer', 'Auth\AuthAddTaskController@addByServer');
Route::post('addAuthBySecret', 'Auth\AuthAddTaskController@addBySecret');
Route::post('addAuthByRestoreCode', 'Auth\AuthAddTaskController@addByRestoreCode');

#网站交互页面
Route::get('account', 'Account\AccountPageController@get');

#验证数据相关API
Route::get("api/captchaCode", 'Api\CaptchaCodeImageController@captcha');
Route::get("api/check/checkUserName", 'Api\CheckController@checkUserName');
Route::get("api/check/checkCpatcha", 'Api\CheckController@checkCpatcha');
Route::get("api/resendVerifyEmail", 'Api\ResendVerifyEmailController@reSendEmail');

#账户API
Route::get('api/unBindWechatAccount',"Api\UnBindWechatController@get");

#安全令数据相关API
Route::get("api/auth/doSync", "Auth\AuthApiController@doSync");
Route::get("api/auth/setDefault", "Auth\AuthApiController@setDefault");
Route::get("api/auth/changeName", "Auth\AuthApiController@changeName");
Route::get("api/auth/deleteAuth", "Auth\AuthApiController@deleteAuth");
Route::get("api/auth/getCode", "Auth\AuthApiController@getCode");

#一键登录相关API及页面
Route::get('api/oneButtonAuth/getRequestInfo', 'OneButtonLogin\ApiController@getRequestInfo');//获取是否有一键登录请求
Route::get('oneButtonAuth/iframePage', 'OneButtonLogin\PageController@get');//一键登录页面
Route::post('api/oneButtonAuth/commit', 'OneButtonLogin\ApiController@commitPost');//提交许可信息页面

#捐赠信息页面
Route::any('donate', 'StaticPage\DonatePageController@seeDonate');//捐赠
Route::get('addDonate', 'StaticPage\DonatePageController@addDonatePage');//添加捐赠信息
Route::post('addDonate', 'StaticPage\DonatePageController@addDonatePost');//添加捐赠信息

#用户相关
Route::get('banUser', 'Account\BanUserPageController@get');//封禁用户
Route::post('banUser', 'Account\BanUserPageController@post');//封禁用户


#微信相关
Route::post('api/wechat/getSessionToken', 'Wechat\LoginController@post');//获取Session值
Route::post('api/wechat/bindAccount', 'Wechat\AccountController@bind');//绑定账号
Route::post('api/wechat/register', 'Wechat\AccountController@register');//注册
Route::post('api/wechat/unBind', 'Wechat\UnBindController@unBind');//取消绑定
Route::post('api/wechat/userInfo', 'Wechat\UserInfoController@userInfo');//用户信息
Route::post('api/wechat/authCount','Wechat\AuthController@getAuthCount');//安全令数量
Route::post('api/wechat/authList', 'Wechat\AuthController@getAuthList');//安全令列表
Route::post('api/wechat/authInfo', 'Wechat\AuthController@getAuthInfo');//单个的安全令信息
Route::post('api/wechat/authDynamicCode', 'Wechat\AuthController@getAuthDynamicCode');//安全令动态码
Route::post('api/wechat/authAddByServer', 'Wechat\AuthAddController@byServer');//通过服务器生成新的安全令
Route::post('api/wechat/authAddByRestoreCode', 'Wechat\AuthAddController@authAddByRestoreCode');//通过还原码生成新的安全令
Route::post('api/wechat/authDelete', 'Wechat\AuthController@deleteAuth');//删除安全令接口
Route::post('api/wechat/authChangeName', 'Wechat\AuthController@authChangeName');//改名
Route::post('api/wechat/authSetDefault', 'Wechat\AuthController@authSetDefault');//设置默认
Route::post('api/wechat/authSyncTime', 'Wechat\AuthController@authSyncTime');//校对时间
Route::post('api/wechat/getOneButtonAuthRequestInfo','Wechat\OnButtonAuthController@getRequestInfo');//获取战网端一键安全令信息
Route::post('api/wechat/commitOneKeyButtonAuthResponse','Wechat\OnButtonAuthController@commit');//获取战网端一键安全令信息
