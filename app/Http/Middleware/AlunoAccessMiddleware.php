<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AlunoAccessMiddleware
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

        // Permitir acesso para alunos e admins
        // Admins podem acessar a área de alunos para fins de teste e supervisão
        if (Auth::user()->tipo === 'aluno' || Auth::user()->tipo === 'admin') {
            return $next($request);
        }

        // Bloquear outros tipos de usuário (se houver)
        return redirect('/')->with('error', 'Acesso negado.');
    }
} 