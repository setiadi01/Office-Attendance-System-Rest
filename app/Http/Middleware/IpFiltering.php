<?php

namespace App\Http\Middleware;

use Closure;
use App\System\System;
use Illuminate\Support\Facades\Log;

class IpFiltering
{
    public function handle($request, Closure $next)
    {

        Log::debug($request->ip());
        if (!System::ipHandler($request->ip(), 'ALLOW')) {
            return response()->json([
                'status' => 'unauthorized',
                'response' => 'you don\'t have permission to access this url'
            ]);
        }

        return $next($request);
    }
}