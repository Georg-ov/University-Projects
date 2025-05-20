<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CheckSubscriptions
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
        // Obtener la fecha actual
        $now = Carbon::now();

        // Buscar usuarios con suscripciones caducadas y actualizar su tipo de suscripción
        User::where('role_type', 'STUDENT')
            ->where('subscription_expiration_date', '<', $now)
            ->update(['subscription_type' => 'FREEMIUM']);

        Log::info("subscriptions updated.");
        return $next($request);
    }
}
