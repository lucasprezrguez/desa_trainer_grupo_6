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
            return redirect()->route('login');
        }

        $user = Auth::user();
        $ruta_actual = $request->route()->getName(); 

        if (($user->roles === 'admin') ||
            ($user->roles === 'alumno' && $ruta_actual === 'trainer.aed') ||
            ($user->roles === 'profesor' && $ruta_actual != 'users.index')) {
            return $next($request); 
        }

        switch ($user->roles) {
            case 'admin':
                return redirect()->route('dashboard');
            case 'alumno':
                return redirect()->route('trainer.aed');
            case 'profesor':
                return redirect()->route('trainer.aed');
            default:
                abort(404);
        }
    }
}