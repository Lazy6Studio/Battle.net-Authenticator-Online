<?php
/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/19 0019
 * Time: 下午 15:34
 */

namespace App\Http\Controllers\StaticPage;


use App\Http\Controllers\Controller;

class DBErrorPageController extends Controller
{

    public function dbError()
    {
        $topNavValueText="数据库错误";
        return response(view('static.errors.dbError')->with('topNavValueText',$topNavValueText));
    }
}