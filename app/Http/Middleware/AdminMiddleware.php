<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle(Request $request, Closure $next)
{
    $user = $request->user();

// បើជា Admin (1) ឬ SuperAdmin (4) ទើបឱ្យទៅមុខទៀតបាន
    if ($user && ($user->role_id == 1 || $user->role_id == 4)) {
        return $next($request);
    }

    return response()->json(['message' => 'អ្នកមិនមានសិទ្ធិគ្រប់គ្រងផ្នែកនេះទេ'], 403);
}
}
