<script setup>
// * imports

import { ref } from 'vue';
import * as auth from '@/services/auth.js';
import { useToast } from 'vue-toastification';
import { useRouter } from 'vue-router';

import InputText from 'primevue/inputtext';
import Button from 'primevue/button';

// * props

const toast  = useToast();
const router = useRouter();

const email = ref('');
const password = ref('');
const email_field_error = ref(false)
const password_field_error = ref(false)

let loginIsRunning = ref(false);


// * methods

/**
 * valida os campos do formulario de login
 * @return {bool} true para campos devidamente preenchidos, false para o cenario oposto
*/
function formValidated()
{
  // * valida campos vazios

  if (email.value.trim().length === 0) {
    email_field_error.value = true;
    return false;
  }

  email_field_error.value = false;

  if (password.value.trim().length === 0) {
    password_field_error.value = true;
    return false;
  }

  password_field_error.value = false;

  // * se necesario no futuro, adicionar mais regras de validacao.
  return true;
}

/**
 * envia os dados preenchidos no formulario para o backend validar
 * @return {never} redireciona para o admin ou exibe um toast com o erro
*/
async function login()
{
  try
  {
    const form_validated = formValidated()
    if (!form_validated) {
      return false;
    }

    loginIsRunning.value = true;
    const login = await auth.attempt(email.value, password.value);
    loginIsRunning.value = false;

    if (login === false || login?.status !== 200) {
      toast.error('Erro não identificado no login.');
      return false;
    }

    if (login?.success === false) {
      toast.error(login.message);
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
      <span class="p-float-label">
          <InputText @keydown.enter="login" class="w-full" type="text" inputId="email" name="email" id="email" size="large" v-model="email" :class="{'p-invalid': email_field_error}" />
          <label for="email">E-mail</label>
      </span>

      <span class="p-float-label">
          <InputText @keydown.enter="login" class="w-full" type="password" inputId="password" name="password" id="password" size="large" v-model="password" :class="{'p-invalid': password_field_error}" />
          <label for="password">Senha</label>
      </span>

      <Button size="large" label="LOGIN" icon="pi pi-arrow-right" class="font-semibold" :loading="loginIsRunning" iconPos="right" @click="login" />
    </form>
  </div>
</template>


<style scoped>
</style>