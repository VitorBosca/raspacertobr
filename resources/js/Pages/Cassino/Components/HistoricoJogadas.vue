<template>
    <div class="bg-gray-900 text-white p-6 rounded-lg">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Histórico de Jogadas</h2>
            <button 
                @click="refreshHistory" 
                :disabled="isLoading"
                class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded-lg transition duration-200 disabled:opacity-50"
            >
                {{ isLoading ? 'Carregando...' : 'Atualizar' }}
            </button>
        </div>

        <!-- Estatísticas -->
        <div v-if="statistics" class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gray-800 p-4 rounded-lg text-center">
                <div class="text-2xl font-bold text-green-400">{{ statistics.total_games }}</div>
                <div class="text-sm text-gray-400">Total de Jogadas</div>
            </div>
            <div class="bg-gray-800 p-4 rounded-lg text-center">
                <div class="text-2xl font-bold text-blue-400">{{ statistics.total_wins }}</div>
                <div class="text-sm text-gray-400">Vitórias</div>
            </div>
            <div class="bg-gray-800 p-4 rounded-lg text-center">
                <div class="text-2xl font-bold text-purple-400">{{ statistics.win_rate }}%</div>
                <div class="text-sm text-gray-400">Taxa de Vitória</div>
            </div>
            <div class="bg-gray-800 p-4 rounded-lg text-center">
                <div class="text-2xl font-bold" :class="statistics.is_profitable ? 'text-green-400' : 'text-red-400'">
                    R$ {{ Math.abs(statistics.profit_loss).toFixed(2) }}
                </div>
                <div class="text-sm text-gray-400">
                    {{ statistics.is_profitable ? 'Lucro' : 'Prejuízo' }}
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="isLoading && history.length === 0" class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-500 mx-auto"></div>
            <p class="mt-4 text-gray-400">Carregando histórico...</p>
        </div>

        <!-- Empty State -->
        <div v-else-if="!isLoading && history.length === 0" class="text-center py-8">
            <div class="text-6xl text-gray-600 mb-4">🎮</div>
            <h3 class="text-xl font-semibold text-gray-400 mb-2">Nenhuma jogada encontrada</h3>
            <p class="text-gray-500">Faça sua primeira jogada para ver o histórico aqui!</p>
        </div>

        <!-- History List -->
        <div v-else class="space-y-4">
            <div 
                v-for="game in history" 
                :key="game.id"
                class="bg-gray-800 rounded-lg p-4 hover:bg-gray-750 transition duration-200"
            >
                <div class="flex flex-col md:flex-row md:items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div 
                                    class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold"
                                    :class="game.has_won ? 'bg-green-600' : 'bg-red-600'"
                                >
                                    {{ game.has_won ? '✓' : '✗' }}
                                </div>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg">{{ game.game_name }}</h4>
                                <p class="text-sm text-gray-400">
                                    {{ game.played_at }} • {{ game.played_at_human }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3 md:mt-0 md:text-right">
                        <div class="flex flex-col space-y-1">
                            <div class="text-sm">
                                <span class="text-gray-400">Gasto:</span>
                                <span class="text-red-400 font-semibold">R$ {{ game.amount_spent }}</span>
                            </div>
                            <div v-if="game.has_won" class="text-sm">
                                <span class="text-gray-400">Prêmio:</span>
                                <span class="text-green-400 font-semibold">R$ {{ game.prize_amount }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="game.has_won" class="mt-3 pt-3 border-t border-gray-700">
                    <div class="flex items-center space-x-2">
                        <span class="text-yellow-400">🏆</span>
                        <span class="text-sm font-medium">{{ game.prize_description }}</span>
                    </div>
                </div>

                <!-- Game Items Preview -->
                <div 
                    v-if="showGameItems[game.id]" 
                    class="mt-4 pt-4 border-t border-gray-700"
                >
                    <div class="grid grid-cols-3 gap-2 max-w-xs">
                        <div 
                            v-for="(item, index) in game.game_items" 
                            :key="index"
                            class="bg-gray-700 p-2 rounded text-center text-xs"
                        >
                            <img 
                                :src="item.image" 
                                :alt="item.name"
                                class="w-8 h-8 mx-auto mb-1 object-contain"
                                @error="onImageError"
                            >
                            <div class="text-gray-300 truncate">{{ item.name }}</div>
                        </div>
                    </div>
                </div>

                <div class="mt-3 text-right">
                    <button 
                        @click="toggleGameItems(game.id)"
                        class="text-blue-400 hover:text-blue-300 text-sm"
                    >
                        {{ showGameItems[game.id] ? 'Ocultar cartela' : 'Ver cartela' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div v-if="pagination && pagination.total > pagination.per_page" class="mt-8 flex justify-center">
            <nav class="flex space-x-2">
                <button 
                    @click="loadPage(pagination.current_page - 1)"
                    :disabled="pagination.current_page <= 1"
                    class="px-3 py-2 rounded bg-gray-700 text-white disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-600 transition duration-200"
                >
                    Anterior
                </button>
                
                <span class="px-4 py-2 text-gray-300">
                    Página {{ pagination.current_page }} de {{ pagination.last_page }}
                </span>
                
                <button 
                    @click="loadPage(pagination.current_page + 1)"
                    :disabled="pagination.current_page >= pagination.last_page"
                    class="px-3 py-2 rounded bg-gray-700 text-white disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-600 transition duration-200"
                >
                    Próxima
                </button>
            </nav>
        </div>
    </div>
</template>

<script>
import HttpApi from "@/Services/HttpApi.js";

export default {
    name: 'HistoricoJogadas',
    data() {
        return {
            history: [],
            statistics: null,
            pagination: null,
            isLoading: false,
            error: null,
            showGameItems: {}
        }
    },
    async mounted() {
        await this.loadHistory();
    },
    methods: {
        async loadHistory(page = 1) {
            try {
                this.isLoading = true;
                this.error = null;
                
                const response = await HttpApi.get('scratch-card/history', {
                    params: {
                        page: page,
                        per_page: 10
                    }
                });
                
                if (response.data.success) {
                    this.history = response.data.data.history;
                    this.statistics = response.data.data.statistics;
                    this.pagination = response.data.data.pagination;
                } else {
                    throw new Error(response.data.message || 'Erro ao carregar histórico');
                }
            } catch (error) {
                console.error('Erro ao carregar histórico:', error);
                this.error = error.message;
                // Não mostrar toast de erro aqui, apenas log
            } finally {
                this.isLoading = false;
            }
        },
        
        async loadPage(page) {
            if (page >= 1 && page <= this.pagination.last_page) {
                await this.loadHistory(page);
            }
        },
        
        async refreshHistory() {
            await this.loadHistory(1);
        },
        
        toggleGameItems(gameId) {
            this.$set(this.showGameItems, gameId, !this.showGameItems[gameId]);
        },
        
        onImageError(event) {
            event.target.src = '/assets/images/coin/default.png';
        }
    }
}
</script>
