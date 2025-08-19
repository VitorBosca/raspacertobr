<template>
  <div :class="'mt-[65px]'">
    <div class="relative w-full bg-container min-h-[calc(100vh-80px)] rounded-lg">
      <Transition name="fade" mode="out-in">
        <div :key="$route.fullPath">
          <slot />
        </div>
      </Transition>

      <FooterComponent v-once />
      <BottomNavComponent v-once />
    </div>
  </div>
</template>

<script>
import { initFlowbite } from 'flowbite';
import { onMounted } from "vue";
import { useAuthStore } from "@/Stores/Auth.js";

import FooterComponent from "@/Components/UI/FooterComponent.vue";
import BottomNavComponent from "@/Components/Nav/BottomNavComponent.vue";

export default {
  components: {
    BottomNavComponent,
    FooterComponent,
  },
  setup() {
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
  methods: {
    getDayOfWeek() {
      const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
      return daysOfWeek[new Date().getDay()];
    },
  },
};
</script>

<style scoped>
/* Animação fade para transição entre páginas */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
