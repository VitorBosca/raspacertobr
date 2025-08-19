<template>
    <div class="flex min-h-full items-end sm:items-center justify-center text-center p-4 sm:p-0">
        <div id="headlessui-dialog-panel-v-0-22" data-headlessui-state="open" class="relative text-left rtl:text-right flex flex-col bg-white dark:bg-gray-900 !bg-black/90 shadow-xl w-full sm:max-w-lg rounded-lg sm:my-8">
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
                        <div v-if="!showPixQRCode" class="flex items-center gap-3 mb-5">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-8 stroke-slate-50">
                                <path d="M17 20.5H7C4 20.5 2 19 2 15.5V8.5C2 5 4 3.5 7 3.5H17C20 3.5 22 5 22 8.5V15.5C22 19 20 20.5 17 20.5Z" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M2 9H3C6 9 7 8 7 5V4" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M22 9H21C18 9 17 8 17 5V4" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M2 15H3C6 15 7 16 7 19V20" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M22 15H21C18 15 17 16 17 19V20" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <label class="text-white text-2xl">Depositar</label>
                        </div>
                        <div v-if="!showPixQRCode">
                            <label class="text-slate-300 text-sm mb-3">Valor</label>
                            <div class="relative">
                                <input type="number" v-model="deposit.amount" placeholder="0,00" class="w-full py-3 h-10 bg-gray-800/50 rounded pl-12 border border-gray-600/30 focus:border-green-500 focus:ring-1 focus:ring-green-500 transition text-sm">
                                <div class="absolute left-0 top-0 p-2 text-white/70">R$</div>
                            </div>
                            <div class="overflow-x-auto whitespace-nowrap py-5">
                                <div class="flex gap-2 min-w-fit">
                                    <button @click="setAmount(10)" class="relative h-10 px-3 rounded-lg flex-shrink-0 font-medium transition text-green-600 bg-green-700/20"> R$ 10,00</button>
                                    <button @click="setAmount(30)" class="relative h-10 px-3 rounded-lg flex-shrink-0 font-medium transition text-green-600 bg-green-700/20"> R$ 30,00</button>
                                    <button @click="setAmount(50)" class="relative h-10 px-3 rounded-lg flex-shrink-0 font-medium transition text-yellow-400 bg-yellow-500/10 border-2 border-yellow-400">
                                        <span class="absolute -top-3 left-1/2 -translate-x-1/2 text-[10px] px-1.5 py-[1px] rounded-full bg-yellow-400 text-black font-bold tracking-wide"> ⭐ RECOMENDADO </span> R$ 50,00
                                    </button>
                                    <button @click="setAmount(100)" class="relative h-10 px-3 rounded-lg flex-shrink-0 font-medium transition text-green-600 bg-green-700/20"> R$ 100,00</button>
                                    <button @click="setAmount(200)" class="relative h-10 px-3 rounded-lg flex-shrink-0 font-medium transition text-green-600 bg-green-700/20"> R$ 200,00</button>
                                    <button @click="setAmount(300)" class="relative h-10 px-3 rounded-lg flex-shrink-0 font-medium transition text-green-600 bg-green-700/20"> R$ 300,00</button>
                                    <button @click="setAmount(500)" class="relative h-10 px-3 rounded-lg flex-shrink-0 font-medium transition text-green-600 bg-green-700/20"> R$ 500,00</button>
                                </div>
                            </div>
                            <button @click="submitQRCode" class="w-full mt-6 button-primary text-black font-semibold py-3 flex items-center justify-center rounded-lg disabled:opacity-60">
                                <i class="fa-solid fa-qrcode mr-2"></i>
                                <span>{{ this.botaoGerar }}</span>
                            </button>
                        </div>
                        
                        <!-- QR Code Section -->
                        <div v-if="showPixQRCode" class="mt-6 p-4 bg-gray-800/30 rounded-lg border border-gray-600/30">
                            <div class="text-center mb-4">
                                <h3 class="text-white text-lg font-semibold mb-2">QR Code PIX</h3>
                                <p class="text-gray-300 text-sm">Escaneie o código ou copie o código PIX</p>
                            </div>
                            
                            <!-- QR Code Display -->
                            <div class="flex justify-center mb-4">
                                <div class="bg-white p-4 rounded-lg">
                                    <QRCodeVue3
                                        :value="qrcodecopypast"
                                        :width="200"
                                        :height="200"
                                        :corner-square-options="{ type: 'square', color: '#000000' }"
                                        :corner-dot-options="{ type: 'square', color: '#000000' }"
                                        :background-options="{ color: '#ffffff' }"
                                        :dots-options="{ type: 'square', color: '#000000' }"
                                    />
                                </div>
                            </div>
                            
                            <!-- PIX Copy Code -->
                            <div class="mb-4">
                                <label class="text-slate-300 text-sm mb-2 block">Código PIX Copia e Cola</label>
                                <div class="relative">
                                    <input 
                                        id="pixcopiaecola" 
                                        type="text" 
                                        :value="qrcodecopypast" 
                                        readonly 
                                        class="w-full py-3 px-4 bg-gray-700/50 rounded border border-gray-600/30 text-white text-sm pr-20"
                                    >
                                    <button 
                                        @click="copyQRCode" 
                                        class="absolute right-2 top-1/2 -translate-y-1/2 px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs rounded transition"
                                    >
                                        <i class="fa-solid fa-copy mr-1"></i>
                                        Copiar
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Transaction Info -->
                            <div class="text-center text-gray-400 text-xs">
                                <p>ID da Transação: {{ idTransaction }}</p>
                                <p class="mt-1">Aguardando pagamento...</p>
                            </div>
                            
                            <!-- Close QR Code Button -->
                            <button 
                                @click="closeQRCode" 
                                class="w-full mt-4 bg-gray-600 hover:bg-gray-700 text-white py-2 rounded-lg transition text-sm"
                            >
                                <i class="fa-solid fa-arrow-left mr-2"></i>
                                Voltar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {useToast} from "vue-toastification";
    import HttpApi from "@/Services/HttpApi.js";
    import QRCodeVue3 from "qrcode-vue3";
    import {useAuthStore} from "@/Stores/Auth.js";
    import { StripeCheckout } from '@vue-stripe/vue-stripe';
    import {useSettingStore} from "@/Stores/SettingStore.js";

    export default {
        props: ['showMobile', 'title', 'isFull'],
        emits: ['close-modal'],
        components: { QRCodeVue3, StripeCheckout },
        data() {

            return {
                isLoading: false,
                minutes: 15,
                seconds: 0,
                timer: null,
                setting: null,
                wallet: null,
                botaoGerar: 'Gerar QR Code',
                deposit: {
                    amount: '',
                    cpf: '',
                    gateway: '',
                    accept_bonus: true
                },
                selectedAmount: 0,
                showPixQRCode: false,
                qrcodecopypast: '',
                idTransaction: '',
                intervalId: null,
                paymentType: 'pix',

                /// stripe
                elementsOptions: {
                    appearance: {}, // appearance options
                },
                confirmParams: {
                    return_url: null, // success url
                },
                successURL: null,
                cancelURL: null,
                amount: null,
                currency: null,
                publishableKey: null,
                sessionId: null,
                paymentGateway: 'nomad',
            }
        },
        setup(props) {


            return {};
        },
        computed: {
            isAuthenticated() {
                const authStore = useAuthStore();
                return authStore.isAuth;
            },
        },
        mounted() {

        },
        beforeUnmount() {
            clearInterval(this.timer);
            this.paymentType = null;
        },
        methods: {
            setPaymentMethod: function(type, gateway) {
                this.paymentType = type;
                this.paymentGateway = gateway ?? 'nomad';
            },
            closeModal: function() {
                // Limpa os dados do QR Code se estiver aberto
                if (this.showPixQRCode) {
                    this.closeQRCode();
                }
                
                // Emite evento para o componente pai (solução principal)
                this.$emit('close-modal');
                
                // Backup: tenta fechar o modal diretamente se disponível
                if (this.modalDeposit) {
                    this.modalDeposit.hide();
                }
                
                // Remove manualmente elementos de backdrop residuais
                setTimeout(() => {
                    const modalElement = document.getElementById('modalElDeposit');
                    if (modalElement) {
                        modalElement.classList.add('hidden');
                        modalElement.setAttribute('aria-hidden', 'true');
                    }
                    
                    // Remove backdrops
                    const backdrops = document.querySelectorAll('[data-modal-backdrop], .fixed.inset-0.z-40');
                    backdrops.forEach(backdrop => backdrop.remove());
                    
                    // Remove scroll lock do body
                    document.body.style.overflow = '';
                    document.body.classList.remove('overflow-hidden');
                }, 50);
                if (window.modalDepositInstance) {
                    window.modalDepositInstance.hide();
                }
            },
            closeQRCode: function() {
                this.showPixQRCode = false;
                this.qrcodecopypast = '';
                this.idTransaction = '';
                this.botaoGerar = 'Gerar QR Code';
                
                // Limpa o intervalo de verificação se estiver rodando
                if (this.intervalId) {
                    clearInterval(this.intervalId);
                    this.intervalId = null;
                }
            },
            submitQRCode: function(event) {
                const _this = this;
                const _toast = useToast();

                _this.botaoGerar = 'Processando...';

                if(_this.deposit.amount === '' || _this.deposit.amount === undefined) {
                    _toast.error(_this.$t('You need to enter a value'));
                    _this.botaoGerar = 'Gerar QR Code';
                    return;
                }

                if(parseFloat(_this.deposit.amount) < parseFloat(_this.setting.min_deposit)) {
                    _toast.error('O valor mínimo de depósito é de '+ _this.setting.min_deposit);
                    _this.botaoGerar = 'Gerar QR Code';
                    return;
                }

                if(parseFloat(_this.deposit.amount) > parseFloat(_this.setting.max_deposit)) {
                    _toast.error('O valor máximo de depósito é de '+ _this.setting.min_deposit);
                    _this.botaoGerar = 'Gerar QR Code';
                    return;
                }

                _this.deposit.paymentType = _this.paymentType;
                _this.deposit.gateway = _this.paymentGateway;

                _this.isLoading = true;
                HttpApi.post('wallet/deposit/payment', _this.deposit).then(response => {
                    _this.showPixQRCode = true;
                    _this.isLoading = false;
                    _this.botaoGerar = 'QR Code Gerado';

                    _this.idTransaction = response.data.idTransaction;
                    _this.qrcodecopypast = response.data.qrcode;

                    // Mostra mensagem de sucesso
                    _toast.success('QR Code gerado com sucesso!');

                    insertKwaiPixelLogs("addToCart");
                    insertKwaiPixelLogs("initiatedCheckout");

                    _this.intervalId = setInterval(function () {
                        _this.checkTransactions(_this.idTransaction);
                    }, 5000);

                }).catch(error => {
                    Object.entries(JSON.parse(error.request.responseText)).forEach(([key, value]) => {
                        _toast.error(`${value}`);
                    });
                    _this.showPixQRCode = false;
                    _this.isLoading = false;
                    _this.botaoGerar = 'Gerar QR Code';
                });
            },
            checkTransactions: function(idTransaction) {
                const _this = this;
                const _toast = useToast();

                HttpApi.post(_this.paymentGateway+'/consult-status-transaction', { idTransaction: idTransaction }).then(response => {
                    _toast.success('Pagamento confirmado com sucesso!');
                    clearInterval(_this.intervalId);

                    insertKwaiPixelLogs("purchase");
                    
                    // Fecha o QR Code e reseta o formulário
                    _this.closeQRCode();
                    
                    // Fecha o modal após 2 segundos para dar tempo do usuário ver a mensagem
                    setTimeout(() => {
                        _this.closeModal();
                    }, 2000);
                    
                }).catch(error => {
                    Object.entries(JSON.parse(error.request.responseText)).forEach(([key, value]) => {
                        // _toast.error(`${value}`);
                    });
                });
            },
            copyQRCode: function(event) {
                const _toast = useToast();
                var inputElement = document.getElementById("pixcopiaecola");
                inputElement.select();
                inputElement.setSelectionRange(0, 99999);  // Para dispositivos móveis

                // Copia o conteúdo para a área de transferência
                document.execCommand("copy");
                _toast.success('Pix Copiado com sucesso');
            },
            setAmount: function(amount) {
                this.deposit.amount = amount;
                this.selectedAmount = amount;
            },
            getWallet: function() {
                const _this = this;
                const _toast = useToast();
                _this.isLoadingWallet = true;

                HttpApi.get('profile/wallet')
                    .then(response => {
                        _this.wallet = response.data.wallet;
                        _this.currency = response.data.wallet.currency;
                        _this.isLoadingWallet = false;
                    })
                    .catch(error => {
                        const _this = this;
                        Object.entries(JSON.parse(error.request.responseText)).forEach(([key, value]) => {
                            _toast.error(`${value}`);
                        });
                        _this.isLoadingWallet = false;
                    });
            },
            getSetting: function() {
                const _this = this;
                const settingStore = useSettingStore();
                const settingData = settingStore.setting;

                if(settingData) {
                    _this.setting = settingData;
                    _this.amount  = settingData.max_deposit;
                }
            },
        },
        created() {
            if(this.isAuthenticated) {
                this.getWallet();
                this.getSetting();

                let gatewaySelected = this.setting.digitopay_is_enable ? 'digitopay' : this.setting.sharkpay_is_enable ? 'sharkpay' : this.setting.bspay_is_enable ? 'bspay' : this.setting.nomad_is_enable ? 'nomad' : 'pradapay';

                switch (gatewaySelected) {
                    case 'nomad':
                        this.setPaymentMethod('pix', 'nomad');
                        break;
                    case 'bspay':
                        this.setPaymentMethod('pix', 'bspay');
                        break;
                    case 'pradapay':
                        this.setPaymentMethod('pix', 'pradapay');
                        break;
                    default:
                        this.setPaymentMethod('pix', 'pradapay');
                }
            }
        },
        watch: {
            amount(oldValue, newValue) {
            },
            currency(oldValue, newValue) {
            }
        },
    };
</script>

<style scoped>
/* Adicione estilos personalizados aqui, se necessário */
</style>
