<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class VerificarAutenticacao
{
    public function handle($request, Closure $next)
    {
        if (array_key_exists('logado', session()->all()) && session()->get('logado') === true) {
            return $next($request);
        }

        return redirect()->route('logar'); // Redireciona para a página de login caso não esteja autenticado.
    }
}