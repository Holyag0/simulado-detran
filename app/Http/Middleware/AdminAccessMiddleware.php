<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar se o usuário está autenticado
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Verificar se o usuário é admin
        if (Auth::user()->tipo !== 'admin') {
            // Redirecionar para a página inicial com mensagem de erro
            return redirect('/')->with('error', 'Acesso negado. Apenas administradores podem acessar o painel administrativo.');
        }

        return $next($request);
    }
} 