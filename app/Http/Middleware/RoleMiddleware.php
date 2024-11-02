<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{

    public function handle(Request $request, Closure $next, $role): Response
    {



        $allowedRoles = explode('|', $role);

        if(!in_array($request->user()->role, $allowedRoles)) {
            return response()->json([
                'message' => 'Acesso negado',
            ], 403);
        }

        return $next($request);
    }
}
