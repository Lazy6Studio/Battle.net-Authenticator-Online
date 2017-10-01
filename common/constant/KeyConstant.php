<?php

/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/11 0011
 * Time: 上午 9:12
 */
class KeyConstant
{
    const USERNAME = "username";
    const PASSWORD = "password";
    const SESSION_USERID = "session_standard_user_id";
    const SESSION_CAPTCHA = "session_random_captcha";
    const SESSION_LAST_SEND_MAIL = "session_anzong_last_send_mail";

    const COOKIE_KEY_USER_NAME = "loginUsername";
    const COOKIE_KEY_USER_TOKEN = "loginCookie";

    const CACHE_KEY_USER_COUNT = "cache_user_count";
    const CACHE_KEY_AUTH_COUNT = "cache_auth_count";

    const CACHE_KEY_USER_ID = "cache_user_id_";

    const CACHE_KEY_USER_WECHAT_OPEN_ID = "cache_user_wechat_open_id_";

    const CACHE_KEY_DONATE_INFO = "cache_donate_info";

    const CACHE_KEY_USER_AUTH_LIST = "cache_user_auth_list_user_id_";
    const CACHE_KEY_AUTH_SERVER_SYNC = "cache_auth_server_sync";


    const WECHAT_KEY_SESSION_TOKEN = "token_wechat_session_v1";
}