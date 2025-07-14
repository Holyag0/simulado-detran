<div>
    <div class="max-w-3xl mx-auto py-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Simulados Disponíveis</h1>
        <div class="space-y-6">
            @forelse($simulados as $simulado)
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow p-6 border border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $simulado->titulo }}</h2>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">{{ $simulado->descricao }}</p>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $simulado->questoes_count }} questões &bull; {{ $simulado->tempo_limite }} min</div>
                    </div>
                    <div class="flex flex-col gap-2 min-w-[180px]">
                        @php $tentativa = $tentativas[$simulado->id] ?? null; @endphp
                        @if(!$tentativa)
                            <a href="{{ route('aluno.simulado.quiz', $simulado->id) }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg font-medium text-center">Iniciar</a>
                        @elseif($tentativa->status === 'em_andamento')
                            <a href="{{ route('aluno.simulado.quiz', $simulado->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium text-center">Continuar</a>
                        @else
                            <span class="inline-block bg-green-100 text-green-800 px-4 py-2 rounded-lg font-medium text-center">Finalizado</span>
                            <a href="{{ route('aluno.simulado.quiz', $simulado->id) }}" class="bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-lg font-medium text-center mt-1">Ver Respostas</a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500">Nenhum simulado disponível no momento.</div>
            @endforelse
        </div>
    </div>
</div>
