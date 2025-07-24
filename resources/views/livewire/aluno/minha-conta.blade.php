<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Minha Conta</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Gerencie suas informações pessoais e configurações</p>
        </div>

        <div class="grid gap-8 lg:grid-cols-2">
            <!-- Informações do Perfil -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações do Perfil</h2>
                    
                    @if (session()->has('profile_updated'))
                        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-md">
                            <p class="text-green-800 dark:text-green-200">{{ session('profile_updated') }}</p>
                        </div>
                    @endif

                    <form wire:submit.prevent="updateProfile" class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nome completo *
                            </label>
                            <input type="text" 
                                   id="name" 
                                   wire:model="name"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Email *
                            </label>
                            <input type="email" 
                                   id="email" 
                                   wire:model="email"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="cpf" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                CPF
                            </label>
                            <input type="text" 
                                   id="cpf" 
                                   wire:model="cpf"
                                   placeholder="000.000.000-00"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                            @error('cpf') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="telefone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Telefone
                            </label>
                            <input type="text" 
                                   id="telefone" 
                                   wire:model="telefone"
                                   placeholder="(11) 99999-9999"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                            @error('telefone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="auto_escola" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Auto Escola
                            </label>
                            <input type="text" 
                                   id="auto_escola" 
                                   wire:model="auto_escola"
                                   placeholder="Nome da sua auto escola"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                            @error('auto_escola') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="pt-4">
                            <button type="submit" 
                                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Atualizar Perfil
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Alterar Senha -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Alterar Senha</h2>
                    
                    @if (session()->has('password_updated'))
                        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-md">
                            <p class="text-green-800 dark:text-green-200">{{ session('password_updated') }}</p>
                        </div>
                    @endif

                    <form wire:submit.prevent="updatePassword" class="space-y-4">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Senha atual *
                            </label>
                            <input type="password" 
                                   id="current_password" 
                                   wire:model="current_password"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                            @error('current_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nova senha *
                            </label>
                            <input type="password" 
                                   id="password" 
                                   wire:model="password"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Confirmar nova senha *
                            </label>
                            <input type="password" 
                                   id="password_confirmation" 
                                   wire:model="password_confirmation"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div class="pt-4">
                            <button type="submit" 
                                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                Alterar Senha
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Informações da Conta -->
        <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações da Conta</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Tipo de conta</div>
                    <div class="text-lg font-medium text-gray-900 dark:text-white capitalize">
                        {{ Auth::user()->tipo }}
                    </div>
                </div>
                
                <div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Membro desde</div>
                    <div class="text-lg font-medium text-gray-900 dark:text-white">
                        {{ Auth::user()->created_at->format('d/m/Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
