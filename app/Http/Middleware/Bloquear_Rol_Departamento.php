<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Bloquear_Rol_Departamento
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       
        if(auth()->check()){

            $user=auth()->user();

            if($user->role_id == 3 || $user->department_id == 1){

                abort(403, 'Acceso denegado: No tienes permiso para entrar a la página');

            }

        }

        return $next($request);

    }
}
