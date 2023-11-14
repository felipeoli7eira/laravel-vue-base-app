<template>
  <div class="container-fluid admin-main-grid">
    <div class="row">
      <div v-if="!is_login_screen" class="col col-2 p-0 m-0 navigation-side">
        <sidenav />
      </div>

      <div class="admin-content content-side overflow-auto col m-0 p-0 vh-100" :class="col_content">
        <router-view />
      </div>
    </div>
  </div>
</template>

<script setup>
import { RouterView, useRoute } from 'vue-router'
import { ref, watch } from 'vue'
import sidenav from '@/components/sidenav.vue'

let is_login_screen = ref(false)
const route = useRoute()

watch(route, ({name}) => is_login_screen.value = name === 'auth.login')

function col_content()
{
  return {
    'col-10': !is_login_screen,
    'col-12': is_login_screen
  }
}

function logOut()
{
  console.log('...')
}
</script>

<style scoped>
.nav-link.active, .nav-link.active:hover
{
  background-color: #393c41 !important
}

body {
  font-family: 'Nunito', sans-serif;
}

.admin-content
{
  background-color: #1c1d20
}
</style>
