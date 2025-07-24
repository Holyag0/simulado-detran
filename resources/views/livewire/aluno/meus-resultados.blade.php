<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Meus Resultados</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Histórico de simulados realizados</p>
        </div>

        @if($tentativas->count() > 0)
            <div class="grid gap-6">
                @foreach($tentativas as $tentativa)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                    {{ $tentativa->simulado->titulo }}
                                </h3>
                                
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                    <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                            {{ $tentativa->acertos }}
                                        </div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">Acertos</div>
                                    </div>
                                    
                                    <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div class="text-2xl font-bold text-red-600 dark:text-red-400">
                                            {{ $tentativa->erros }}
                                        </div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">Erros</div>
                                    </div>
                                    
                                    <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                            {{ number_format(($tentativa->acertos / ($tentativa->acertos + $tentativa->erros)) * 100, 1) }}%
                                        </div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">Aproveitamento</div>
                                    </div>
                                    
                                    <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div class="text-2xl font-bold 
                                            @if(($tentativa->acertos / ($tentativa->acertos + $tentativa->erros)) * 100 >= 70)
                                                text-green-600 dark:text-green-400
                                            @else
                                                text-red-600 dark:text-red-400
                                            @endif
                                        ">
                                            @if(($tentativa->acertos / ($tentativa->acertos + $tentativa->erros)) * 100 >= 70)
                                                ✓ Aprovado
                                            @else
                                                ✗ Reprovado
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">Status</div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                                    <div class="flex items-center space-x-4">
                                        <span>📅 {{ $tentativa->created_at->format('d/m/Y H:i') }}</span>
                                        <span>⏱️ {{ $tentativa->simulado->tempo_limite }} min</span>
                                        <span>📋 {{ $tentativa->acertos + $tentativa->erros }} questões</span>
                                    </div>
                                </div>
                            </div>

                            <div class="ml-6">
                                <div class="w-20 h-20 rounded-full flex items-center justify-center text-2xl font-bold
                                    @if(($tentativa->acertos / ($tentativa->acertos + $tentativa->erros)) * 100 >= 70)
                                        bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-400
                                    @else
                                        bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-400
                                    @endif
                                ">
                                    {{ number_format(($tentativa->acertos / ($tentativa->acertos + $tentativa->erros)) * 100, 0) }}%
                                </div>
                            </div>
                        </div>

                        <!-- Barra de progresso -->
                        <div class="mt-4">
                            <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
                                <span>Progresso do simulado</span>
                                <span>{{ $tentativa->acertos + $tentativa->erros }} de {{ $tentativa->simulado->numero_questoes }} questões</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full" 
                                     style="width: {{ (($tentativa->acertos + $tentativa->erros) / $tentativa->simulado->numero_questoes) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Estatísticas gerais -->
            <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Estatísticas Gerais</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">
                            {{ $tentativas->count() }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Simulados Realizados</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600 dark:text-green-400">
                            {{ $tentativas->where(function($t) { return ($t->acertos / ($t->acertos + $t->erros)) * 100 >= 70; })->count() }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Aprovações</div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                            {{ $tentativas->count() > 0 ? number_format($tentativas->avg(function($t) { return ($t->acertos / ($t->acertos + $t->erros)) * 100; }), 1) : 0 }}%
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Média Geral</div>
                    </div>
                </div>
            </div>
        @else
            <!-- Estado vazio -->
            <div class="text-center py-12">
                <div class="w-24 h-24 mx-auto mb-4 text-gray-400">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum resultado encontrado</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Você ainda não finalizou nenhum simulado.</p>
                <a href="{{ route('aluno.simulados') }}" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md transition-colors">
                    Fazer meu primeiro simulado
                </a>
            </div>
        @endif
    </div>
</div>
