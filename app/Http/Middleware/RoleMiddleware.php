<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
   
   public function handle(Request $request, Closure $next, ...$roles)
{
    $user = $request->user();

    // ឆែកមើលឈ្មោះ Role របស់ User (ត្រូវប្រាកដថា Relationship 'role' ក្នុង UserModel ដើរត្រឹមត្រូវ)
    if ($user && in_array($user->role->name, $roles)) {
        return $next($request);
    }

    return response()->json([
        'message' => 'you don\'t have permission to access this resource'
    ], 403);
}
}
