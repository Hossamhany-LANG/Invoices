<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle($request, Closure $next)
    {
        if($request->status == 'غير مفعل'){
            return abort(404 , 'not found');
        } else{
            return $next($request);
        }
    }
}

