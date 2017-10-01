<?php
/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/11 0011
 * Time: 下午 12:11
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use AuthUtils;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;


class MyAuthListController extends Controller
{


    public function __construct()
    {
        $this->middleware('base.check');
    }

    function getCsv(Request $request)
    {
        $user = Auth::user();
        if (!$user->getIsLogin()) {
            $encodeUrl = urlencode(base64_encode("account"));
            $encodeName = urlencode(base64_encode("账号管理"));
            return redirect("login?from=$encodeUrl&fromName=$encodeName");
        }
        if (!($user->getUserRight() == User::USER_NORMAL)) {
            return redirect('account');
        }
        $authUtils = new AuthUtils();
        $authUtils->getAllAuth($user, false);
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="MyAuthList.csv"');
        try {
            $output = fopen('php://output', 'w');
            $list = "安全令名称,安全令序列号,安全令密钥,安全令还原码\r\n";
            foreach ($authUtils->getAuthList() as $auth) {
                $string = htmlspecialchars($auth->getAuthName()) . "," .
                    htmlspecialchars($auth->getAuthSerial()) . "," .
                    htmlspecialchars($auth->getAuthSecret()) . "," .
                    htmlspecialchars(strtoupper($auth->getAuthRestoreCode())) .
                    "\r\n";
                $list .= $string;
            }
            $list = "\xEF\xBB\xBF" . $list;
            fwrite($output, $list);
            fclose($output);
        } catch (\Exception $e) {
            return redirect('account');
        }
    }

    function getPage(Request $request)
    {
        $user = Auth::user();
        if (!$user->getIsLogin()) {
            $encodeUrl = urlencode(base64_encode("myAuthList"));
            $encodeName = urlencode(base64_encode("我的安全令"));
            return redirect("login?from=$encodeUrl&fromName=$encodeName");
        }
        $authUtils = new AuthUtils();
        $authUtils->getAllAuth($user, false);
        return view('auth.myAuthList.index')->with('_USER', $user)->with("topNavValueText", "我的安全令")->with("authUtils", $authUtils);
    }
}