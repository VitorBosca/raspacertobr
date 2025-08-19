<template>
    <div class="flex items-center gap-3">
        <button @click="toggleModalDeposit" type="button" class="px-2 md:px-4 py-2 text-sm rounded-lg button-primary flex items-center gap-2 transform duration-300 text-black">
            <i class="fa-solid fa-money-bill-transfer"></i>
            <span class="hidden md:block">Depositar</span>
        </button>
    </div>

    <div id="modalElDeposit" tabindex="-1" aria-hidden="true" class="fixed  top-0 left-0 right-0 z-[9999999999999999] hidden w-full overflow-x-hidden overflow-y-auto md:inset-0 h-screen md:h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-2xl max-h-full">
            <div class="flex flex-col md:justify-between md:px-6 pb-8 my-auto">
                <DepositWidget @close-modal="closeModalFromChild" />
            </div>
        </div>
    </div>
</template>

<script>
    import {useAuthStore} from "@/Stores/Auth.js";
    import DepositWidget from "@/Components/Widgets/DepositWidget.vue";
    import {onMounted} from "vue";
    import {initFlowbite} from "flowbite";

    export default {
        props: ['showMobile', 'title', 'isFull'],
        components: { DepositWidget },
        data() {
            return {
                isLoading: false,
                modalDeposit: null,
            }
        },
        setup(props) {
            onMounted(() => {
                initFlowbite();
            });

            return {};
        },
        computed: {
            isAuthenticated() {
                const authStore = useAuthStore();
                return authStore.isAuth;
            },
        },
        // mounted() {
        //     this.modalDeposit = new Modal(document.getElementById('modalElDeposit'), {
        //         placement: 'center',
        //         backdrop: 'dynamic',
        //         backdropClasses: 'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
        //         closable: true,
        //         onHide: () => {
        //             this.paymentType = null;
        //             // Remove qualquer backdrop residual
        //             setTimeout(() => {
        //                 const backdrops = document.querySelectorAll('.bg-gray-900\\/50, .dark\\:bg-gray-900\\/80, [class*="backdrop"]');
        //                 backdrops.forEach(backdrop => {
        //                     if (backdrop.style.zIndex === '40' || backdrop.classList.contains('fixed')) {
        //                         backdrop.remove();
        //                     }
        //                 });
        //             }, 100);
        //         },
        //         onShow: () => {

        //         },
        //         onToggle: () => {

        //         },
        //     });
        // },
        mounted() {
            // Cria e guarda a instância global do modal
            this.modalDeposit = new Modal(document.getElementById('modalElDeposit'), {
                placement: 'center',
                backdrop: 'dynamic',
                backdropClasses: 'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
                closable: true,
            });

            // Torna acessível globalmente
            window.modalDepositInstance = this.modalDeposit;
        },
        beforeUnmount() {

        },
        methods: {
            toggleModalDeposit: function() {
                this.modalDeposit.toggle();
            },
            closeModalFromChild: function() {
                this.modalDeposit.hide();
                
                // Remove manualmente qualquer backdrop que possa ter ficado
                setTimeout(() => {
                    const backdrops = document.querySelectorAll('.bg-gray-900\\/50, .dark\\:bg-gray-900\\/80, [class*="backdrop"]');
                    backdrops.forEach(backdrop => {
                        if (backdrop.style.zIndex === '40' || backdrop.classList.contains('fixed')) {
                            backdrop.remove();
                        }
                    });
                }, 100);
            },
        },
        created() {

        },
        watch: {

        },
    };
</script>

<style scoped>

</style>
