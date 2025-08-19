<template>
    <div>
        <h1 class="text-[20px] text-white mb-5">Prêmios da Raspadinha:</h1>
        <div class="w-full">
            <div v-if="isLoading" class="text-center text-white">
                Carregando prêmios...
            </div>
            <div v-else-if="prizes.length === 0" class="text-center text-gray-400">
                Nenhum prêmio configurado para este jogo.
            </div>
            <div v-else id="rewards" class="grid grid-cols-2 md:grid-cols-7 gap-[10px] w-full">
                <div
                    v-for="(prize, index) in prizes"
                    :key="index"
                    class="group flex flex-col bg-[#262626] bg-gradient-to-t from-[#29e5041f] from-[0%] to-[#262626] to-[35%] p-[12px] rounded-[10px] cursor-pointer aspect-square transition duration-300"
                >
                    <div class="flex justify-center">
                        <img
                            :src="prize.image || '/assets/images/coin/default.png'" 
                            :alt="prize.name"
                            class="p-[10px] w-[126px] h-[126px] object-contain"
                            @error="onImageError"
                        >
                    </div>
                    <div class="mb-2">
                        <p class="text-sm text-white font-semibold line-clamp-1" :title="prize.name">{{ prize.name }}</p>
                    </div>
                    <div class="py-[4px] px-[6px] w-fit rounded-[6px] bg-[#fff] font-medium text-[#000] text-center text-[14px]">
                        {{ prize.value }}
                    </div>
                    <div v-if="prize.type === 'product'" class="text-xs text-gray-300 mt-1 text-center">
                        Produto Físico
                    </div>
                    <!-- <div v-else-if="prize.type === 'money'" class="text-xs text-green-300 mt-1 text-center">
                        Dinheiro
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import HttpApi from "@/Services/HttpApi.js";

export default {
    name: 'PremiosDinamicos',
    props: {
        gameId: {
            type: [String, Number],
            required: true
        }
    },
    data() {
        return {
            prizes: [],
            isLoading: true,
            error: null
        }
    },
    async mounted() {
        await this.fetchGamePrizes();
    },
    watch: {
        gameId: {
            handler: async function(newGameId) {
                if (newGameId) {
                    await this.fetchGamePrizes();
                }
            },
            immediate: true
        }
    },
    methods: {
        async fetchGamePrizes() {
            if (!this.gameId) return;
            
            try {
                this.isLoading = true;
                const response = await HttpApi.get(`scratch-card/prizes/${this.gameId}`);
                
                if (response.data.success) {
                    const apiData = response.data.data;
                    this.prizes = apiData.prizes || [];
                    
                    // Log informações úteis para debug
                    if (apiData.uses_default_prizes) {
                        console.log(`Jogo ${this.gameId}: Usando prêmios padrão (${apiData.configured_prizes} prêmios configurados)`);
                    } else {
                        console.log(`Jogo ${this.gameId}: Usando prêmios configurados (${apiData.configured_prizes} prêmios)`);
                    }
                } else {
                    throw new Error(response.data.message || 'Erro ao carregar prêmios');
                }
            } catch (error) {
                console.error('Erro ao buscar prêmios:', error);
                this.error = error.message;
                // Usar prêmios padrão em caso de erro
                this.prizes = this.getDefaultPrizes();
                console.log(`Jogo ${this.gameId}: Usando prêmios padrão devido a erro na API`);
            } finally {
                this.isLoading = false;
            }
        },
        
        onImageError(event) {
            // Fallback para imagem padrão se a imagem não carregar
            event.target.src = '/assets/images/coin/default.png';
        },
        
        getDefaultPrizes() {
            // Prêmios padrão sincronizados com o backend
            return [
                {
                    name: 'Nota R$ 1',
                    image: '/assets/images/coin/1.png',
                    value: 'R$ 1,00',
                    type: 'money',
                    cash_value: 1.00
                },
                {
                    name: 'Nota R$ 2',
                    image: '/assets/images/coin/2.png',
                    value: 'R$ 2,00',
                    type: 'money',
                    cash_value: 2.00
                },
                {
                    name: 'Nota R$ 3',
                    image: '/assets/images/coin/3.png',
                    value: 'R$ 3,00',
                    type: 'money',
                    cash_value: 3.00
                },
                {
                    name: 'Nota R$ 5',
                    image: '/assets/images/coin/5.png',
                    value: 'R$ 5,00',
                    type: 'money',
                    cash_value: 5.00
                },
                {
                    name: 'Nota R$ 10',
                    image: '/assets/images/coin/10.png',
                    value: 'R$ 10,00',
                    type: 'money',
                    cash_value: 10.00
                },
                {
                    name: 'Nota R$ 20',
                    image: '/assets/images/coin/20.png',
                    value: 'R$ 20,00',
                    type: 'money',
                    cash_value: 20.00
                },
                {
                    name: 'Nota R$ 50',
                    image: '/assets/images/coin/50.png',
                    value: 'R$ 50,00',
                    type: 'money',
                    cash_value: 50.00
                },
                {
                    name: 'Nota R$ 100',
                    image: '/assets/images/coin/100.png',
                    value: 'R$ 100,00',
                    type: 'money',
                    cash_value: 100.00
                },
                {
                    name: 'iPhone 15 Pro',
                    image: 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=100&h=100&fit=crop&crop=center',
                    value: 'R$ 8.999',
                    type: 'product',
                    cash_value: 0
                },
                {
                    name: 'Samsung Galaxy S24',
                    image: 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=100&h=100&fit=crop&crop=center',
                    value: 'R$ 4.999',
                    type: 'product',
                    cash_value: 0
                },
                {
                    name: 'PlayStation 5',
                    image: 'https://images.unsplash.com/photo-1607853202273-797f1c22a38e?w=100&h=100&fit=crop&crop=center',
                    value: 'R$ 3.999',
                    type: 'product',
                    cash_value: 0
                },
                {
                    name: 'AirPods Pro',
                    image: 'https://images.unsplash.com/photo-1572569511254-d8f925fe2cbb?w=100&h=100&fit=crop&crop=center',
                    value: 'R$ 1.999',
                    type: 'product',
                    cash_value: 0
                }
            ];
        }
    }
}
</script>
