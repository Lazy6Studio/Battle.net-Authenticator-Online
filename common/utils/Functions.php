<?php
use App\User;

/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/11 0011
 * Time: 上午 8:47
 */
class Functions
{
    /**
     * @return bool 是否是HTTPS
     */
    static function isHTTPS()
    {
        if (!isset($_SERVER['HTTPS']))
            return FALSE;
        if ($_SERVER['HTTPS'] === 1) {  //Apache
            return TRUE;
        } elseif ($_SERVER['HTTPS'] === 'on') { //IIS
            return TRUE;
        } elseif ($_SERVER['SERVER_PORT'] == 443) { //其他
            return TRUE;
        }
        return FALSE;
    }

    /**
     * curl获取数据
     * @param string $url
     * @param string $userAgent
     * @return string
     */
    static function _curlGet($url = '', $userAgent = '')
    {
        $ch = curl_init();
        if (strlen($userAgent) > 6) {
            curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        }
        if (config('app.use_proxy') && !empty(config('app.proxy_url'))) {//测试模式
            curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
            curl_setopt($ch, CURLOPT_PROXY, config('app.proxy_url'));
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        ob_start();
        curl_exec($ch);
        $html = ob_get_contents();
        ob_end_clean();
        curl_close($ch);
        if (!is_string($html) || !strlen($html)) {
            return "";
        }
        return $html;
    }

    public static function _curlPost($url, $data)
    {
        $ch = curl_init();
        if (config('app.use_proxy') && !empty(config('app.proxy_url'))) {//测试模式
            curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
            curl_setopt($ch, CURLOPT_PROXY, config('app.proxy_url'));
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        ob_start();
        curl_exec($ch);
        $html = ob_get_contents();
        ob_end_clean();
        curl_close($ch);
        if (!is_string($html) || !strlen($html)) {
            return "";
        }
        return $html;
    }


    /**
     * 判断是否是整数
     * @param $value
     * @return bool
     */
    static function isInt($value)
    {
        return is_integer($value) || ctype_digit($value);
    }

    /**
     * 随机数生成器
     * @param int $len
     * @return string
     */
    static function getRandomString($len = 40)
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        mt_srand();
        $password = '';
        while (strlen($password) < $len)
            $password .= substr($chars, (int)(mt_rand() % strlen($chars)), 1);
        return sha1(md5($password));
    }


    /**
     * 验证用户名是否符合规定
     * @param $userName
     * @return int
     */
    static function isUsernameValid($userName)
    {
        return preg_match("/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u", $userName);
    }

    /**
     * 验证邮箱格式
     * @param $email
     * @return bool
     */
    static function isEmailValid($email)
    {
        if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
            return false;
        }
        $email_array = explode("@", $email);
        $local_array = explode(".", $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
            if (!preg_match("/^(([A-Za-z0-9!#$%&#038;'*+\\/=?^_`{|}~-][A-Za-z0-9!#$%&#038;'*+\\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
                return false;
            }
        }
        if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) {
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2) {
                return false;
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
                    return false;
                }
            }
        }
        return true;
    }

    public static function getUnencryptPassword($password)
    {
        return trim(self::privateKeyDecoding(@base64_encode(pack("H*", $password)), __DIR__ . "/../.." . config('app.rsa_key_add'), TRUE));
    }

    /**
     * 校验提交的密码，与时间的关系型
     * @param $username
     * @param $password
     * @param null $user
     * @return string 解密的密码
     */
    public static function checkPostUserNameAndPasswordHasUser(User $user, $password)
    {
        $decodedPassword = self::getUnencryptPassword($password);
        $unixTimestamp = substr($decodedPassword, strlen($decodedPassword) - 10);
        if (empty($user->getUserId()) || !self::isInt($user->getUserId())) {
            return false;
        }
        if (!self::checkValidPostUnixTimestampValid($unixTimestamp, $user)) {
            return false;
        }
        $md5password = $user->getUserPass();
        $data1 = $md5password . config('app.rsa_salt') . $unixTimestamp;
        $data2 = md5($data1) . $unixTimestamp;
        if ($data2 === $decodedPassword) {
            return $user;
        }
        return false;
    }

    /**
     * 校验提交的密码的时间是否合法
     * @param $unixTimestamp
     * @param $username
     * @param User|null $user
     * @return bool
     */
    public static function checkValidPostUnixTimestampValid($unixTimestamp, User $user)
    {
        if (!Functions::isInt($unixTimestamp)) {
            return false;
        }
        if (abs(time() - $unixTimestamp) > 900) {//与服务器差别均较大的unix值抛弃
            return false;
        }
        if (!$user->getIsLogin()) {
            $userUnixTimeStampInDB = DBHelper::getUserLastUseSessionTimeByName($user);
            if ($userUnixTimeStampInDB == false) {
                return false;
            }
            $userUnixTimeStampInDB = $userUnixTimeStampInDB->user_last_used_session_time;
        } else {
            $userUnixTimeStampInDB = $user->getLastUsedSessionTime();
        }
        if ($userUnixTimeStampInDB - $unixTimestamp < 600) {//数据库时间小于当前时间前移5分钟的，认可
            $writeData = ($userUnixTimeStampInDB < $unixTimestamp ? $unixTimestamp : $userUnixTimeStampInDB);
            DBHelper::updateUserLastUseSessionTimeByName($user, $writeData);
            return true;
        }
        return false;
    }

    /**
     * 私钥解密
     *
     * @param string 密文（二进制格式且base64编码）
     * @param string 密钥文件（.pem / .key）
     * @param string 密文是否来源于JS的RSA加密
     * @return string 明文
     */
    public static function privateKeyDecoding($crypttext, $fileName, $fromjs = FALSE)
    {
        $key_content = file_get_contents($fileName);
        $prikeyid = openssl_get_privatekey($key_content);
        $crypttext = base64_decode($crypttext);
        $padding = $fromjs ? OPENSSL_NO_PADDING : OPENSSL_PKCS1_PADDING;
        if (openssl_private_decrypt($crypttext, $sourcestr, $prikeyid, $padding)) {
            return $fromjs ? rtrim(strrev($sourcestr), "/0") : "" . $sourcestr;
        }
        return "";
    }

    public static function emailHidePassword($str)
    {
        $len = strlen($str);
        $strstart = substr($str, 0, 3);
        for ($i = 3; $i < $len; $i++) {
            $strstart = $strstart . "*";
        }
        return $strstart;
    }

    /**
     * 校验找回密码邮件Token是否正确
     * @param $key
     * @return bool
     */
    public static function isFindPasswordTokenValid($key)
    {
        return (preg_match("/^[A-Fa-f0-9]+$/u", $key) && strlen($key) == 40);
    }

    /**
     * 校验安全令备注是否正确
     * @param $postAuthName
     * @return bool
     */
    public static function isAuthNameValid($postAuthName)
    {
        $len = mb_strlen($postAuthName, "UTF-8");
        if ($len > 0 && $len < 13) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 注册到战网一键登录系统
     * @param $authenticator
     */
    public static function registerOnBlizzardOneButtonLogin(Authenticator $authenticator)
    {
        $serial = $authenticator->plain_serial();
        $code = $authenticator->code();
        switch (strtoupper($authenticator->region())) {
            case "CN":
                $url = "https://www.battlenet.com.cn/enrollment/pushButton";
                $data = "serial={$serial}&code={$code}&application=cn.bma&locale=zh-CN";
                self::_curlPost($url, $data);
                break;
            case "US":
            case "EU":
                $url = "https://us.battle.net/enrollment/pushButton";
                $data = "serial={$serial}&code={$code}&application=bma&locale=zh-CN";
                self::_curlPost($url, $data);
                break;
        }
    }


    /**
     * 校验安全令Code是否正确
     * @param $code
     * @return bool
     */
    public static function isAuthCodeValid($code)
    {
        return (strlen($code) == 4 && self::isInt($code));
    }

    /**
     * 校验安全令SECRET是否正确
     * @param $key
     * @return bool
     */
    public static function isAuthSecretValid($key)
    {
        return (preg_match("/^[A-Fa-f0-9]+$/u", $key) && strlen($key) == 40);
    }

    /**
     * 校验安全令还原码是否正确
     * @param $restore
     * @return bool
     */
    public static function isAuthRestoreCodeValid($restore)
    {
        return (preg_match("/^[A-Za-z0-9]+$/u", $restore) && strlen($restore) == 10);
    }
}