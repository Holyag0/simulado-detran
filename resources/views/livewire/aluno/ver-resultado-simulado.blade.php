<div>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-slate-900 dark:to-gray-800">
        <div class="container mx-auto px-4 py-6 max-w-4xl">
            
            {{-- Header do Simulado --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 mb-6 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $simulado->titulo }}</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $simulado->descricao }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">
                            Finalizado em {{ $tentativa->finalizado_em->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-600 dark:text-gray-400">Tempo Utilizado</div>
                        <div class="text-lg font-bold text-gray-800 dark:text-gray-100">
                            @php
                                $totalSegundos = $tentativa->iniciado_em->diffInSeconds($tentativa->finalizado_em);
                                $minutos = floor($totalSegundos / 60);
                                $segundos = $totalSegundos % 60;
                            @endphp
                            {{ $minutos }}:{{ str_pad($segundos, 2, '0', STR_PAD_LEFT) }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Resultados --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="bg-gradient-to-r from-emerald-500 to-teal-600 p-8 text-center text-white">
                    <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold mb-2">Resultado do Simulado</h2>
                    <p class="text-xl opacity-90">Revisão completa das suas respostas</p>
                </div>
                
                <div class="p-8">
                    {{-- Métricas Principais --}}
                    <div class="mb-8" x-data="{ isExpanded: false }">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl p-6 border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                            <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2H4zm3 2a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Estatísticas Detalhadas</h3>
                                        <p class="text-gray-600 dark:text-gray-400">Métricas principais e desempenho por categoria</p>
                                    </div>
                                </div>
                                
                                <!-- Toggle Button -->
                                <button 
                                    @click="isExpanded = !isExpanded"
                                    class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white dark:bg-gray-700 shadow-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200"
                                    :class="{ 'rotate-180': isExpanded }"
                                >
                                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Estatísticas Content -->
                        <div x-show="isExpanded" 
                             x-transition:enter="transition-all duration-300 ease-out"
                             x-transition:enter-start="opacity-0 max-h-0"
                             x-transition:enter-end="opacity-100 max-h-96"
                             x-transition:leave="transition-all duration-300 ease-in"
                             x-transition:leave-start="opacity-100 max-h-96"
                             x-transition:leave-end="opacity-0 max-h-0"
                             class="overflow-hidden">
                            <div class="mt-6 space-y-8">
                                {{-- Métricas Principais --}}
                                <div>
                                    <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-6">Métricas Principais</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                        <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl p-6 text-center border border-emerald-200 dark:border-emerald-800">
                                            <div class="text-3xl font-bold text-emerald-600 dark:text-emerald-400 mb-2">{{ $resultado['acertos'] }}</div>
                                            <div class="text-sm font-medium text-emerald-700 dark:text-emerald-300">Acertos</div>
                                        </div>
                                        <div class="bg-red-50 dark:bg-red-900/20 rounded-2xl p-6 text-center border border-red-200 dark:border-red-800">
                                            <div class="text-3xl font-bold text-red-600 dark:text-red-400 mb-2">{{ $resultado['erros'] }}</div>
                                            <div class="text-sm font-medium text-red-700 dark:text-red-300">Erros</div>
                                        </div>
                                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl p-6 text-center border border-blue-200 dark:border-blue-800">
                                            <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2">{{ $resultado['percentual'] }}%</div>
                                            <div class="text-sm font-medium text-blue-700 dark:text-blue-300">Aproveitamento</div>
                                        </div>
                                        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-2xl p-6 text-center border border-purple-200 dark:border-purple-800">
                                            <div class="text-3xl font-bold text-purple-600 dark:text-purple-400 mb-2">{{ $resultado['nota'] }}</div>
                                            <div class="text-sm font-medium text-purple-700 dark:text-purple-300">Nota (0-10)</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Estatísticas por Categoria --}}
                                @if($estatisticasCategoria->count() > 0)
                                    <div>
                                        <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-6">Desempenho por Categoria</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                            @foreach($estatisticasCategoria as $estatistica)
                                                <div class="bg-white dark:bg-gray-700 rounded-2xl p-6 border border-gray-200 dark:border-gray-600 shadow-lg">
                                                    <div class="flex items-center justify-between mb-4">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-4 h-4 rounded-full" style="background-color: {{ $estatistica['cor'] }}"></div>
                                                            <span class="font-semibold text-gray-800 dark:text-gray-100">{{ $estatistica['categoria'] }}</span>
                                                        </div>
                                                        <div class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $estatistica['percentual'] }}%</div>
                                                    </div>
                                                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-3">
                                                        <span>{{ $estatistica['acertos'] }}/{{ $estatistica['total'] }} acertos</span>
                                                        <span>{{ $estatistica['erros'] }} erros</span>
                                                    </div>
                                                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-3">
                                                        <div class="h-3 rounded-full transition-all duration-300" 
                                                             style="width: {{ $estatistica['percentual'] }}%; background-color: {{ $estatistica['cor'] }};">
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    {{-- Seção de Revisão das Questões --}}
                    @if(count($resultado['respostas_detalhadas']) > 0)
                        <div class="mb-8">
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">Revisão das Questões</h3>
                            <div class="space-y-6">
                                @foreach($resultado['respostas_detalhadas'] as $index => $resposta)
                                    @php
                                        $questao = $resposta['questao'];
                                        $respostaEscolhida = $resposta['resposta_escolhida'];
                                        $respostaCorreta = $resposta['resposta_correta'];
                                        $correta = $resposta['correta'];
                                    @endphp
                                    
                                    <div class="bg-white dark:bg-gray-700 rounded-2xl p-6 border-2 {{ $correta ? 'border-emerald-200 dark:border-emerald-800' : 'border-red-200 dark:border-red-800' }}">
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm {{ $correta ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white' }}">
                                                    {{ $index + 1 }}
                                                </div>
                                                <div>
                                                    <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                                                        Questão {{ $index + 1 }}
                                                    </h4>
                                                    <div class="flex items-center gap-2 mt-1">
                                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium {{ $correta ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300' : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300' }}">
                                                            @if($correta)
                                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                                </svg>
                                                                Correta
                                                            @else
                                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                                </svg>
                                                                Incorreta
                                                            @endif
                                                        </span>
                                                        @if($questao->categoria)
                                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium" style="background-color: {{ $questao->categoria->cor }}20; color: {{ $questao->categoria->cor }};">
                                                                <div class="w-2 h-2 rounded-full" style="background-color: {{ $questao->categoria->cor }};"></div>
                                                                {{ $questao->categoria->nome }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $questao->pergunta }}</p>
                                        </div>
                                        
                                        <div class="space-y-2 mb-4">
                                            @foreach(['a', 'b', 'c', 'd'] as $alt)
                                                @php
                                                    $isEscolhida = $respostaEscolhida === $alt;
                                                    $isCorreta = $respostaCorreta === $alt;
                                                    $baseClasses = 'flex items-center gap-3 p-3 rounded-lg border-2 ';
                                                    
                                                    if ($isCorreta) 
                                                        $classes = $baseClasses . 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300';
                                                    elseif ($isEscolhida && !$correta) 
                                                        $classes = $baseClasses . 'border-red-500 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300';
                                                    else 
                                                        $classes = $baseClasses . 'border-gray-200 dark:border-gray-600 text-gray-600 dark:text-gray-400';
                                                @endphp
                                                <div class="{{ $classes }}">
                                                    <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center font-bold text-sm {{ $isCorreta ? 'border-emerald-500 bg-emerald-500 text-white' : ($isEscolhida && !$correta ? 'border-red-500 bg-red-500 text-white' : 'border-gray-300 dark:border-gray-500 text-gray-500 dark:text-gray-400') }}">
                                                        {{ strtoupper($alt) }}
                                                    </div>
                                                    <span class="flex-1">{{ $questao->{'alternativa_' . $alt} }}</span>
                                                    @if($isCorreta)
                                                        <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                        </svg>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        @if($questao->explicacao)
                                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                                                <h5 class="font-semibold text-blue-800 dark:text-blue-200 mb-2">Explicação:</h5>
                                                <p class="text-blue-700 dark:text-blue-300 text-sm">{{ $questao->explicacao }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <div class="text-center">
                        <a href="/aluno/simulados" 
                           class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-4 rounded-2xl font-semibold text-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                            </svg>
                            Voltar para Simulados
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .rotate-180 {
        transform: rotate(180deg);
    }
    </style>
</div>
