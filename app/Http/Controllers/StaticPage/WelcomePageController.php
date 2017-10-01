<?php
/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/12 0012
 * Time: 下午 13:03
 */

namespace App\Http\Controllers\StaticPage;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class WelcomePageController extends Controller
{

    public function __construct()
    {
        $this->middleware('base.check');
    }

    function __invoke()
    {
        return view('static.welcome.index')->with('_USER', Auth::user())->with("topNavValueText", "WELCOME");
    }
}