<?php

namespace App\Http\Middleware;

use App\Models\Reseller;
use Closure;
use Illuminate\Http\Request;

class ResellerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $reseller = Reseller::getAllItems(); // Mengambil data dari model
        view()->share('reseller', $reseller);

        return $next($request);
    }
}
