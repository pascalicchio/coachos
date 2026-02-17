<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrganizationScope
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->organization_id) {
            return response()->json(['error' => 'No organization associated'], 403);
        }

        return $next($request);
    }
}
