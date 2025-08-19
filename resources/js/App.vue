<template>
  <NavTopComponent :simple="false" />

  <RouterView v-slot="{ Component, route }">
    <Transition name="page-opacity" mode="out-in">
      <div :key="route.name">
        <component :is="Component" />
      </div>
    </Transition>
  </RouterView>
</template>

<script>
import { onMounted } from "vue";
import { RouterView } from "vue-router";
import NavTopComponent from "@/Components/Nav/NavTopComponent.vue";

export default {
  components: { RouterView, NavTopComponent },
  setup() {
    onMounted(() => {
      // Você pode colocar lógica de carregamento aqui, se quiser.
    });

    return {};
  },
};
</script>

<style scoped>
/* Transição suave com fade */
.page-opacity-enter-active,
.page-opacity-leave-active {
  transition: opacity 0.3s ease;
}

.page-opacity-enter-from,
.page-opacity-leave-to {
  opacity: 0;
}
</style>
