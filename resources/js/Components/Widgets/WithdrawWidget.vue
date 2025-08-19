<template>
    <div class="flex min-h-full items-end sm:items-center justify-center text-center p-4 sm:p-0">
        <div id="headlessui-dialog-panel-v-0-5" data-headlessui-state="open" class="relative text-left rtl:text-right flex flex-col bg-white dark:bg-gray-900 !bg-black/90 shadow-xl w-full sm:max-w-lg rounded-lg sm:my-8 z-[9999999]">
            <div class="rounded-lg shadow dark:bg-gray-900 bg-[#0A0D0B]">
                <div class="">
                    <div class="relative w-full text-center min-h-[214px] flex items-center justify-center bg-center bg-cover rounded-t-lg" style="background-image: url('/assets/images/banners/deposit_bg.jpg');">
                        <button @click="closeModal" class="absolute top-3 right-3 p-2 text-white hover:text-red-500 transition" aria-label="Fechar">
                            <i class="fa-solid fa-xmark text-2xl"></i>
                        </button>
                    </div>
                </div>
                <div class="px-4 py-5 sm:p-6 3rem">
                    <div>
                        <div class="flex items-center gap-3 mb-5">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-8 stroke-slate-50">
                                <path d="M17 20.5H7C4 20.5 2 19 2 15.5V8.5C2 5 4 3.5 7 3.5H17C20 3.5 22 5 22 8.5V15.5C22 19 20 20.5 17 20.5Z" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M2 9H3C6 9 7 8 7 5V4" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M22 9H21C18 9 17 8 17 5V4" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M2 15H3C6 15 7 16 7 19V20" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M22 15H21C18 15 17 16 17 19V20" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <label class="text-white text-2xl">Sacar</label>
                        </div>
                        
                        <div>
                            <label class="text-slate-300 text-sm mb-3">Valor</label>
                            <div class="relative">
                                <input 
                                    v-model="withdraw.amount" 
                                    type="number" 
                                    :min="setting?.min_withdrawal || 20"
                                    :max="setting?.max_withdrawal || 1000"
                                    step="0.01"
                                    placeholder="0,00" 
                                    class="w-full py-3 h-10 bg-gray-800/50 rounded pl-12 border border-gray-600/30 focus:border-green-500 focus:ring-1 focus:ring-green-500 transition text-sm">
                                <div class="absolute left-0 top-0 p-2 text-white/70">R$</div>
                            </div>
                            <div class="overflow-x-auto whitespace-nowrap py-5">
                                <div class="flex gap-2 min-w-fit">
                                    <button @click="setAmount(10)" type="button" class="relative h-10 px-3 rounded-lg flex-shrink-0 font-medium transition text-green-600 bg-green-700/20">
                                        R$ 10,00
                                    </button>
                                    <button @click="setAmount(30)" type="button" class="relative h-10 px-3 rounded-lg flex-shrink-0 font-medium transition text-green-600 bg-green-700/20">
                                        R$ 30,00
                                    </button>
                                    <button @click="setAmount(50)" type="button" class="relative h-10 px-3 rounded-lg flex-shrink-0 font-medium transition text-yellow-400 bg-yellow-500/10 border-2 border-yellow-400">
                                        <span class="absolute -top-3 left-1/2 -translate-x-1/2 text-[10px] px-1.5 py-[1px] rounded-full bg-yellow-400 text-black font-bold tracking-wide">
                                            ⭐ RECOMENDADO
                                        </span>
                                        R$ 50,00
                                    </button>
                                    <button @click="setAmount(100)" type="button" class="relative h-10 px-3 rounded-lg flex-shrink-0 font-medium transition text-green-600 bg-green-700/20">
                                        R$ 100,00
                                    </button>
                                    <button @click="setAmount(200)" type="button" class="relative h-10 px-3 rounded-lg flex-shrink-0 font-medium transition text-green-600 bg-green-700/20">
                                        R$ 200,00
                                    </button>
                                    <button @click="setAmount(300)" type="button" class="relative h-10 px-3 rounded-lg flex-shrink-0 font-medium transition text-green-600 bg-green-700/20">
                                        R$ 300,00
                                    </button>
                                    <button @click="setAmount(500)" type="button" class="relative h-10 px-3 rounded-lg flex-shrink-0 font-medium transition text-green-600 bg-green-700/20">
                                        R$ 500,00
                                    </button>
                                </div>
                            </div>

                            <!-- Dados PIX -->
                            <div class="mt-5 flex items-center gap-3">
                                <div class="block w-1/3">
                                    <label class="text-slate-300 text-sm mb-3">Tipo de Chave</label>
                                    <select 
                                        v-model="withdraw.pix_type" 
                                        class="text-white/80 w-full p-3 h-10 bg-gray-800 rounded border border-gray-600/30 focus:border-green-500 focus:ring-1 focus:ring-green-500 transition text-sm">
                                        <option disabled value="">Selecione</option>
                                        <option value="phoneNumber">Telefone</option>
                                        <option value="email">E-mail</option>
                                        <option value="document">CPF</option>
                                        <option value="document">CNPJ</option>
                                        <option value="randomKey">Chave aleatória</option>
                                    </select>
                                </div>
                                <div class="block w-2/3">
                                    <label class="text-slate-300 text-sm mb-3">Chave Pix</label>
                                    <input 
                                        v-model="withdraw.pix_key" 
                                        type="text" 
                                        placeholder="Digite sua chave PIX..." 
                                        class="w-full p-3 h-10 bg-gray-800/50 rounded border border-gray-600/30 focus:border-green-500 focus:ring-1 focus:ring-green-500 transition text-sm">
                                </div>
                            </div>

                            <button 
                                @click="submitWithdraw" 
                                :disabled="isLoading"
                                class="w-full mt-6 button-primary text-black font-semibold py-3 flex items-center justify-center rounded-lg disabled:opacity-60">
                                <i class="fa-solid fa-paper-plane mr-2"></i>
                                <span v-if="isLoading">Processando...</span>
                                <span v-else>Solicitar Saque</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { useToast } from "vue-toastification";
import HttpApi from "@/Services/HttpApi.js";
import { useAuthStore } from "@/Stores/Auth.js";
import { useSettingStore } from "@/Stores/SettingStore.js";
import { Modal } from 'flowbite';

export default {
    props: ['showMobile', 'title', 'isFull'],
    emits: ['close-modal'],
    data() {
        return {
            isLoading: false,
            setting: null,
            wallet: null,
            withdraw: {
                name: '',
                pix_key: '',
                pix_type: '',
                amount: '',
                type: 'pix',
                currency: '',
                symbol: '',
                accept_terms: false
            },
            modalWithdraw: null,
        }
    },
    computed: {
        isAuthenticated() {
            const authStore = useAuthStore();
            return authStore.isAuth;
        },
        userData() {
            const authStore = useAuthStore();
            return authStore.user;
        },
    },
    mounted() {

    },
    methods: {
        closeModal() {
            this.$emit('close-modal');
            this.modalWithdraw.hide();
        },
        setAmount: function(amount) {
            this.withdraw.amount = amount;
        },
        setMinAmount: function() {
            this.withdraw.amount = this.setting?.min_withdrawal || 20;
        },
        setMaxAmount: function() {
            const maxAmount = Math.min(
                this.setting?.max_withdrawal || 1000,
                this.wallet?.balance_withdrawal || 0
            );
            this.withdraw.amount = maxAmount;
        },
        setPercentAmount: function(percent) {
            const amount = (percent / 100) * (this.wallet?.balance_withdrawal || 0);
            this.withdraw.amount = Math.min(amount, this.setting?.max_withdrawal || 1000);
        },
        formatCurrency: function(value) {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(value || 0);
        },
        submitWithdraw: function() {
            const _this = this;
            const _toast = useToast();

            if (_this.withdraw.amount === '' || _this.withdraw.amount === undefined) {
                _toast.error('Você precisa informar um valor');
                return;
            }

            if (parseFloat(_this.withdraw.amount) < parseFloat(_this.setting?.min_withdrawal || 20)) {
                _toast.error('O valor mínimo de saque é de ' + (_this.setting?.min_withdrawal || 20));
                return;
            }

            if (parseFloat(_this.withdraw.amount) > parseFloat(_this.setting?.max_withdrawal || 1000)) {
                _toast.error('O valor máximo de saque é de ' + (_this.setting?.max_withdrawal || 1000));
                return;
            }

            if (parseFloat(_this.withdraw.amount) > parseFloat(_this.wallet?.balance_withdrawal || 0)) {
                _toast.error('Você não tem saldo suficiente');
                return;
            }

            if (!_this.withdraw.pix_type || _this.withdraw.pix_type === '') {
                _toast.error('Você precisa selecionar o tipo de chave PIX');
                return;
            }

            if (!_this.withdraw.pix_key || _this.withdraw.pix_key === '') {
                _toast.error('Você precisa informar a chave PIX');
                return;
            }

            _this.isLoading = true;

            // Define campos obrigatórios se não estiverem preenchidos
            if (!_this.withdraw.name && _this.userData?.name) {
                _this.withdraw.name = _this.userData.name;
            }
            _this.withdraw.accept_terms = true;

            HttpApi.post('wallet/withdraw/request', _this.withdraw).then(response => {
                _this.isLoading = false;
                _toast.success(response.data.message || 'Saque solicitado com sucesso!');
                
                // Reset form and close modal
                _this.resetForm();
                _this.closeModal();
                
                // Refresh wallet data
                _this.getWallet();
                
            }).catch(error => {
                _this.isLoading = false;
                
                if (error.request && error.request.responseText) {
                    try {
                        const errors = JSON.parse(error.request.responseText);
                        Object.entries(errors).forEach(([key, value]) => {
                            if (Array.isArray(value)) {
                                value.forEach(msg => _toast.error(msg));
                            } else {
                                _toast.error(value);
                            }
                        });
                    } catch (e) {
                        _toast.error('Erro ao processar saque');
                    }
                } else {
                    _toast.error('Erro ao processar saque');
                }
            });
        },
        getWallet: function() {
            const _this = this;
            const _toast = useToast();

            HttpApi.get('profile/wallet')
                .then(response => {
                    _this.wallet = response.data.wallet;
                    _this.withdraw.currency = response.data.wallet.currency;
                    _this.withdraw.symbol = response.data.wallet.symbol;
                })
                .catch(error => {
                    console.error('Erro ao carregar carteira:', error);
                });
        },
        getSetting: function() {
            const _this = this;
            const settingStore = useSettingStore();
            const settingData = settingStore.setting;

            if (settingData) {
                _this.setting = settingData;
                _this.withdraw.amount = settingData.min_withdrawal;
            }
        },
    },
    created() {
        if (this.isAuthenticated) {
            this.getWallet();
            this.getSetting();
            // Define o nome automaticamente se estiver logado
            if (this.userData?.name) {
                this.withdraw.name = this.userData.name;
            }
            // Define termos como aceitos automaticamente
            this.withdraw.accept_terms = true;
        }
    },
    watch: {
        // Watch for setting changes
    },
};
</script>

<style scoped>
/* Adicione estilos personalizados aqui, se necessário */
</style>