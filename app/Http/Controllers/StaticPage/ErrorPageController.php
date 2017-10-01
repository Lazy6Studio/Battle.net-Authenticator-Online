<?php
/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/12 0012
 * Time: 下午 13:16
 */

namespace App\Http\Controllers\StaticPage;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class ErrorPageController extends Controller
{

    public function __construct()
    {
        $this->middleware('base.check');
    }

    public function error()
    {
        return view('static.errors.index')->with('_USER', Auth::user())->with("topNavValueText", "服务器错误");
    }
    public function error404()
    {
        return view('static.errors.error404')->with('_USER', Auth::user())->with("topNavValueText", "404错误");
    }
    public function timeout()
    {
        return view('static.errors.timeout')->with('_USER', Auth::user())->with("topNavValueText", "超时错误");
    }
}