<x-filament-panels::page>
<div class="min-h-screen bg-white dark:bg-gray-900 py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">Selecionar Questões</h1>
            <p class="text-gray-600 dark:text-gray-400">Escolha as questões para adicionar ao seu simulado</p>
        </div>

        <div x-data="questoesSelector()" class="flex flex-col lg:flex-row gap-8">
            <!-- Coluna Esquerda: Pesquisa e Lista -->
            <div class="flex-1 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form method="GET" class="flex gap-2 items-center mb-4">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Buscar por ID ou parte da pergunta..." 
                            class="block w-full pl-14 pr-12 py-3 border border-gray-200 dark:border-gray-700 rounded-xl bg-gray-50 dark:bg-gray-800 focus:bg-white dark:focus:bg-gray-900 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                        />
                    </div>
                    <button 
                        type="submit" 
                        class="bg-primary-600 dark:bg-primary-500 hover:bg-primary-700 dark:hover:bg-primary-400 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-sm"
                    >
                        Buscar
                    </button>
                    @if(request('search'))
                        <a href="{{ url()->current() }}" class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-sm border border-gray-200 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700">
                            Limpar
                        </a>
                    @endif
                </form>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100/50 dark:from-gray-900 dark:to-gray-800/50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">ID</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Pergunta</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">Ações</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach($questoes as $questao)
                            <tr>
                                <td class="px-4 py-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                                        #{{ $questao->id }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    <p class="text-sm text-gray-900 dark:text-gray-100 leading-relaxed">{{ Str::limit($questao->pergunta, 80) }}</p>
                                </td>
                                <td class="px-4 py-2">
                                    <button type="button" class="text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-200 text-sm font-medium transition-colors duration-200" onclick="previewQuestion({{ $questao->id }})">Visualizar</button>
                                </td>
                                <td class="px-4 py-2 text-right">
                                    <button type="button" @click="addQuestao({ id: {{ $questao->id }}, pergunta: @js($questao->pergunta) })" class="bg-primary-600 dark:bg-primary-500 hover:bg-primary-700 dark:hover:bg-primary-400 text-white px-3 py-1 rounded text-xs font-medium">Adicionar</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($questoes->hasPages())
                <div class="mt-4 flex justify-center">
                    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                        {{ $questoes->appends(request()->query())->links() }}
                    </div>
                </div>
                @endif
            </div>

            <!-- Coluna Direita: Questões Selecionadas -->
            <div class="w-full lg:w-1/3 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 flex flex-col">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Questões Selecionadas</h2>
                <template x-if="selectedQuestoes.length === 0">
                    <p class="text-gray-500 dark:text-gray-400">Nenhuma questão selecionada.</p>
                </template>
                <ul class="divide-y divide-gray-200 dark:divide-gray-800 mb-4" x-show="selectedQuestoes.length > 0">
                    <template x-for="questao in selectedQuestoes" :key="questao.id">
                        <li class="py-2 flex items-center justify-between">
                            <span class="text-sm text-gray-900 dark:text-gray-100" x-text="'#' + questao.id + ' - ' + questao.pergunta.substring(0, 40) + (questao.pergunta.length > 40 ? '...' : '')"></span>
                            <button type="button" @click="removeQuestao(questao.id)" class="ml-2 text-red-600 hover:text-red-800 text-xs font-medium">Remover</button>
                        </li>
                    </template>
                </ul>
                <form method="POST" x-show="selectedQuestoes.length > 0">
                    @csrf
                    <template x-for="questao in selectedQuestoes" :key="questao.id">
                        <input type="hidden" name="questoes[]" :value="questao.id">
                    </template>
                    <button type="submit" class="w-full bg-primary-600 dark:bg-primary-500 hover:bg-primary-700 dark:hover:bg-primary-400 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 mt-2">Adicionar ao Simulado</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function questoesSelector() {
            return {
                selectedQuestoes: [],
                addQuestao(questao) {
                    if (!this.selectedQuestoes.some(q => q.id === questao.id)) {
                        this.selectedQuestoes.push(questao);
                    }
                },
                removeQuestao(id) {
                    this.selectedQuestoes = this.selectedQuestoes.filter(q => q.id !== id);
                }
            }
        }
        function previewQuestion(id) {
            // Implementar modal de preview da questão
            alert('Preview question: ' + id);
        }
    </script>

    <style>
        /* Custom checkbox styling */
        input[type="checkbox"]:checked {
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='m13.854 3.646-7.5 7.5a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6 10.293l7.146-7.147a.5.5 0 0 1 .708.708z'/%3e%3c/svg%3e");
        }

        /* Smooth transitions */
        * {
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Loading animation for buttons */
        .loading {
            position: relative;
            pointer-events: none;
        }

        .loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            margin: auto;
            border: 2px solid transparent;
            border-top-color: currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</x-filament-panels::page>