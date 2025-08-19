<template>
    <div class="w-full py-[52px] bg-[#0a0d0b]">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-start md:items-center justify-between pb-32 px-5 gap-5">
            <div>
                <div class="mb-8">
                    <div class="">
                        <a aria-current="page" href="/" class="router-link-active router-link-exact-active">
                            <img :src="`/storage/` + setting.software_logo_black" alt=""
                                class="h-10 mr-3 block dark:hidden " />
                            <img :src="`/storage/` + setting.software_logo_white" alt=""
                                class="h-10 mr-3 hidden dark:block" />
                        </a>
                    </div>
                </div>
                <div class="space-y-4">
                    <p class="text-sm text-white/50">© 2025 Todos os direitos reservados.</p>
                    <p class="text-sm text-white/50">{{ setting.software_name }} e outros jogos de azar são regulamentados e cobertos pela <br> nossa licença de jogos. Jogue com responsabilidade. </p>
                </div>
            </div>
            <div class="flex items-center gap-10">
                <div class="text-sm">
                    <p class="text-md text-white mb-5">Regulamentos</p>
                    <ul class="space-y-2">
                        <li class="text-white/60"><a href="">Jogo responsável</a></li>
                        <li class="text-white/60"><a href="">Política de Privacidade</a></li>
                        <li class="text-white/60"><a href="">Termos de Uso</a></li>
                    </ul>
                </div>
                <div class="text-sm">
                    <p class="text-md text-white mb-5">Ajuda</p>
                    <ul class="space-y-2">
                        <li class="text-white/60"><a href="">Perguntas Frequentes</a></li>
                        <li class="text-white/60"><a href="">Como Jogar</a></li>
                        <li class="text-white/60"><a href="">Suporte Técnico</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import HttpApi from "@/Services/HttpApi.js";
    import {useSettingStore} from "@/Stores/SettingStore.js";

    export default {
        props: [],
        components: {},
        data() {
            return {
                isLoading: false,
                year: new Date().getFullYear(),
                setting: null,
            }
        },
        setup(props) {


            return {};
        },
        computed: {

        },
        mounted() {

        },
        methods: {
            getSetting: function() {
                const _this = this;
                const settingStore = useSettingStore();
                const settingData = settingStore.setting;

                if(settingData) {
                    _this.setting = settingData;
                }
            },
        },
        created() {
            this.getSetting();
        },
        watch: {

        },
    };
</script>

<style scoped>

</style>
