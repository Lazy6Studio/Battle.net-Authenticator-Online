<?php
/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/15 0015
 * Time: 下午 16:22
 */

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use AuthUtils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthAddPageController extends Controller
{

    function __construct()
    {
        $this->middleware('base.check');
    }

    /**
     * 添加安全令页面
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function get(Request $request)
    {
        $user = Auth::user();
        if (!$user->getIsLogin()) {//未登录，跳登录
            $encodeUrl = urlencode(base64_encode("addAuth"));
            $encodeName = urlencode(base64_encode("添加安全令"));
            return redirect("login?from=$encodeUrl&fromName=$encodeName");
        }
        $authUtils = new AuthUtils();
        $authUtils->getAllAuth($user);
        if ($user->getUserDonated() == 1 && $authUtils->getAuthCount() >= config('app.auth_max_count_donated_user')) {
            return view('auth.add.error')->with('_USER', $user)->with("topNavValueText", "添加安全令")
                ->with("errorString", "您已经拥有" . $authUtils->getAuthCount() . "枚安全令，已到捐赠者账号的最大添加数量")
                ->with('jsString', "如要添加新的安全令，请到我的安全令中删除已有的安全令")
                ->with("jumpToUrl", "myAuthList");
        }
        if ($user->getUserDonated() == 0 && $authUtils->getAuthCount() >= config('app.auth_max_count_standard_user')) {
            return view('auth.add.error')->with('_USER', $user)->with("topNavValueText", "添加安全令")
                ->with("errorString", "您已经拥有" . $authUtils->getAuthCount() . "枚安全令，已到普通账号的最大添加数量")
                ->with('jsString', "如要添加新的安全令，请<a href=\"/donate\">捐赠</a>提升权限，或到我的安全令中删除已有的安全令")
                ->with("jumpToUrl", "myAuthList");
        }
        $captchaCodeUnix = time();
        $maxAuthCount = $user->getUserDonated() == 1 ? config('app.auth_max_count_donated_user') : config('app.auth_max_count_standard_user');
        return view('auth.add.index')->with('_USER', $user)->with("topNavValueText", "添加安全令")
            ->with("captchaCodeUnix", $captchaCodeUnix)->with("maxAuthCount", $maxAuthCount);
    }
}