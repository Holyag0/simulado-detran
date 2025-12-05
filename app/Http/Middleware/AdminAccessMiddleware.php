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
            // Para requisições AJAX/API, retornar 401 em vez de redirect
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Não autenticado'], 401);
            }
            return redirect()->route('login');
        }

        // Verificar se o usuário é admin
        if (Auth::user()->tipo !== 'admin') {
            // Para requisições AJAX/API, retornar 403 em vez de redirect
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Acesso negado. Apenas administradores podem acessar o painel administrativo.'], 403);
            }
            // Se não for admin, redirecionar para a página inicial com mensagem de erro
            return redirect('/')->with('error', 'Acesso negado. Apenas administradores podem acessar o painel administrativo.');
        }

        // Se for admin, permitir acesso
        return $next($request);
    }
} 