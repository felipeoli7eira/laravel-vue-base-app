<script setup>
import { ref } from 'vue';
import * as auth from '@/services/auth.js';
import { useToast } from 'vue-toastification';
import { useRouter } from 'vue-router';

import InputText from 'primevue/inputtext';
import Button from 'primevue/button';

const toast  = useToast();
const router = useRouter();

const email = ref('');
const password = ref('');
let loginIsRunning = ref(false);

async function login()
{
  try
  {
    loginIsRunning.value = true;

    const login = await auth.attempt(email.value, password.value);

    if (login === false) {
      loginIsRunning.value = false;
      toast.error('Erro não identificado no login.');
      return false;
    }

    if (login?.success === false) {
      loginIsRunning.value = false;
      toast.error(login.message);
      return false;
    }

    if (login?.status !== 200) {
      loginIsRunning.value = false;
      toast.error('Erro não identificado no login.');
      return false;
    }

    const token = login.data.data.auth;

    auth.setToken(token);

    toast.success(login.data.message);

    router.push({name: 'home'});

    return true;
  }
  catch (error)
  {
    loginIsRunning.value = false;
    console.warn(error);
    toast.error('Erro não identificado no login.');
    return false;
  }
}

</script>

<template>
  <div class="h-screen w-screen flex align-items-center justify-content-center border">
    <form @submit.prevent="submit" class="flex flex-column gap-4 col-10 sm:col-6 md:col-4 lg:col-2">

      <!-- <img src="@/assets/svg/vue.svg" class="login-logo-app"> -->

      <span class="p-float-label">
          <InputText class="w-full" type="text" inputId="email" name="email" id="email" size="large" v-model="email" />
          <label for="email">E-mail</label>
      </span>

      <span class="p-float-label">
          <InputText class="w-full" type="password" inputId="password" name="password" id="password" size="large" v-model="password" />
          <label for="password">Senha</label>
      </span>

      <Button size="large" label="LOGIN" icon="pi pi-arrow-right" class="font-semibold" :loading="loginIsRunning" iconPos="right" @click="login" />
    </form>
  </div>
</template>


<style scoped>
.login-logo-app
{
  max-width: 150px;
  display: inline-block;
  margin: 0 auto;
}
</style>