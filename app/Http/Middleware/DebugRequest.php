<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DebugRequest
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('Debug Request:', [
            'method' => $request->method(),
            'url' => $request->url(),
            'headers' => $request->headers->all(),
            'input' => $request->all(),
            'files' => $request->files->all(),
            'raw_body' => $request->getContent(),
        ]);
        return $next($request);
    }
}
