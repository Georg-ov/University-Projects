<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;

class CheckDatabaseTables
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Lista de tablas importantes para verificar
        $tables = [
            'users', 'migrations', 'addresses', 'categories', 'comments', 
            'courses', 'credit_cards', 'failed_jobs', 'lessons', 
            'password_resets', 'personal_access_tokens'
        ];

        // Rutas que no deberían ser verificadas por este middleware
        $excludedRoutes = [
            'error.page'
        ];

        if (in_array(Route::currentRouteName(), $excludedRoutes)) {
            return $next($request);
        }

        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) {
                // Si falta alguna tabla, redirigir a la vista de error
                return redirect()->route('error.page');
            }
        }

        return $next($request);
    }
}
