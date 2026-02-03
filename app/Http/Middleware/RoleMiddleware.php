<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
   
    public function handle(Request $request, Closure $next,$roleName)
    {
        if (!auth()->check()){
            return  response ()->json([
                'message'=>'can not use please login first'
            ]);
        }

        if (auth()->user()->role && auth()->user()->role->name==$roleName){
            
            return $next($request);
        }

        return response()->json([
            'message'=>"you don't have permistion to access this resource "

        ]);
    }
}
