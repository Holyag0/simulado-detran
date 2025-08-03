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

        // Verificar se o usuário é aluno
        if (Auth::user()->tipo !== 'aluno') {
            // Redirecionar para o painel admin se for admin, ou para home se for outro tipo
            if (Auth::user()->tipo === 'admin') {
                return redirect('/admin')->with('error', 'Esta área é destinada apenas para alunos.');
            }
            return redirect('/')->with('error', 'Acesso negado. Esta área é destinada apenas para alunos.');
        }

        return $next($request);
    }
} 