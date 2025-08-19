<template>
    <div class="flex items-center gap-3">
        <button @click="toggleModal" type="button"
            class="ml-3 px-4 py-2 text-sm rounded-lg button-primary hidden md:flex items-center gap-2 transform duration-300 text-black">
            <i class="fa-solid fa-money-from-bracket"></i>
            <span class="hidden md:block">Sacar</span>
        </button>

        <!-- Modal -->
        <div id="modalElWithdraw" tabindex="-1" aria-hidden="true"
            class="fixed top-0 left-0 right-0 hidden w-full overflow-x-hidden overflow-y-auto md:inset-0 h-screen md:h-[calc(100%-1rem)] max-h-full z-[999999999]"
            style="z-index: 2147483647 !important;">
            <div class="relative w-full max-w-2xl max-h-full">
                <div class="flex flex-col md:justify-between md:px-6 pb-8 my-auto relative">
                    <WithdrawWidget ref="withdrawWidget" :showMobile="showMobile" :title="title" :isFull="isFull"
                        @close-modal="closeModal" />
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import { Modal } from 'flowbite';
import WithdrawWidget from "@/Components/Widgets/WithdrawWidget.vue";

export default {
    props: ['showMobile', 'title', 'isFull'],
    components: { WithdrawWidget },
    data() {
        return {
            modalWithdraw: null,
        }
    },
    mounted() {
        this.modalWithdraw = new Modal(document.getElementById('modalElWithdraw'), {
            placement: 'center',
            backdrop: 'dynamic',
            backdropClasses: 'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
            closable: true,
            onHide: () => {
                // Emitir evento para resetar form no widget
                this.$refs.withdrawWidget?.resetForm?.();
            },
            onShow: () => {
                // Forçar z-index do modal quando abrir
                setTimeout(() => {
                    this.forceModalZIndex();
                }, 10);
                
                // Verificar novamente após um tempo para garantir
                setTimeout(() => {
                    this.forceModalZIndex();
                }, 100);
            },
            onToggle: () => {
                // Modal toggle logic
            },
        });
    },
    methods: {
        toggleModal: function () {
            this.modalWithdraw.toggle();
            // Força z-index após toggle
            setTimeout(() => {
                this.forceModalZIndex();
            }, 50);
        },
        closeModal: function () {
            this.modalWithdraw.hide();
        },
        forceModalZIndex: function() {
            // Força z-index do modal para ficar na frente
            const modal = document.getElementById('modalElWithdraw');
            if (modal) {
                modal.style.setProperty('z-index', '2147483647', 'important');
                modal.style.setProperty('position', 'fixed', 'important');
            }
            
            // Força z-index baixo no backdrop
            const backdrops = document.querySelectorAll('[data-modal-backdrop], .fixed.inset-0.bg-gray-900');
            backdrops.forEach(backdrop => {
                backdrop.style.setProperty('z-index', '40', 'important');
            });
        }
    }
};
</script>

<style>
/* Estilos globais para o modal withdraw - Força z-index máximo */
#modalElWithdraw {
    z-index: 2147483647 !important;
    position: fixed !important;
}

#modalElWithdraw * {
    z-index: inherit !important;
}

/* Backdrop sempre com z-index baixo */
div[data-modal-backdrop],
.fixed.inset-0.bg-gray-900,
[data-modal-backdrop="modalElWithdraw"] {
    z-index: 40 !important;
}
</style>

<style scoped>
/* CSS Scoped adicional se necessário */
</style>
