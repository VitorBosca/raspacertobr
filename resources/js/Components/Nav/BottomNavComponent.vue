<template>
    <nav class="fixed bottom-2 left-2 right-2 z-50 bg-[#171717] md:hidden rounded-[14px] shadow-lg flex justify-center border-t border-t-[#262626]" style="box-shadow: rgba(0, 0, 0, 0.267) 0px -2px 16px;">
        <ul class="flex w-full justify-around items-end px-2 py-1 h-[72px]" style="height: 72px;">
            <li class="flex-1 flex flex-col items-center">
                <button @click="$router.push('/')" 
                    :class="[
                        'flex flex-col items-center text-[11px] py-2 w-full group focus:outline-none',
                        ($route.path === '/' || $route.path === '/home') ? 'text-[#22cc19]' : 'text-white'
                    ]"
                >
                    <i class="fas fa-home text-lg mb-[2px]"></i>
                    <span class="font-semibold">Início</span>
                </button>
            </li>
            <li class="flex-1 flex flex-col items-center">
                <button @click="$router.push('/raspadinhas')" 
                    :class="[
                        'flex flex-col items-center text-[11px] py-2 w-full group focus:outline-none',
                        $route.path === '/raspadinhas' ? 'text-[#22cc19]' : 'text-white'
                    ]"
                >
                    <i class="fas fa-ticket-alt text-lg mb-[2px]"></i>
                    <span class="font-normal text-white/90]">Raspadinhas</span>
                </button>
            </li>
            <li class="flex-1 flex flex-col items-center">
                <div class="relative -translate-y-2 flex flex-col items-center pl-3">
                    <!-- Se logado -->
                    <button
                        v-if="isAuthenticated"
                        @click="openModalDeposit"
                        aria-label="Depositar"
                        class="bg-[#22cc19] border-4 border-[#13171b] w-16 h-16 rounded-full flex items-center justify-center text-white text-2xl shadow-lg transition-all duration-200 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-[#22cc19]/70">
                        <i class="fas fa-money-bill-wave"></i>
                    </button>

                    <!-- Se não logado -->
                    <button
                        v-else
                        @click="openRegisterModal"
                        aria-label="Registre-se"
                        class="bg-[#22cc19] border-4 border-[#13171b] w-16 h-16 rounded-full flex items-center justify-center text-white text-sm shadow-lg transition-all duration-200 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-[#22cc19]/70">
                        <i class="fa-solid fa-user-plus"></i>
                    </button>

                    <span class="text-white text-[11px] font-semibold mt-2">
                        {{ isAuthenticated ? "Depositar" : "Registre-se" }}
                    </span>
                </div>
            </li>
            <li class="flex-1 flex flex-col items-center">
                <button @click="$router.push('/indique')" 
                    :class="[
                        'flex flex-col items-center text-[11px] py-2 w-full group focus:outline-none',
                        $route.path === '/indique' ? 'text-[#22cc19]' : 'text-white'
                    ]"
                >
                    <i class="fas fa-gift text-lg mb-[2px]"></i>
                    <span class="font-normal text-white/90">Indique</span>
                </button>
            </li>
            <li class="flex-1 flex flex-col items-center">
                <button @click="$router.push('/profile/wallet')" 
                    :class="[
                        'flex flex-col items-center text-[11px] py-2 w-full group focus:outline-none',
                        $route.path === '/profile/wallet' ? 'text-[#22cc19]' : 'text-white'
                    ]"
                >
                    <i class="fa-solid fa-user text-lg mb-[2px]"></i>
                    <span class="font-normal text-white/90">Conta</span>
                </button>
            </li>
        </ul>
    </nav>
</template>

<script>
import { useAuthStore } from "@/Stores/Auth.js";

export default {
    props: [],
    components: {},
    data() {
        return {
            isLoading: false,
        };
    },
    setup(props) {
        return {};
    },
    computed: {
        isAuthenticated() {
        const authStore = useAuthStore();
        return authStore.isAuth;
        }
    },
    methods: {
        openModalDeposit() {
            if (window.modalDepositInstance) {
                window.modalDepositInstance.show(); // ou toggle()
            } else {
                console.error('Modal de depósito não inicializado');
            }
        },
        openRegisterModal() {
            const modalRegister = document.getElementById("modalElRegister");
            if (modalRegister) modalRegister.classList.remove("hidden");
        }
    },
    watch: {},
};
</script>

<style scoped>
/* Adicione estilos personalizados aqui, se necessário */
</style>
