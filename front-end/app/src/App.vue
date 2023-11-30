<template>
  <div class="app-content">
    <menubar v-if="!isLoginScreen" />
    <router-view />
  </div>
</template>

<script setup>
import { RouterView, useRoute } from 'vue-router';
import { ref, watch } from 'vue';
import menubar from '@/components/menubar.vue';

let isLoginScreen = ref(false);
const route = useRoute();

watch(route, ({name}) => isLoginScreen.value = name === 'auth.login');
</script>

<style scoped>
</style>
