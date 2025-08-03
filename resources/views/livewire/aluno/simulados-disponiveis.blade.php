<div>
    <div class="max-w-3xl mx-auto py-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Simulados Disponíveis</h1>
            <button wire:click="atualizarDados" 
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Atualizar
            </button>
        </div>
        <div class="space-y-6">
            @forelse($simulados as $simulado)
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow p-6 border border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $simulado->titulo }}</h2>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">{{ $simulado->descricao }}</p>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $simulado->questoes_count }} questões &bull; {{ $simulado->tempo_limite }} min</div>
                    </div>
                    <div class="flex flex-col gap-2 min-w-[180px]">
                        @php 
                            $tentativa = $tentativas[$simulado->id] ?? null; 
                            $tentativaFinalizada = $tentativa && $tentativa->status === 'finalizada';
                        @endphp
                        
                        @if(!$tentativa)
                            <a href="{{ route('aluno.simulado.quiz', $simulado->id) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium text-center transition-colors">
                                Iniciar Simulado
                            </a>
                        @elseif($tentativa->status === 'em_andamento')
                            <a href="{{ route('aluno.simulado.quiz', $simulado->id) }}" 
                               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium text-center transition-colors">
                                Continuar Simulado
                            </a>
                        @elseif($tentativaFinalizada)
                            <div class="space-y-2">
                                <div class="flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="font-medium">Finalizado</span>
                                </div>
                                
                                <div class="flex gap-2">
                                    <a href="{{ route('aluno.simulado.resultado', $simulado->id) }}" 
                                       class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-lg font-medium text-center text-sm transition-colors">
                                        Ver Respostas
                                    </a>
                                    <a href="{{ route('aluno.resultados') }}?simulado={{ $simulado->id }}" 
                                       class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded-lg font-medium text-center text-sm transition-colors">
                                        Ver Desempenho
                                    </a>
                                </div>
                                
                                @if($tentativa->pontuacao >= 70)
                                    <div class="text-center">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Aprovado
                                        </span>
                                        <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                            {{ $tentativa->getAproveitamentoFormatado() }} (Nota: {{ $tentativa->getNotaFormatada() }})
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                            Reprovado
                                        </span>
                                        <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                            {{ $tentativa->getAproveitamentoFormatado() }} (Nota: {{ $tentativa->getNotaFormatada() }})
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500">Nenhum simulado disponível no momento.</div>
            @endforelse
        </div>
    </div>
</div>
