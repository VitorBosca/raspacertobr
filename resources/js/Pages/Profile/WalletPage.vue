<template>
    <BaseLayout>
        <div class="p-5">
            <main>
                <div v-if="setting != null" class="w-full min-h-[calc(100vh-80px)] bg-neutral-900 rounded-lg p-5">
                    <div class="max-w-7xl mx-auto">
                        <div class="grid grid-cols-1 lg:grid-cols-4 gap-0 md:gap-7">
                            <!-- Sidebar -->
                            <aside class="col-span-1 mb-5">
                                <!-- User Info -->
                                <div class="flex items-center gap-4 border border-slate-700/30 rounded-xl px-5 py-4 mb-6">
                                    <img src="/assets/images/avatar.jpg" alt="Avatar" class="w-14 h-14 rounded-full bg-green-800/10 border border-green-600/20 object-cover">
                                    <div>
                                        <h2 class="text-lg text-white line-clamp-1">{{ userData ? userData.name : 'admin' }}</h2>
                                        <p class="text-xs text-neutral-400">Entrou em {{ userData ? formatDate(userData.created_at) : '' }}</p>
                                    </div>
                                </div>
                                
                                <!-- Navigation -->
                                <nav class="bg-slate-800/20 rounded-xl flex flex-col divide-y divide-neutral-700/40 overflow-hidden">
                                    <button @click="activeTab = 'wallet'" :class="activeTab === 'wallet' ? 'bg-lime-500 text-neutral-900 font-bold' : 'text-white/70 hover:bg-neutral-700/60'" class="flex items-center gap-3 px-6 py-4 text-sm transition-colors w-full text-left group">
                                        <i class="fa-solid fa-user text-lg" :class="activeTab === 'wallet' ? 'text-neutral-900' : 'text-lime-500'"></i>
                                        <span>Conta</span>
                                    </button>
                                    <button @click="activeTab = 'games'" :class="activeTab === 'games' ? 'bg-lime-500 text-neutral-900 font-bold' : 'text-white/70 hover:bg-neutral-700/60'" class="flex items-center gap-3 px-6 py-4 text-sm transition-colors w-full text-left group">
                                        <i class="fa-solid fa-gamepad text-lg" :class="activeTab === 'games' ? 'text-neutral-900' : 'text-lime-500'"></i>
                                        <span>Histórico de Jogos</span>
                                    </button>
                                    <button @click="activeTab = 'transactions'" :class="activeTab === 'transactions' ? 'bg-lime-500 text-neutral-900 font-bold' : 'text-white/70 hover:bg-neutral-700/60'" class="flex items-center gap-3 px-6 py-4 text-sm transition-colors w-full text-left group">
                                        <i class="fa-solid fa-receipt text-lg" :class="activeTab === 'transactions' ? 'text-neutral-900' : 'text-lime-500'"></i>
                                        <span>Transações</span>
                                    </button>
                                    <button class="flex items-center gap-3 px-6 py-4 text-sm transition-colors w-full text-left group text-white/70 hover:bg-neutral-700/60">
                                        <i class="fa-solid fa-shield-alt text-lime-500 text-lg"></i>
                                        <span>Segurança</span>
                                    </button>
                                    <button @click="logout" class="flex items-center gap-3 px-6 py-4 text-sm transition-colors w-full text-left group text-red-500 hover:bg-neutral-700/60">
                                        <i class="fa-solid fa-sign-out-alt text-red-500 text-lg"></i>
                                        <span>Sair</span>
                                    </button>
                                </nav>
                            </aside>
                            
                            <!-- Main Content -->
                            <div class="col-span-3 flex flex-col gap-7">
                                <!-- Loading State -->
                                <div v-if="isLoadingWallet" class="flex justify-center items-center h-64">
                                    <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-green-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                    </svg>
                                    <span class="sr-only">{{ $t('Loading') }}...</span>
                                </div>
                                
                                <!-- Wallet Tab Content -->
                                <div v-if="!isLoadingWallet && activeTab === 'wallet'">
                                    <!-- Stats Cards -->
                                    <section v-if="wallet" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div class="rounded-xl px-6 py-5 flex items-center gap-4 border border-slate-700/30">
                                            <div class="bg-lime-500/20 text-lime-400 rounded-xl w-12 h-12 flex items-center justify-center text-2xl">
                                                <i class="fa-solid fa-money-bill-wave"></i>
                                            </div>
                                            <div>
                                                <div class="text-neutral-400 text-xs">Saldo Total</div>
                                                <div class="text-lime-500 font-semibold text-2xl flex items-center gap-1">
                                                    <span>R$</span>
                                                    <span>{{ formatCurrency(parseFloat(wallet.balance)) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="rounded-xl px-6 py-5 flex items-center gap-4 border border-slate-700/30">
                                            <div class="bg-lime-500/20 text-lime-400 rounded-xl w-12 h-12 flex items-center justify-center text-2xl">
                                                <i class="fa-solid fa-money-check-alt"></i>
                                            </div>
                                            <div>
                                                <div class="text-neutral-400 text-xs">Saldo de Saque</div>
                                                <div class="text-lime-500 font-semibold text-2xl flex items-center gap-1">
                                                    <span>R$</span>
                                                    <span>{{ formatCurrency(parseFloat(wallet.balance_withdrawal)) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="rounded-xl px-6 py-5 flex items-center gap-4 border border-slate-700/30">
                                            <div class="bg-lime-500/20 text-lime-400 rounded-xl w-12 h-12 flex items-center justify-center text-2xl">
                                                <i class="fa-solid fa-hand-holding-usd"></i>
                                            </div>
                                            <div>
                                                <div class="text-neutral-400 text-xs">Saldo de Bônus</div>
                                                <div class="text-lime-500 font-semibold text-2xl flex items-center gap-1">
                                                    <span>R$</span>
                                                    <span>{{ formatCurrency(parseFloat(wallet.balance_bonus)) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    
                                    <!-- Personal Information -->
                                    <section class="border border-slate-700/30 rounded-xl p-7 flex flex-col gap-6 mt-4">
                                        <h3 class="font-semibold text-lg text-white mb-3">Informações da Carteira</h3>
                                        
                                        <!-- Email Field -->
                                        <div class="flex flex-col md:flex-row items-start md:items-center gap-3">
                                            <label class="w-28 text-neutral-400 text-sm">Email</label>
                                            <div class="relative w-full">
                                                <input class="w-full bg-neutral-900 h-10 rounded-lg px-4 py-2 pl-10 border border-neutral-700 text-white/60 text-sm outline-none focus:ring-2 focus:ring-blue-500 transition" 
                                                       type="text" 
                                                       readonly 
                                                       :value="userData ? userData.email : ''">
                                                <div class="absolute left-3 top-2.5 text-blue-400">
                                                    <i class="fa-solid fa-envelope"></i>
                                                </div>
                                            </div>
                                            <button @click="$router.push('/profile/deposit')" class="ml-0 md:ml-3 px-5 py-2 h-10 w-full md:w-[120px] rounded-md bg-blue-500 hover:bg-blue-400 text-white font-semibold text-sm flex items-center gap-2 transition">
                                                <i class="fa-solid fa-plus"></i> 
                                                Depositar
                                            </button>
                                        </div>
                                        
                                        <!-- Phone Field -->
                                        <div class="flex flex-col md:flex-row items-start md:items-center gap-3">
                                            <label class="w-28 text-neutral-400 text-sm">Moeda</label>
                                            <div class="relative w-full">
                                                <input class="w-full bg-neutral-900 h-10 rounded-lg px-4 py-2 pl-10 border border-neutral-700 text-white/60 text-sm outline-none focus:ring-2 focus:ring-blue-500 transition" 
                                                       type="text" 
                                                       readonly 
                                                       :value="wallet ? wallet.currency : ''">
                                                <div class="absolute left-3 top-2.5 text-blue-400">
                                                    <i class="fa-solid fa-coins"></i>
                                                </div>
                                            </div>
                                            <button @click="$router.push('/profile/withdraw')" class="ml-0 md:ml-3 px-5 py-2 h-10 w-full md:w-[120px] rounded-md bg-green-500 hover:bg-green-400 text-white font-semibold text-sm flex items-center gap-2 transition">
                                                <i class="fa-solid fa-minus"></i> 
                                                Sacar
                                            </button>
                                        </div>
                                        
                                        <!-- Document Field -->
                                        <div v-if="mywallets && mywallets.length > 0" class="flex flex-col md:flex-row items-start md:items-center gap-3">
                                            <label class="w-28 text-neutral-400 text-sm">Carteiras</label>
                                            <div class="relative w-full">
                                                <select @change="setWallet($event.target.value)" class="w-full bg-neutral-900 h-10 rounded-lg px-4 py-2 pl-10 border border-neutral-700 text-white/60 text-sm outline-none focus:ring-2 focus:ring-blue-500 transition">
                                                    <option v-for="(walletItem, index) in mywallets" :key="index" :value="walletItem.id" :selected="walletItem.active === 1">
                                                        {{ walletItem.symbol }} - {{ formatCurrency(parseFloat(walletItem.total_balance)) }}
                                                    </option>
                                                </select>
                                                <div class="absolute left-3 top-2.5 text-blue-400">
                                                    <i class="fa-solid fa-wallet"></i>
                                                </div>
                                            </div>
                                            <div class="ml-0 md:ml-3 px-5 py-2 h-10 w-full md:w-[120px] rounded-md bg-gray-600 text-white font-semibold text-sm flex items-center gap-2">
                                                <i class="fa-solid fa-info-circle"></i> 
                                                Info
                                            </div>
                                        </div>
                                    </section>
                                </div>

                                <!-- Game History Tab Content -->
                                <div v-if="!isLoadingWallet && activeTab === 'games'">
                                    <div class="flex items-center gap-4 mb-6">
                                        <h2 class="text-xl font-semibold text-white">Histórico de Jogadas da Raspadinha</h2>
                                        <button 
                                            @click="loadGameHistory" 
                                            :disabled="isLoadingGameHistory"
                                            class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded-lg transition duration-200 disabled:opacity-50 text-sm"
                                        >
                                            {{ isLoadingGameHistory ? 'Carregando...' : 'Atualizar' }}
                                        </button>
                                    </div>

                                    <!-- Statistics Cards -->
                                    <div v-if="gameStatistics" class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                        <div class="bg-gray-800 p-4 rounded-lg text-center">
                                            <div class="text-2xl font-bold text-green-400">{{ gameStatistics.total_games }}</div>
                                            <div class="text-sm text-gray-400">Total de Jogadas</div>
                                        </div>
                                        <div class="bg-gray-800 p-4 rounded-lg text-center">
                                            <div class="text-2xl font-bold text-blue-400">{{ gameStatistics.total_wins }}</div>
                                            <div class="text-sm text-gray-400">Vitórias</div>
                                        </div>
                                        <div class="bg-gray-800 p-4 rounded-lg text-center">
                                            <div class="text-2xl font-bold text-purple-400">{{ gameStatistics.win_rate }}%</div>
                                            <div class="text-sm text-gray-400">Taxa de Vitória</div>
                                        </div>
                                        <div class="bg-gray-800 p-4 rounded-lg text-center">
                                            <div class="text-2xl font-bold" :class="gameStatistics.is_profitable ? 'text-green-400' : 'text-red-400'">
                                                R$ {{ Math.abs(gameStatistics.profit_loss).toFixed(2) }}
                                            </div>
                                            <div class="text-sm text-gray-400">
                                                {{ gameStatistics.is_profitable ? 'Lucro' : 'Prejuízo' }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Loading State -->
                                    <div v-if="isLoadingGameHistory && gameHistory.length === 0" class="text-center py-8">
                                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-500 mx-auto"></div>
                                        <p class="mt-4 text-gray-400">Carregando histórico...</p>
                                    </div>

                                    <!-- Empty State -->
                                    <div v-else-if="!isLoadingGameHistory && gameHistory.length === 0" class="text-center py-8">
                                        <div class="text-6xl text-gray-600 mb-4">🎮</div>
                                        <h3 class="text-xl font-semibold text-gray-400 mb-2">Nenhuma jogada encontrada</h3>
                                        <p class="text-gray-500">Faça sua primeira jogada para ver o histórico aqui!</p>
                                    </div>

                                    <!-- History Table -->
                                    <div v-else class="rounded-2xl bg-neutral-900/90 border border-neutral-800 overflow-x-auto p-1">
                                        <table class="min-w-full text-left">
                                            <thead>
                                                <tr class="border-b border-neutral-800 text-neutral-400 text-sm">
                                                    <th class="p-3">Data</th>
                                                    <th class="p-3">Jogo</th>
                                                    <th class="p-3 text-right">Valor Pago</th>
                                                    <th class="p-3 text-right">Prêmio</th>
                                                    <th class="p-3 text-center">Status</th>
                                                    <th class="p-3 text-center">Detalhes</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(game, index) in gameHistory" :key="index" class="border-b border-neutral-800 text-white hover:bg-neutral-800/60 transition">
                                                    <td class="p-3 whitespace-nowrap">{{ game.played_at }}</td>
                                                    <td class="p-3">{{ game.game_name }}</td>
                                                    <td class="p-3 text-right">R$ {{ formatCurrency(game.amount_spent) }}</td>
                                                    <td class="p-3 text-right">
                                                        <span v-if="game.has_won" class="text-green-400">R$ {{ formatCurrency(game.prize_amount) }}</span>
                                                        <span v-else class="text-red-500">-</span>
                                                    </td>
                                                    <td class="p-3 text-center">
                                                        <span v-if="game.has_won" class="px-2 py-1 rounded-full text-xs font-medium bg-green-900/40 text-green-300">Ganhou</span>
                                                        <span v-else class="px-2 py-1 rounded-full text-xs font-medium bg-red-900/40 text-red-300">Perdeu</span>
                                                    </td>
                                                    <td class="p-3 text-center">
                                                        <button 
                                                            @click="toggleGameDetails(index)"
                                                            class="text-blue-400 hover:text-blue-300 text-sm"
                                                        >
                                                            {{ showGameDetails[index] ? 'Ocultar' : 'Ver' }}
                                                        </button>
                                                    </td>
                                                </tr>
                                                
                                                <!-- Game Details Row -->
                                                <tr v-if="showGameDetails[index]" :key="`details-${index}`" class="bg-neutral-800/40">
                                                    <td colspan="6" class="p-4">
                                                        <div class="space-y-3">
                                                            <div v-if="game.has_won && game.winning_prize" class="flex items-center space-x-2">
                                                                <span class="text-yellow-400">🏆</span>
                                                                <span class="text-sm font-medium">{{ game.prize_description }}</span>
                                                            </div>
                                                            
                                                            <div>
                                                                <p class="text-sm text-gray-400 mb-2">Itens da cartela:</p>
                                                                <div class="grid grid-cols-3 gap-2 max-w-xs">
                                                                    <div 
                                                                        v-for="(item, itemIndex) in game.game_items" 
                                                                        :key="itemIndex"
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
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Transactions Tab Content -->
                                <div v-if="!isLoadingWallet && activeTab === 'transactions'">
                                    <div class="flex flex-col md:flex-row items-start md:items-center gap-4 mb-6">
                                        <h2 class="text-xl font-semibold text-white">Transações</h2>
                                        <button 
                                            @click="loadDepositHistory" 
                                            :disabled="isLoadingDeposits"
                                            class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded-lg transition duration-200 disabled:opacity-50 text-sm"
                                        >
                                            {{ isLoadingDeposits ? 'Carregando...' : 'Atualizar Depósitos' }}
                                        </button>
                                        <div class="ml-1 md:ml-auto flex gap-1 bg-neutral-800/80 rounded-xl p-1">
                                            <button @click="transactionTab = 'deposits'" :class="transactionTab === 'deposits' ? 'bg-neutral-900 text-white font-semibold shadow' : 'text-neutral-400 hover:bg-neutral-900/70'" class="px-7 py-2 rounded-lg text-base transition-all">
                                                Depósitos
                                            </button>
                                            <button @click="transactionTab = 'withdrawals'" :class="transactionTab === 'withdrawals' ? 'bg-neutral-900 text-white font-semibold shadow' : 'text-neutral-400 hover:bg-neutral-900/70'" class="px-7 py-2 rounded-lg text-base transition-all">
                                                Saques
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Loading State para Depósitos -->
                                    <div v-if="isLoadingDeposits && transactionTab === 'deposits'" class="text-center py-8">
                                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-500 mx-auto"></div>
                                        <p class="mt-4 text-gray-400">Carregando depósitos...</p>
                                    </div>
                                    
                                    <!-- Empty State para Depósitos -->
                                    <div v-else-if="!isLoadingDeposits && transactionTab === 'deposits' && filteredTransactions.length === 0" class="text-center py-8">
                                        <div class="text-6xl text-gray-600 mb-4">💳</div>
                                        <h3 class="text-xl font-semibold text-gray-400 mb-2">Nenhum depósito encontrado</h3>
                                        <p class="text-gray-500">Faça seu primeiro depósito para ver o histórico aqui!</p>
                                    </div>
                                    
                                    <div class="rounded-2xl bg-neutral-900/90 border border-neutral-800 overflow-x-auto p-1">
                                        <table class="min-w-full text-left">
                                            <thead>
                                                <tr class="border-b border-neutral-800 text-neutral-400 text-base">
                                                    <th class="py-4 px-4 font-normal">Valor</th>
                                                    <th class="py-4 px-4 font-normal">Status</th>
                                                    <th class="py-4 px-4 font-normal">Data/Hora</th>
                                                    <th class="py-4 px-4 font-normal">ID Transação</th>
                                                    <th class="py-4 px-4 font-normal">Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(transaction, index) in filteredTransactions" :key="index" class="border-b border-neutral-800 last:border-none">
                                                    <td class="py-4 px-4 font-semibold text-green-400">R$ {{ formatCurrency(transaction.amount) }}</td>
                                                    <td class="py-4 px-4">
                                                        <span v-if="transaction.status === 'pending'" class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-900/40 text-yellow-300">Pendente</span>
                                                        <span v-else-if="transaction.status === 'completed'" class="px-3 py-1 rounded-full text-xs font-medium bg-green-900/40 text-green-300">Concluído</span>
                                                        <span v-else class="px-3 py-1 rounded-full text-xs font-medium bg-red-900/40 text-red-300">Cancelado</span>
                                                    </td>
                                                    <td class="py-4 px-4 text-white">{{ transaction.date }}</td>
                                                    <td class="py-4 px-4 font-mono text-xs text-neutral-400">{{ transaction.id }}</td>
                                                    <td class="py-4 px-4">
                                                        <button class="text-xs px-3 py-1 rounded bg-neutral-800/70 text-neutral-300 hover:bg-neutral-700 transition">
                                                            Detalhes
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="flex flex-wrap items-center justify-between gap-2 p-4">
                                            <div class="flex items-center gap-2">
                                                <label class="text-neutral-400 text-sm">Mostrar</label>
                                                <select class="bg-neutral-800 text-white rounded px-2 py-1 text-sm border border-neutral-700 focus:ring-0">
                                                    <option value="10">10</option>
                                                    <option value="25">25</option>
                                                    <option value="50">50</option>
                                                </select>
                                                <span class="text-neutral-400 text-sm">registros por página</span>
                                            </div>
                                            <div class="flex gap-2 items-center">
                                                <button disabled class="px-3 py-1 rounded bg-neutral-800 text-neutral-300 hover:bg-neutral-700 disabled:opacity-50 text-sm">
                                                    &lt; Anterior
                                                </button>
                                                <button disabled class="px-3 py-1 rounded bg-neutral-800 text-neutral-300 hover:bg-neutral-700 disabled:opacity-50 text-sm">
                                                    Próximo &gt;
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </BaseLayout>
</template>


<script>

import { RouterLink } from "vue-router";
import BaseLayout from "@/Layouts/BaseLayout.vue";
import WalletSideMenu from "@/Pages/Profile/Components/WalletSideMenu.vue";
import HistoricoJogadas from "@/Pages/Cassino/Components/HistoricoJogadas.vue";
import {useAuthStore} from "@/Stores/Auth.js";
import HttpApi from "@/Services/HttpApi.js";
import {useSettingStore} from "@/Stores/SettingStore.js";

export default {
    props: [],
    components: {WalletSideMenu, BaseLayout, RouterLink, HistoricoJogadas },
    data() {
        return {
            isLoading: false,
            isLoadingWallet: true,
            wallet: null,
            mywallets: null,
            setting: null,
            activeTab: 'wallet', // wallet, games, transactions
            gameHistory: [],
            gameStatistics: null,
            isLoadingGameHistory: false,
            depositHistory: [],
            isLoadingDeposits: false,
            transactions: [
                { amount: 10.00, status: 'pending', date: '27/07/2025, 01:01', id: 'bc141e2e-b2b2-4209-be7a-a080fa349c66', type: 'deposit' }
            ],
            transactionTab: 'deposits', // deposits, withdrawals
            showGameDetails: {} // Para controlar a exibição dos detalhes das jogadas
        }
    },
    setup(props) {


        return {};
    },
    computed: {
        userData() {
            const authStore = useAuthStore();
            return authStore.user;
        },
        filteredTransactions() {
            if (this.transactionTab === 'deposits') {
                // Retorna os depósitos reais do banco de dados
                return this.depositHistory.map(deposit => ({
                    id: deposit.payment_id,
                    amount: deposit.amount,
                    status: this.getDepositStatus(deposit.status),
                    date: this.formatDateTime(deposit.created_at),
                    type: 'deposit'
                }));
            } else {
                // Retorna os saques (ainda mockados)
                return this.transactions.filter(transaction => transaction.type === 'withdrawal');
            }
        }
    },
    mounted() {

    },
    methods: {
        setWallet: function(id) {
            const _this = this;
            _this.isLoadingWallet = true;

            HttpApi.post('profile/mywallet/'+ id, {})
                .then(response => {
                   _this.getMyWallet();
                    _this.isLoadingWallet = false;
                    window.location.reload();

                })
                .catch(error => {
                    _this.showOperationErrorToast(error);
                    _this.isLoadingWallet = false;
                });
        },
        getWallet: function() {
            const _this = this;
            _this.isLoadingWallet = true;

            HttpApi.get('profile/wallet')
                .then(response => {
                    _this.wallet = response.data.wallet;
                    _this.isLoadingWallet = false;
                })
                .catch(error => {
                    _this.showOperationErrorToast(error);
                    _this.isLoadingWallet = false;
                });
        },
        getMyWallet: function() {
            const _this = this;

            HttpApi.get('profile/mywallet')
                .then(response => {
                    _this.mywallets = response.data.wallets;
                })
                .catch(error => {
                    _this.showOperationErrorToast(error);
                });
        },
        getSetting: function() {
            const _this = this;
            const settingStore = useSettingStore();
            const settingData = settingStore.setting;

            if(settingData) {
                _this.setting = settingData;
            }

            _this.isLoading = false;
        },
        rolloverPercentage(balance) {
            return Math.max(0, ((balance / 100) * 100).toFixed(2));
        },
        formatCurrency(value) {
            return new Intl.NumberFormat('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(value);
        },
        formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('pt-BR');
        },
        logout() {
            const authStore = useAuthStore();
            authStore.logout();
            this.$router.push('/login');
        },
        
        async loadGameHistory() {
            try {
                this.isLoadingGameHistory = true;
                
                const response = await HttpApi.get('scratch-card/history', {
                    params: {
                        page: 1,
                        per_page: 50 // Carregar mais jogadas na página da carteira
                    }
                });
                
                if (response.data.success) {
                    this.gameHistory = response.data.data.history;
                    this.gameStatistics = response.data.data.statistics;
                } else {
                    throw new Error(response.data.message || 'Erro ao carregar histórico');
                }
            } catch (error) {
                console.error('Erro ao carregar histórico:', error);
                this.showErrorToast('Erro ao carregar histórico de jogadas');
            } finally {
                this.isLoadingGameHistory = false;
            }
        },
        
        toggleGameDetails(index) {
            this.$set(this.showGameDetails, index, !this.showGameDetails[index]);
        },
        
        async loadDepositHistory() {
            try {
                this.isLoadingDeposits = true;
                
                const response = await HttpApi.get('wallet/deposit');
                
                console.log('Resposta da API de depósitos:', response.data); // Debug
                
                if (response.data && response.data.deposits) {
                    // Laravel paginate retorna os dados em .data
                    this.depositHistory = response.data.deposits.data || [];
                    console.log('Depósitos carregados:', this.depositHistory); // Debug
                } else {
                    console.error('Estrutura de resposta inesperada:', response.data);
                    throw new Error('Erro ao carregar histórico de depósitos');
                }
            } catch (error) {
                console.error('Erro ao carregar depósitos:', error);
                this.showErrorToast('Erro ao carregar histórico de depósitos');
            } finally {
                this.isLoadingDeposits = false;
            }
        },
        
        getDepositStatus(status) {
            const statusMap = {
                0: 'pending',    // Pendente
                1: 'completed',  // Aprovado/Concluído
                2: 'cancelled'   // Rejeitado/Cancelado
            };
            return statusMap[status] || 'pending';
        },
        
        formatDateTime(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('pt-BR') + ', ' + date.toLocaleTimeString('pt-BR', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });
        },
        
        onImageError(event) {
            event.target.src = '/assets/images/coin/default.png';
        },
    },
    created() {
        this.getWallet();
        this.getMyWallet();
        this.getSetting();
    },
    watch: {
        activeTab(newTab) {
            if (newTab === 'games' && this.gameHistory.length === 0) {
                this.loadGameHistory();
            }
            if (newTab === 'transactions' && this.depositHistory.length === 0) {
                this.loadDepositHistory();
            }
        }
    },
};
</script>

<style scoped>

</style>
