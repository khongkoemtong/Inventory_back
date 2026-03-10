<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSuperAdmin
{
 
// app/Http/Middleware/CheckSuperAdmin.php

public function handle($request, Closure $next, ...$roles)
{
    // ឆែកមើលថា User បាន Login និងមាន Relationship ទៅកាន់ Role ឬអត់
    if (auth()->check() && auth()->user()->role) {
        
        // ទាញយកឈ្មោះ Role មកធៀប (ឧទាហរណ៍៖ "SuperAdmin" ឬ "Admin")
        $userRoleName = auth()->user()->role->name; 

        if (in_array($userRoleName, $roles)) {
            return $next($request); 
        }
    }

    return response()->json(['message' => 'អ្នកគ្មានសិទ្ធិប្រើប្រាស់មុខងារនេះទេ!'], 403);
}
}