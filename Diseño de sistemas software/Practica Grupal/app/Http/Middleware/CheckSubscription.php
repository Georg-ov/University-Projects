<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Checkamos el campo subscription_type
        if ($user && $user->subscription_type == 'PREMIUM') {
            Log::info('Usuario ' . $user->email . ' ya es miembro premium');
            return redirect()->route('mainpage')->with('message', 'You are already a premium member.');
        }
        Log::info('Usuario ' . ($user ? $user->email : 'desconocido') . ' no es miembro premium' . $user->subscription_type . $user);
        return $next($request);
    }
}
