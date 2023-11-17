<template>
  <!-- <div class="">
    <div class="">
      <div v-if="!is_login_screen" class="">
        <sidenav />
      </div>

      <div class=""> -->
        <router-view />
      <!-- </div>
    </div>
  </div> -->
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
  /* background-color: #393c41 !important */
}
</style>
