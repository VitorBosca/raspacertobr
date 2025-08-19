<template>
    <BaseLayout>
        <LoadingComponent :isLoading="isLoading">
            <div class="text-center">
                <span></span>
            </div>
        </LoadingComponent>

        <div v-if="!isLoading" class="p-[10px] pt-[0px] px-[16px] bg-[#0a0d0b]">
            <div class="bg-[#171717] rounded-[16px] min-h-[80svh] w-full px-5 pt-6 md:pt-7 md:pb-12 ">

                <!-- Banners carousel -->
                <div class="carousel-banners py-5 pt-[0px]">
                    <div class="max-w-full md:max-w-7xl mx-auto">
                        <section class="group/hero relative">
                            <Swiper
                                :modules="modules"
                                v-bind="swiperOptions"
                                class="swiper swiper-initialized swiper-horizontal md:rounded-lg overflow-hidden swiper-backface-hidden">
                                <SwiperSlide v-for="(banner, index) in banners" :key="index">
                                    <a :href="banner.link">
                                        <img :src="`/storage/`+banner.image" 
                                             :alt="`Banner ${index + 1}`"
                                             class="aspect-[7/2] w-full object-cover transition-all rounded-lg overflow-hidden">
                                    </a>
                                </SwiperSlide>
                            </Swiper>
                            
                            <!-- Custom Navigation Buttons -->
                            <svg width="1em" height="1em" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" 
                                 class="hero-prev-button size-6 absolute left-2 top-1/2 -translate-y-1/2 cursor-pointer z-20 opacity-0 transition-opacity group-hover/hero:opacity-100 drop-shadow text-white">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 4-8 8 8 8"></path>
                            </svg>
                            
                            <svg width="1em" height="1em" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" 
                                 class="hero-next-button size-6 absolute right-2 top-1/2 -translate-y-1/2 cursor-pointer z-20 opacity-0 transition-opacity group-hover/hero:opacity-100 drop-shadow -scale-x-100 text-white">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 4-8 8 8 8"></path>
                            </svg>
                        </section>
                    </div>
                </div>
    
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
                        <div class="list-header flex items-center justify-between mb-5 mt-[20px]">
                            <div class="flex items-center gap-2">
                                <svg width="1em" height="1em" fill="currentColor" class="bi bi-fire text-[#28e504] animate-pulse duration-700" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg"><path d="M8 16c3.314 0 6-2 6-5.5 0-1.5-.5-4-2.5-6 .25 1.5-1.25 2-1.25 2C11 4 9 .5 6 0c.357 2 .5 4-2 6-1.25 1-2 2.729-2 4.5C2 14 4.686 16 8 16m0-1c-1.657 0-3-1-3-2.75 0-.75.25-2 1.25-3C6.125 10 7 10.5 7 10.5c-.375-1.25.5-3.25 2-3.5-.179 1-.25 2 1 3 .625.5 1 1.364 1 2.25C11 14 9.657 15 8 15"></path></svg>
                                <h2 class="flex items-center gap-1 text-xl font-medium">{{ $t('Featured') }}</h2>
                            </div>
                            
                            <div class="flex items-center gap-2"><a href="/raspadinhas" class="flex items-center gap-2 text-white text-sm"><span>Ver mais</span><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 stroke-slate-50"><path d="M8.91003 19.9201L15.43 13.4001C16.2 12.6301 16.2 11.3701 15.43 10.6001L8.91003 4.08008" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path></svg></a>
                            </div>
                        </div>
    
                        <div class="list-body grid grid-cols-1 md:grid-cols-3 gap-5">
                            <CassinoGameCard
                                v-for="(game, index) in featured_games"
                                :index="index"
                                :title="game.game_name"
                                :cover="game.cover"
                                :valor="game.valor"
                                :gamecode="game.game_code"
                                :type="game.distribution"
                                :game="game"
                            />
                        </div>
                    </div>
    
                    <div class="w-full flex flex-col mt-10 items-center justify-center py-10"><div class="flex flex-col items-center text-center"><h1 class="text-2xl md:text-4xl text-white">Baixe agora nosso Aplicativo</h1><p class="text-white/40 text-sm md:text-base"> Descubra prêmios incríveis com o app da Raspadinha! </p><div class="flex items-center justify-center mt-5 gap-5"><button type="button" class="flex items-center justify-center px-0 md:px-2 py-2 md:py-3 rounded-full divide-x-2 bg-white cursor-pointer border-2 border-slate-50 hover:border-primary-500"><div class="px-4"><svg xmlns="http://www.w3.org/2000/svg" class="text-black w-10" fill="currentColor" viewBox="0 0 576 512"><path d="M420.6 301.9a24 24 0 1 1 24-24 24 24 0 0 1 -24 24m-265.1 0a24 24 0 1 1 24-24 24 24 0 0 1 -24 24m273.7-144.5 47.9-83a10 10 0 1 0 -17.3-10h0l-48.5 84.1a301.3 301.3 0 0 0 -246.6 0L116.2 64.5a10 10 0 1 0 -17.3 10h0l47.9 83C64.5 202.2 8.2 285.6 0 384H576c-8.2-98.5-64.5-181.8-146.9-226.6"></path></svg></div><div class="px-4 text-left text-slate-600 leading-5 text-xs md:text-base"><p>Instalar no</p><p class="font-bold">Android</p></div></button><!----></div><div class="w-full flex items-center justify-center mt-8"><img :src="`/assets/images/banners/baixe_app.Bv8s4KbW.png`" alt="Baixe o App"></div></div><!----></div>
    
                </div>
            </div>
        </div>
    </BaseLayout>
</template>

<script>
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import {onMounted, ref} from "vue";

import BaseLayout from "@/Layouts/BaseLayout.vue";
import MakeDeposit from "@/Components/UI/MakeDeposit.vue";
import {RouterLink, useRoute} from "vue-router";
import {useAuthStore} from "@/Stores/Auth.js";
import LanguageSelector from "@/Components/UI/LanguageSelector.vue";
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
        Swiper,
        SwiperSlide,
        LanguageSelector,
        MakeDeposit,
        BaseLayout,
        RouterLink
    },
    data() {
        return {
            isLoading: true,

            /// swiper modules
            modules: [Navigation, Pagination, Autoplay],

            /// swiper settings
            swiperOptions: {
                slidesPerView: 1,
                spaceBetween: 0,
                autoplay: {
                    delay: 6000,
                    disableOnInteraction: false,
                },
                loop: true,
                navigation: {
                    nextEl: '.hero-next-button',
                    prevEl: '.hero-prev-button',
                },
                pagination: {
                    clickable: true,
                    bulletClass: 'swiper-pagination-bullet !bg-white w-[15px] h-[3px] rounded-lg',
                    bulletActiveClass: 'swiper-pagination-bullet-active',
                },
                breakpoints: {
                    700: {
                        slidesPerView: 1,
                    },
                    1024: {
                        slidesPerView: 1,
                    },
                }
            },

            /// banners
            banners: null,
            bannersHome: null,

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
        getBanners: async function() {
            const _this = this;

            try {
                const response = await HttpApi.get('settings/banners');
                const allBanners = response.data.banners;

                _this.banners = allBanners.filter(banner => banner.type === 'carousel');
                _this.bannersHome = allBanners.filter(banner => banner.type === 'home');
            } catch (error) {
                console.error(error);
            } finally {

            }
        },
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
            await this.getBanners();
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

<style scoped>
/* Custom pagination bullet styling */
:deep(.swiper-pagination-bullet) {
    background-color: white !important;
    width: 15px !important;
    height: 3px !important;
    border-radius: 0.5rem !important;
    opacity: 0.5 !important;
}

:deep(.swiper-pagination-bullet-active) {
    opacity: 1 !important;
}

:deep(.swiper-pagination) {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
}

/* Custom navigation button hover effects */
.hero-prev-button:hover,
.hero-next-button:hover {
    transform: scale(1.1);
    transition: all 0.2s ease-in-out;
}

.hero-prev-button,
.hero-next-button {
    transition: all 0.2s ease-in-out;
}
</style>


