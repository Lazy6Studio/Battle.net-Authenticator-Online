<?php
/**
 * Created by PhpStorm.
 * User: Anh Lai
 * Date: 2017/5/11 0011
 * Time: 下午 12:48
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DBConnectionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            DB::connection()->getPdo();
            return $next($request);
        } catch (\Exception $e) {
            return redirect('dbError');
        }
    }
}