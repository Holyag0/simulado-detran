<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-slate-900 dark:to-gray-800">
    <div class="container mx-auto px-4 py-6 max-w-4xl">
        
        {{-- Header com Progresso --}}
        @if(!$finalizado && $total > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 mb-8 border border-gray-100 dark:border-gray-700">
                {{-- Barra de Progresso --}}
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-3">
                        <h1 class="text-xl font-bold text-gray-800 dark:text-gray-100">Simulado em Andamento</h1>
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full">
                            {{ $indice + 1 }} de {{ $total }}
                        </span>
                    </div>
                    @php
                        $progresso = (($indice + 1) / $total) * 100;
                        $respondidas = count(array_filter($statusQuestoes ?? [], fn($status) => $status === 'respondida'));
                    @endphp
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 mb-3 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-3 rounded-full transition-all duration-500 ease-out shadow-sm" 
                             style="width: {{ $progresso }}%">
                        </div>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                        <span>{{ $respondidas }} questões respondidas</span>
                        <span>{{ round($progresso, 1) }}% concluído</span>
                    </div>
                </div>

                {{-- Grid de Navegação das Questões --}}
                <div class="grid grid-cols-10 sm:grid-cols-12 md:grid-cols-15 lg:grid-cols-20 gap-2">
                    @foreach(range(0, $total - 1) as $i)
                        @php
                            $status = $statusQuestoes[$i] ?? 'nao_respondida';
                            $isAtual = $i === $indice;
                            $baseClasses = 'group relative w-10 h-10 flex items-center justify-center rounded-xl font-semibold text-sm border-2 transition-all duration-300 transform hover:scale-105 cursor-pointer ';
                            
                            if ($isAtual) 
                                $classes = $baseClasses . 'border-blue-500 bg-blue-500 text-white shadow-lg shadow-blue-200 dark:shadow-blue-900/50 ring-4 ring-blue-200 dark:ring-blue-800 ';
                            elseif ($status === 'respondida') 
                                $classes = $baseClasses . 'border-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 ';
                            elseif ($status === 'pulado') 
                                $classes = $baseClasses . 'border-amber-400 bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 hover:bg-amber-100 dark:hover:bg-amber-900/50 ';
                            else 
                                $classes = $baseClasses . 'border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 hover:border-gray-400 dark:hover:border-gray-500 ';
                        @endphp
                        <button wire:click="irParaQuestao({{ $i }})" 
                                class="{{ $classes }}"
                                title="Questão {{ $i+1 }} - {{ ucfirst(str_replace('_', ' ', $status)) }}">
                            {{ $i + 1 }}
                            @if($status === 'respondida')
                                <div class="absolute -top-1 -right-1 w-4 h-4 bg-emerald-500 rounded-full flex items-center justify-center">
                                    <svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            @endif
                            @if($status === 'pulado')
                                <div class="absolute -top-1 -right-1 w-4 h-4 bg-amber-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs font-bold">!</span>
                                </div>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Conteúdo Principal --}}
        @if($finalizado)
            {{-- Tela de Resultado --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="bg-gradient-to-r from-emerald-500 to-teal-600 p-8 text-center text-white">
                    <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold mb-2">Simulado Concluído!</h2>
                    <p class="text-xl opacity-90">Parabéns pela dedicação nos estudos</p>
                </div>
                
                @if($resultado)
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
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
                        </div>
                        
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
                @endif
            </div>

        @elseif($questaoAtual)
            {{-- Questão Atual --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                {{-- Cabeçalho da Questão --}}
                <div class="bg-gradient-to-r from-slate-100 to-gray-100 dark:from-gray-700 dark:to-gray-600 px-8 py-6 border-b border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                {{ $indice + 1 }}
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">Questão {{ $indice + 1 }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">de {{ $total }} questões</p>
                            </div>
                        </div>
                        @php
                            $status = $statusQuestoes[$indice] ?? 'nao_respondida';
                        @endphp
                        @if($status === 'respondida')
                            <span class="inline-flex items-center gap-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 px-3 py-1 rounded-full text-sm font-medium">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Respondida
                            </span>
                        @elseif($status === 'pulado')
                            <span class="inline-flex items-center gap-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 px-3 py-1 rounded-full text-sm font-medium">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                Pulada
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Pergunta --}}
                <div class="p-8">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-8 leading-relaxed">
                        {{ $questaoAtual['pergunta'] }}
                    </h2>

                    {{-- Alternativas --}}
                    <div class="space-y-4 mb-8">
                        @foreach(['a', 'b', 'c', 'd'] as $alt)
                            @php
                                $marcada = isset($respostas[$questaoAtual['id']]) && $respostas[$questaoAtual['id']] === $alt;
                                $baseClasses = 'group relative block w-full text-left p-6 rounded-2xl border-2 transition-all duration-300 transform hover:scale-[1.02] cursor-pointer ';
                                
                                if($marcada) 
                                    $altClasses = $baseClasses . 'border-blue-500 bg-blue-50 dark:bg-blue-900/30 text-blue-900 dark:text-blue-100 shadow-lg shadow-blue-200/50 dark:shadow-blue-900/50 ring-2 ring-blue-200 dark:ring-blue-800 ';
                                else 
                                    $altClasses = $baseClasses . 'border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:border-gray-300 dark:hover:border-gray-500 hover:shadow-md ';
                            @endphp
                            <button wire:click="responder('{{ $alt }}')" class="{{ $altClasses }}">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full border-2 {{ $marcada ? 'border-blue-500 bg-blue-500' : 'border-gray-300 dark:border-gray-500 group-hover:border-gray-400' }} flex items-center justify-center font-bold text-sm {{ $marcada ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}">
                                        {{ strtoupper($alt) }}
                                    </div>
                                    <div class="flex-1 text-base leading-relaxed">
                                        {{ $questaoAtual['alternativa_' . $alt] }}
                                    </div>
                                    @if($marcada)
                                        <div class="flex-shrink-0 text-blue-500">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </button>
                        @endforeach
                    </div>

                    {{-- Navegação --}}
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6 border-t border-gray-200 dark:border-gray-600">
                        <button wire:click="anterior" 
                                @if($indice === 0) disabled @endif 
                                class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 transform hover:scale-105">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Anterior
                        </button>
                        
                        <div class="flex gap-3">
                            @if($indice < $total - 1)
                                <button wire:click="pular" 
                                        class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold transition-all duration-300 bg-amber-500 text-white hover:bg-amber-600 transform hover:scale-105 shadow-lg hover:shadow-xl disabled:opacity-50" 
                                        @if(($statusQuestoes[$indice] ?? null) === 'respondida') disabled @endif>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                        <path fill-rule="evenodd" d="M3.293 15.707a1 1 0 010-1.414L7.586 10 3.293 5.707a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Pular
                                </button>
                                <button wire:click="proxima" 
                                        class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold transition-all duration-300 bg-gradient-to-r from-blue-600 to-indigo-600 text-white hover:from-blue-700 hover:to-indigo-700 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                    Próxima
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            @else
                                <button wire:click="finalizar" 
                                        class="inline-flex items-center gap-2 px-8 py-3 rounded-xl font-semibold text-lg transition-all duration-300 bg-gradient-to-r from-emerald-600 to-green-600 text-white hover:from-emerald-700 hover:to-green-700 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Finalizar Simulado
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        @else
            {{-- Estado Vazio --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-12 text-center border border-gray-100 dark:border-gray-700">
                <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">Nenhuma questão encontrada</h3>
                <p class="text-gray-500 dark:text-gray-400">Verifique se o simulado foi carregado corretamente.</p>
            </div>
        @endif
    </div>
</div>