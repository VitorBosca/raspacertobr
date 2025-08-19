<template>
    <BaseLayout>
        <div class="w-full min-h-[calc(100vh-80px)] bg-[#0a0d0b] rounded-lg p-[10px] px-[16px] text-white">
            <div class="bg-[#171717] rounded-[16px] p-[10px] pt-[20px] min-h-[calc(100vh-80px)]">
                <div class="max-w-7xl mx-auto !h-[100%]">
                    <div v-if="!isLoading" class="grid grid-cols-1 lg:grid-cols-4 gap-[10px] md:gap-7">
                        <!-- Card de usuário -->
                        <div class="col-span-1 border border-[rgba(255,255,255,.05)] rounded-xl min-h-[260px]">
                            <div class="flex flex-col justify-between p-6">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-14 h-14 rounded-full bg-yellow-600 flex items-center justify-center text-2xl">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                    <div>
                                        <p class="text-[15px]">{{ userData.name || 'Usuário' }}</p>
                                        <div class="flex items-center gap-2 w-fit rounded-[6px] text-sm text-blue-400 py-[4px] px-[8px] border border-[rgba(255,255,255,.05)] text-[15px]">
                                            <i class="fa-solid fa-gem text-blue-500"></i> 
                                            Nível 1
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <span class=" bg-[#262626] px-2 py-1 rounded-[6px] text-[13px] text-white/70">
                                        Comissão {{ userData.affiliate_revenue_share_fake || userData.affiliate_revenue_share || 0 }}%
                                    </span>
                                </div>
                                <div class="w-full h-2 bg-[#1a4013] rounded mt-2">
                                    <div class="h-2 bg-green-500 rounded" style="width:0%;"></div>
                                </div>
                                <div class="text-white/50 mt-1 flex justify-between text-[14px]">
                                    <span>0 / 1.000 XP</span>
                                    <span>Nível 1</span>
                                </div>
                                <button class="mt-[16px] w-full bg-[#28e504] text-black px-[16px] py-[8px] rounded-[8px] hover:bg-[#26d006] transition text-[13px]">
                                    Ver níveis
                                </button>
                            </div>
                        </div>
    
                        <!-- Card de referência -->
                        <div class="lg:col-span-3">
                            <div class="flex items-center justify-between mb-3">
                                <h2 class="text-white text-[16px]">Link de referência</h2>
                            </div>
                            <div class="border border-[rgba(255,255,255,.05)] rounded-xl p-6 flex flex-col justify-between">
                                <div>
                                    <div class="text-gray-300 text-sm mb-1 block">Seu Código</div>
                                    <input 
                                        type="text" 
                                        readonly 
                                        :placeholder="referencecode ? referencecode : 'Clique para gerar seu código'"
                                        class="w-full p-3 h-10 bg-gray-800 rounded border border-gray-600 focus:border-green-500 focus:ring-1 focus:ring-green-500 transition text-sm" 
                                        :value="referencecode"
                                        id="referenceCode"
                                    >
                                    <div class="w-[100%] h-[1px] bg-[rgba(255,255,255,.05)] mt-[20px]"></div>
                                    <div class="text-sm text-white/50 break-all pt-[10px]">
                                        {{ referencelink || 'Link será gerado após criar o código' }}
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 mt-2">
                                    <button 
                                        v-if="!referencecode" 
                                        @click.prevent="generateCode" 
                                        :disabled="isLoadingGenerate"
                                        class="bg-[#28e504] text-black px-[16px] py-[8px] rounded-[8px] hover:bg-[#26d006] transition disabled:opacity-50 text-[13px]"
                                    >
                                        {{ isLoadingGenerate ? 'Gerando...' : 'Criar Código' }}
                                    </button>
                                    <button 
                                        v-else 
                                        @click.prevent="copyCode"
                                        class="bg-[#28e504] text-black px-[16px] py-[8px] rounded-[8px] hover:bg-[#26d006] transition text-[13px]"
                                    >
                                        Copiar Código
                                    </button>
                                    <button 
                                        v-if="referencelink"
                                        @click.prevent="copyLink"
                                        class="p-2 text-white hover:text-lime-400 transition" 
                                        aria-label="Copiar link de afiliado"
                                    >
                                        <i class="fa-solid fa-share-nodes"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
    
                                        <!-- Loading state -->
                    <div v-else>
                        <div class="flex flex-col items-center justify-center text-center">
                            <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-green-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C0 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                            </svg>
                            <span class="mt-3 text-white block">Carregando...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </BaseLayout>
</template>


<script>
import BaseLayout from "@/Layouts/BaseLayout.vue";
import HttpApi from "@/Services/HttpApi.js";
import {useToast} from "vue-toastification";
import {useAuthStore} from "@/Stores/Auth.js";

export default {
    components: { BaseLayout },
    data() {
        return {
            isLoading: true,
            isLoadingGenerate: false,
            referencecode: '',
            referencelink: '',
        }
    },
    computed: {
        userData() {
            const authStore = useAuthStore();
            return authStore.user;
        }
    },
    methods: {
        copyCode: function() {
            const _toast = useToast();
            var inputElement = document.getElementById("referenceCode");
            inputElement.select();
            inputElement.setSelectionRange(0, 99999);
            document.execCommand("copy");
            _toast.success('Código copiado com sucesso');
        },
        copyLink: function() {
            const _toast = useToast();
            navigator.clipboard.writeText(this.referencelink).then(() => {
                _toast.success('Link copiado com sucesso');
            });
        },
        getCode: function() {
            const _this = this;
            _this.isLoading = true;

            HttpApi.get('profile/affiliates/')
                .then(response => {
                    if(response.data.code !== '' && response.data.code !== undefined && response.data.code !== null) {
                        _this.referencecode = response.data.code;
                    }
                    _this.referencelink = response.data.url;
                    _this.isLoading = false;
                })
                .catch(error => {
                    _this.isLoading = false;
                });
        },
        generateCode: function() {
            const _this = this;
            const _toast = useToast();
            _this.isLoadingGenerate = true;

            HttpApi.get('profile/affiliates/generate')
                .then(response => {
                    if(response.data.status) {
                        _this.getCode();
                        _toast.success('Seu código foi gerado com sucesso');
                    }
                    _this.isLoadingGenerate = false;
                })
                .catch(error => {
                    _toast.error('Erro ao gerar código');
                    _this.isLoadingGenerate = false;
                });
        }
    },
    created() {
        this.getCode();
    }
};
</script>

<style scoped>

</style>