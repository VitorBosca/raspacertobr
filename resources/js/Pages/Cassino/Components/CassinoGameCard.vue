<template>
    <div class="bg-[transparent] relative rounded-xl  border border-gray-600/50 p-3 max-w-xl w-full mx-auto transition duration-300 max-h-[210px] group">
        <div class="absolute top-0 left-0 right-0 bottom-0 rounded-2xl bg-gradient-to-t from-[#29e5041f] from-[0%] to-transparent to-[35%] w-full shadow-[0_2px_0_0_transparent] group-hover:shadow-[0_2px_0_0_#28e504] duration-300 transition"></div>
        <div class="relative flex flex-col">
            <div class="rounded-lg overflow-hidden mb-5">
                <img :src="`/storage/${game.cover}`" :alt="game.game_name" class="w-full h-full object-contain object-center" loading="lazy">
            </div>
            <div class="flex items-start sm:items-center justify-between mb-6 flex-col sm:flex-row">
                <div class="text-white text-[16px] font-semibold line-clamp-1">{{ game.game_name }}</div>
                <div class="text-yellow-400 text-[12px] uppercase line-clamp-1">PRÊMIOS INCRÍVEIS</div>
            </div>
            <div class="flex-grow"></div>
            <div class="w-full flex items-center justify-between">
                <div>
                    <button @click="navigateToPlayPage" class="flex items-center text-black gap-2 button-primary transition duration-300 p-1 md:p-1 rounded-lg text-sm" type="button">
                        <i class="fa-solid fa-coin-vertical pl-1 md:pl-2"></i>
                        <span>Jogar</span>
                        <span class="bg-gray-900 rounded-md p-1 text-[12px] text-white">
                            <span class="text-[#05aa76] mr-1">R$</span>
                            <span>{{game.valor}}</span>
                        </span>
                    </button>
                </div>
                <div>
                    <button @click="navigateToPrizes" class="flex items-center gap-1 md:gap-2 text-white hover:text-[#00d492] transition duration-200">
                        <i class="fa-solid fa-gift group-hover:animate-shake"></i>
                        <span class="text-[10px] leading-[100%] line hover:text-[#00d492]">VER PRÊMIOS</span>
                        <i class="fa-solid fa-chevron-right text-[12px]"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { useRouter } from "vue-router";

export default {
    props: ['index', 'game'],
    setup(props) {
        const router = useRouter();

        const navigateToPlayPage = () => {
            router.push({ name: 'casinoPlayPage', params: { id: props.game.id, slug: props.game.game_code } });
        };

        const navigateToPrizes = () => {
            router.push({ name: 'casinoPlayPage', params: { id: props.game.id, slug: props.game.game_code } });
        };

        return {
            navigateToPlayPage,
            navigateToPrizes
        };
    }
};
</script>

<style scoped>
/* Adicione estilos personalizados aqui, se necessário */
</style>
