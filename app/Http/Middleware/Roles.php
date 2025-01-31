<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
// use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Roles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return response('Unauthorized.', 403); 
        }

        $user = Auth::user();
        $ruta_actual = $request->route()->getName(); 

        if (($user->roles === 'admin' && $ruta_actual === 'dashboard') ||
            ($user->roles === 'alumno' && $ruta_actual === 'trainer.aed') ||
            ($user->roles === 'profesor' && $ruta_actual === 'dashboard')) {
            return $next($request); 
        }

        switch ($user->roles) {
            case 'admin':
                return redirect()->route('dashboard');
            case 'alumno':
                return redirect()->route('trainer.aed');
            case 'profesor':
                return redirect()->route('dashboard');
            default:
                abort(404);
        }
    }
}