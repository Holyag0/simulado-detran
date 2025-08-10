<x-filament-panels::page>
<div class="min-h-screen bg-white dark:bg-gray-900 py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">Selecionar Questões</h1>
            <p class="text-gray-600 dark:text-gray-400">Escolha as questões para adicionar ao seu simulado</p>
        </div>

        <div x-data="questoesSelector({{ $simulado->id ?? 'null' }})" class="flex flex-col lg:flex-row gap-8">
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
                <form method="POST" action="{{ route('filament.admin.resources.simulados.adicionar-questoes-modal.associate-questoes', $simulado ?? 1) }}" x-show="selectedQuestoes.length > 0">
                    @csrf
                    <template x-for="questao in selectedQuestoes" :key="questao.id">
                        <input type="hidden" name="questoes[]" :value="questao.id">
                    </template>
                    <button type="submit" class="w-full bg-primary-600 dark:bg-primary-500 hover:bg-primary-700 dark:hover:bg-primary-400 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 mt-2">Adicionar ao Simulado</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Visualização da Questão -->
    <div id="questaoModal" class="fixed inset-0 bg-black bg-opacity-60 z-50 hidden flex items-center justify-center p-4 backdrop-blur-sm">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto border border-gray-200 dark:border-gray-600">
            <!-- Header do Modal -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-600 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 rounded-t-2xl">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Visualizar Questão
                </h3>
                <button type="button" onclick="closeQuestaoModal()" class="text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors duration-200 p-1 rounded-full hover:bg-red-50 dark:hover:bg-red-900/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Conteúdo do Modal -->
            <div class="p-6">
                <div id="questaoModalContent" class="space-y-6">
                    <!-- Loading -->
                    <div id="questaoModalLoading" class="flex items-center justify-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
                    </div>
                    
                    <!-- Conteúdo da Questão -->
                    <div id="questaoModalData" class="hidden space-y-6">
                        <!-- ID e Categoria -->
                        <div class="flex items-center gap-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-lg">
                                #<span id="questaoId"></span>
                            </span>
                            <span id="questaoCategoriaBadge" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium text-white shadow-md">
                                <span id="questaoCategoria"></span>
                            </span>
                        </div>
                        
                        <!-- Pergunta -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 p-4 rounded-xl border-l-4 border-blue-500">
                            <h4 class="text-sm font-semibold text-blue-700 dark:text-blue-300 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Pergunta
                            </h4>
                            <p id="questaoPergunta" class="text-gray-900 dark:text-white text-base leading-relaxed font-medium"></p>
                        </div>
                        
                        <!-- Alternativas -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Alternativas
                            </h4>
                            <div class="space-y-3">
                                <div id="alternativaA" class="flex items-center gap-3 p-4 rounded-xl border-2 border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500 transition-colors bg-white dark:bg-gray-700">
                                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-bold flex items-center justify-center shadow-md">A</span>
                                    <span id="questaoAlternativaA" class="text-gray-900 dark:text-gray-400 font-medium"></span>
                                </div>
                                <div id="alternativaB" class="flex items-center gap-3 p-4 rounded-xl border-2 border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500 transition-colors bg-white dark:bg-gray-700">
                                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-r from-green-500 to-green-600 text-white text-sm font-bold flex items-center justify-center shadow-md">B</span>
                                    <span id="questaoAlternativaB" class="text-gray-900 dark:text-gray-400 font-medium"></span>
                                </div>
                                <div id="alternativaC" class="flex items-center gap-3 p-4 rounded-xl border-2 border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500 transition-colors bg-white dark:bg-gray-700">
                                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-r from-yellow-500 to-yellow-600 text-white text-sm font-bold flex items-center justify-center shadow-md">C</span>
                                    <span id="questaoAlternativaC" class="text-gray-900 dark:text-gray-400 font-medium"></span>
                                </div>
                                <div id="alternativaD" class="flex items-center gap-3 p-4 rounded-xl border-2 border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500 transition-colors bg-white dark:bg-gray-700">
                                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-r from-red-500 to-red-600 text-white text-sm font-bold flex items-center justify-center shadow-md">D</span>
                                    <span id="questaoAlternativaD" class="text-gray-900 dark:text-gray-400 font-medium"></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Resposta Correta -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 p-4 rounded-xl border-l-4 border-green-500">
                            <h4 class="text-sm font-semibold text-green-700 dark:text-green-300 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Resposta Correta
                            </h4>
                            <span id="questaoRespostaCorreta" class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-gradient-to-r from-green-500 to-emerald-500 text-white shadow-lg">
                                Alternativa <span id="questaoRespostaCorretaTexto"></span>
                            </span>
                        </div>
                        
                        <!-- Explicação -->
                        <div id="questaoExplicacaoContainer" class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 p-4 rounded-xl border-l-4 border-purple-500">
                            <h4 class="text-sm font-semibold text-purple-700 dark:text-purple-300 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Explicação
                            </h4>
                            <p id="questaoExplicacao" class="text-white text-sm leading-relaxed"></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer do Modal -->
            <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-600 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-b-2xl">
                <button type="button" onclick="closeQuestaoModal()" class="px-6 py-2.5 text-white bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600 rounded-lg font-semibold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Fechar
                </button>
            </div>
        </div>
    </div>

    <script>
        function questoesSelector(simuladoId) {
            if (!simuladoId) {
                console.error('Simulado ID não encontrado');
                return {
                    selectedQuestoes: [],
                    init() {},
                    addQuestao() {},
                    removeQuestao() {},
                    save() {}
                };
            }
            const storageKey = `simulado_${simuladoId}_questoesSelecionadas`;
            return {
                selectedQuestoes: [],
                init() {
                    const saved = localStorage.getItem(storageKey);
                    if (saved) {
                        this.selectedQuestoes = JSON.parse(saved);
                    }
                },
                addQuestao(questao) {
                    if (!this.selectedQuestoes.some(q => q.id === questao.id)) {
                        this.selectedQuestoes.push(questao);
                        this.save();
                        
                        // Mostrar feedback visual
                        const button = event.target;
                        const originalText = button.textContent;
                        button.textContent = '✓ Adicionada';
                        button.classList.add('bg-green-500', 'dark:bg-green-400');
                        button.classList.remove('bg-primary-600', 'dark:bg-primary-500');
                        
                        setTimeout(() => {
                            button.textContent = originalText;
                            button.classList.remove('bg-green-500', 'dark:bg-green-400');
                            button.classList.add('bg-primary-600', 'dark:bg-primary-500');
                        }, 1500);
                    } else {
                        // Mostrar feedback de que já foi adicionada
                        const button = event.target;
                        const originalText = button.textContent;
                        button.textContent = 'Já adicionada';
                        button.classList.add('bg-gray-500', 'dark:bg-gray-400');
                        button.classList.remove('bg-primary-600', 'dark:bg-primary-500');
                        
                        setTimeout(() => {
                            button.textContent = originalText;
                            button.classList.remove('bg-gray-500', 'dark:bg-gray-400');
                            button.classList.add('bg-primary-600', 'dark:bg-primary-500');
                        }, 1500);
                    }
                },
                removeQuestao(id) {
                    this.selectedQuestoes = this.selectedQuestoes.filter(q => q.id !== id);
                    this.save();
                },
                save() {
                    localStorage.setItem(storageKey, JSON.stringify(this.selectedQuestoes));
                }
            }
        }
        
        function previewQuestion(id) {
            // Mostrar modal e loading
            const modal = document.getElementById('questaoModal');
            const loading = document.getElementById('questaoModalLoading');
            const data = document.getElementById('questaoModalData');
            
            modal.classList.remove('hidden');
            loading.classList.remove('hidden');
            data.classList.add('hidden');
            
            // Fazer requisição AJAX para buscar dados da questão
            fetch(`/admin/questoes/${id}/dados`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro ao buscar dados da questão');
                    }
                    return response.json();
                })
                .then(questao => {
                    // Preencher dados no modal
                    document.getElementById('questaoId').textContent = questao.id;
                    document.getElementById('questaoPergunta').textContent = questao.pergunta;
                    document.getElementById('questaoAlternativaA').textContent = questao.alternativa_a;
                    document.getElementById('questaoAlternativaB').textContent = questao.alternativa_b;
                    document.getElementById('questaoAlternativaC').textContent = questao.alternativa_c;
                    document.getElementById('questaoAlternativaD').textContent = questao.alternativa_d;
                    
                    // Configurar categoria com cor dinâmica
                    const categoriaBadge = document.getElementById('questaoCategoriaBadge');
                    const categoriaText = document.getElementById('questaoCategoria');
                    
                    if (questao.categoria) {
                        categoriaText.textContent = questao.categoria.nome;
                        
                        // Aplicar cor da categoria se existir
                        if (questao.categoria.cor) {
                            categoriaBadge.style.background = questao.categoria.cor;
                        } else {
                            // Cor padrão se não houver cor definida
                            categoriaBadge.style.background = 'linear-gradient(to right, #3b82f6, #1d4ed8)';
                        }
                    } else {
                        categoriaText.textContent = 'Sem categoria';
                        categoriaBadge.style.background = 'linear-gradient(to right, #6b7280, #4b5563)';
                    }
                    
                    // Configurar resposta correta
                    const respostaCorreta = questao.resposta_correta.toUpperCase();
                    document.getElementById('questaoRespostaCorretaTexto').textContent = respostaCorreta;
                    
                    // Destacar a alternativa correta
                    const alternativas = ['A', 'B', 'C', 'D'];
                    alternativas.forEach(letra => {
                        const alternativaElement = document.getElementById(`alternativa${letra}`);
                        if (letra === respostaCorreta) {
                            alternativaElement.classList.add('ring-2', 'ring-green-500', 'ring-opacity-50', 'border-green-500');
                            alternativaElement.style.background = 'linear-gradient(to right, #f0fdf4, #dcfce7)';
                            // Mudar cor do texto para verde escuro
                            const textoElement = alternativaElement.querySelector('span');
                            if (textoElement) {
                                textoElement.style.color = '#166534'; // Verde escuro
                                textoElement.style.fontWeight = 'bold';
                            }
                        } else {
                            alternativaElement.classList.remove('ring-2', 'ring-green-500', 'ring-opacity-50', 'border-green-500');
                            alternativaElement.style.background = '';
                            // Restaurar cor original do texto
                            const textoElement = alternativaElement.querySelector('span');
                            if (textoElement) {
                                textoElement.style.color = '';
                                textoElement.style.fontWeight = '';
                            }
                        }
                    });
                    
                    // Mostrar/ocultar explicação
                    const explicacaoContainer = document.getElementById('questaoExplicacaoContainer');
                    const explicacao = document.getElementById('questaoExplicacao');
                    
                    if (questao.explicacao && questao.explicacao.trim() !== '') {
                        explicacao.textContent = questao.explicacao;
                        explicacaoContainer.classList.remove('hidden');
                    } else {
                        explicacaoContainer.classList.add('hidden');
                    }
                    
                    // Esconder loading e mostrar dados
                    loading.classList.add('hidden');
                    data.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao carregar dados da questão: ' + error.message);
                    closeQuestaoModal();
                });
        }
        
        function closeQuestaoModal() {
            const modal = document.getElementById('questaoModal');
            modal.classList.add('hidden');
        }
        
        // Fechar modal ao clicar fora dele
        document.getElementById('questaoModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeQuestaoModal();
            }
        });
        
        // Fechar modal com tecla ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeQuestaoModal();
            }
        });
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

        /* Modal animations */
        #questaoModal {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #questaoModal.hidden {
            opacity: 0;
            pointer-events: none;
            transform: scale(0.95);
        }

        #questaoModal:not(.hidden) {
            opacity: 1;
            pointer-events: auto;
            transform: scale(1);
        }

        #questaoModal > div {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #questaoModal.hidden > div {
            transform: translateY(20px);
        }

        #questaoModal:not(.hidden) > div {
            transform: translateY(0);
        }

        /* Loading animation */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        /* Hover effects for alternatives */
        #alternativaA:hover, #alternativaB:hover, #alternativaC:hover, #alternativaD:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Correct answer highlight animation */
        @keyframes pulse-green {
            0%, 100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
            50% { box-shadow: 0 0 0 10px rgba(34, 197, 94, 0); }
        }

        .ring-green-500 {
            animation: pulse-green 2s infinite;
        }
    </style>
</x-filament-panels::page> 