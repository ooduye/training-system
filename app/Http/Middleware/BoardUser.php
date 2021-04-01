<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Auth;
use Illuminate\Http\Request;

class BoardUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->guard('api')->user();
        if ($user->profile->id == User::ROLE_BOARD) {
            return $next($request);
        } else {
            return response()->json(['message' => 'Unauthorized user'], 401);
        }
    }
}
