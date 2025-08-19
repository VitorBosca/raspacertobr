<template>
    <BaseLayout>
        <LoadingComponent :isLoading="isLoading">
            <div class="text-center">
                <span></span>
            </div>
        </LoadingComponent>

        <div v-if="!isLoading" class="p-[10px] px-[16px] bg-[#0a0d0b]">
            <div class="bg-[#171717] rounded-[16px]  min-h-[80svh] w-full px-5 pt-6 md:pt-7 md:pb-12">
                <div class="max-w-full md:max-w-7xl mx-auto">
                    <CompradoresAoVivo />
    
                    <!-- categories -->
                    <div v-if="categories" class="category-list">
                        <div class="flex mb-5 gap-4" style="max-height: 200px; overflow-x: auto; overflow-y: hidden;">
                            <div class="flex flex-row justify-between items-center w-full" style="min-width: 100%; white-space: nowrap;">
                                <RouterLink :to="{ name: 'casinosAll', params: { provider: 'all', category: category.slug }}" v-for="(category, index) in categories"
                                            class="flex flex-col justify-center items-center min-w-[80px] text-center">
                                    <div class="nui-mask nui-mask-hexed dark:bg-muted-800 flex size-16 scale-95 items-center justify-center dark:bg-[#1E293B] p-4 shadow-lg">
                                        <img :src="`/storage/`+category.image" alt="" width="35" class="">
                                    </div>
                                    <p class="mt-3">{{ $t(category.name) }}</p>
                                </RouterLink>
                            </div>
                        </div>
                    </div>
    
                    <div v-if="featured_games?.length">
                        <div class="list-header flex items-center justify-between gap-5">
                            <div class="relative my-4 w-full">
                                <div class="search-box border border-[#262626] focus-within:bg-white/10 transition-colors duration-300 rounded-lg flex items-center pl-5 pr-2 relative z-20 w-full">
                                    <svg width="1em" height="1em" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="icon size-5 opacity-70">
                                        <path stroke="#b6b6b6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 15 6 6m-11-4a7 7 0 1 1 0-14 7 7 0 0 1 0 14Z"></path>
                                    </svg>
                                    <input type="text" class="bg-transparent px-4 py-4 outline-none border-none focus:outline-none focus:border-none focus:ring-0 grow w-full text-white placeholder-white/40" placeholder="Pesquise..." autocomplete="off">
                                </div>
                            </div>
                        </div>
    
                        <div class="list-body grid grid-cols-1 md:grid-cols-3 gap-5 mb-16">
                            <CassinoGameCard
                                v-for="(game, index) in featured_games"
                                :index="index"
                                :title="game.game_name"
                                :cover="game.cover"
                                :gamecode="game.game_code"
                                :type="game.distribution"
                                :game="game"
                            />
                        </div>
                    </div>
    
                </div>
            </div>

        </div>
    </BaseLayout>
</template>

<script>
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import {onMounted, ref} from "vue";

import BaseLayout from "@/Layouts/BaseLayout.vue";
import MakeDeposit from "@/Components/UI/MakeDeposit.vue";
import {RouterLink, useRoute} from "vue-router";
import {useAuthStore} from "@/Stores/Auth.js";
import CassinoGameCard from "@/Pages/Cassino/Components/CassinoGameCard.vue";
import HttpApi from "@/Services/HttpApi.js";
import ShowCarousel from "@/Pages/Home/Components/ShowCarousel.vue";
import {useSettingStore} from "@/Stores/SettingStore.js";
import LoadingComponent from "@/Components/UI/LoadingComponent.vue";
import ShowProviders from "@/Pages/Home/Components/ShowProviders.vue";
import {searchGameStore} from "@/Stores/SearchGameStore.js";
import CustomPagination from "@/Components/UI/CustomPagination.vue";
import CompradoresAoVivo from './Components/CompradoresAoVivo.vue';

export default {
    props: [],
    components: {
        CustomPagination,
        CompradoresAoVivo,
        ShowProviders,
        LoadingComponent,
        ShowCarousel,
        CassinoGameCard,
        MakeDeposit,
        BaseLayout,
        RouterLink
    },
    data() {
        return {
            isLoading: true,

            /// swiper modules
            modules: [Navigation, Pagination, Autoplay],

            providers: null,

            featured_games: null,
            categories: null,
        }
    },
    setup(props) {
        const ckCarouselOriginals = ref(null)

        onMounted(() => {

        });

        return {
            ckCarouselOriginals
        };
    },
    computed: {
        searchGameStore() {
            return searchGameStore();
        },
        userData() {
            const authStore = useAuthStore();
            return authStore.user;
        },
        isAuthenticated() {
            const authStore = useAuthStore();
            return authStore.isAuth;
        },
        setting() {
            const settingStore = useSettingStore();
            return settingStore.setting;
        }
    },
    mounted() {

    },
    methods: {
        getFeaturedGames: async function() {
            const _this = this;
            return await HttpApi.get('featured/games')
                .then(async response =>  {


                    _this.featured_games = response.data.featured_games;
                    _this.isLoading = false;
                })
                .catch(error => {
                    Object.entries(JSON.parse(error.request.responseText)).forEach(([key, value]) => {
                        console.log(`${value}`);
                    });
                    _this.isLoading = false;
                });
        },
        initializeMethods: async function() {
            await this.getFeaturedGames();
        }

    },
    async created() {
        await this.initializeMethods();
    },
    watch: {


    },
};
</script>

