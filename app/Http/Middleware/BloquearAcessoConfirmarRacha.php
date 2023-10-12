<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BloquearAcessoConfirmarRacha
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Verifique se a rota acessada é "/confirmarRacha"
        if ($request->is('confirmarRacha')) {
            // Redirecione para outra página ou retorne uma resposta de erro
            return redirect('/'); // Ou você pode retornar uma resposta de erro aqui
        }
    
        return $next($request);
    }
}
