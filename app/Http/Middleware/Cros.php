<?php

namespace App\Http\Middleware;

use Closure;

class Cros
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->header('Access-Control-Allow-Origin', config('base.cros_access'));
        $response->header('Access-Control-Allow-Headers', 'Authorization,Origin, Content-Type, Cookie, Accept, multipart/form-data, application/json');
        $response->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, OPTIONS,DELETE');
        $response->header('Access-Control-Allow-Credentials', 'true');
        return $response;
    }
}
