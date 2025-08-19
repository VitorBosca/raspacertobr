<template>
    <div class="" :class="''">
        <div class="relative">
            <slot></slot>

            <FooterComponent v-once />
            <BottomNavComponent v-once/>
        </div>
    </div>
</template>

<script>
import FooterComponent from "@/Components/UI/FooterComponent.vue";
import BottomNavComponent from "@/Components/Nav/BottomNavComponent.vue";
import {useAuthStore} from "@/Stores/Auth.js";

export default {
    props: [],
    components: {BottomNavComponent, FooterComponent },
    data() {
        return {
            logo: '/assets/images/logo_verde.png',
            isLoading: false,
        }
    },
    computed: {
        isAuthenticated() {
            const authStore = useAuthStore();
            return authStore.isAuth;
        },
    },
};
</script>
