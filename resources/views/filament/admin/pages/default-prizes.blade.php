<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header com informações importantes -->
        <div class="rounded-lg p-6 border border-purple-200">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Gerenciar Prêmios Padrão</h3>
                    <p class="text-sm text-gray-600 mt-1">
                        Configure os prêmios que aparecerão por padrão nos jogos. Estes prêmios serão usados quando não houver prêmios específicos configurados no jogo.
                    </p>
                </div>
            </div>
        </div>

        <!-- Cards informativos -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-blue-900">Prêmios em Dinheiro</h4>
                        <p class="text-xs text-blue-700">Valores creditados na carteira</p>
                    </div>
                </div>
            </div>

            <div class="border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-green-900">Produtos Físicos</h4>
                        <p class="text-xs text-green-700">Prêmios físicos para entrega</p>
                    </div>
                </div>
            </div>

            <div class="border border-purple-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-purple-900">Probabilidades</h4>
                        <p class="text-xs text-purple-700">Controle das chances de vitória</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulário principal -->
        <div class="shadow-sm rounded-lg border border-gray-200">
            <div class="p-6">
                {{ $this->form }}
            </div>
            
            <!-- Botão de salvar no final do formulário -->
            <div class="px-6 py-4 border-t border-gray-200 rounded-b-lg">
                <div class="flex justify-end">
                    <x-filament::button
                        wire:click="save"
                        color="success"
                        icon="heroicon-o-check"
                        size="lg"
                    >
                        Salvar Configurações
                    </x-filament::button>
                </div>
            </div>
        </div>

        <!-- Avisos importantes -->
        <div class="border border-amber-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-amber-800">
                        Informações Importantes
                    </h3>
                    <div class="mt-2 text-sm text-amber-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Os prêmios configurados aqui serão usados como padrão em todos os jogos</li>
                            <li>Prêmios específicos do jogo têm prioridade sobre os prêmios padrão</li>
                            <li>A soma das probabilidades não precisa ser 100%, o sistema normaliza automaticamente</li>
                            <li>Prêmios do tipo "dinheiro" creditam o valor na carteira do usuário</li>
                            <li>Prêmios do tipo "produto" são apenas informativos e requerem entrega manual</li>
                            <li>Imagens podem ser enviadas ou você pode usar URLs externas</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
