<template>
    <div class="ml-0 mt-[65px] md:mt-[65px]">
        <slot></slot>

        <FooterComponent v-once />
    </div>
</template>

<script>
import {initFlowbite} from 'flowbite';
import {onMounted} from "vue";
import FooterComponent from "@/Components/UI/FooterComponent.vue";
import BottomNavComponent from "@/Components/Nav/BottomNavComponent.vue";
import CookiesComponent from "@/Components/UI/CookiesComponent.vue";

export default {
    props: [],
    components: {CookiesComponent, BottomNavComponent, FooterComponent},
    data() {
        return {
            logo: '/assets/images/logo_verde.png',
            isLoading: false,
        }
    },
    setup(props) {
        onMounted(() => {
            initFlowbite();
        });

        return {};
    },
    computed: {},
    mounted() {


        // setTimeout(() => {
        //     this.isLoading = false
        // }, 3000)
    },
    methods: {},
    watch: {},
};
</script>
