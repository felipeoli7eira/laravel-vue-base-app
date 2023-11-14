<script setup>
import { ref } from 'vue'
import * as auth from '@/services/auth.js'
import { useToast } from 'vue-toastification'
import { useRouter } from 'vue-router'

const toast  = useToast()
const router = useRouter()

const email = ref('')
const password = ref('')
let loginIsRunning = ref(false)

async function login()
{
  try
  {
    loginIsRunning.value = true

    const login = await auth.attempt(email.value, password.value)

    if (login === false) {
      loginIsRunning.value = false
      toast.error('Erro não identificado no login.')
      return false
    }

    if (login?.success === false) {
      loginIsRunning.value = false
      toast.error(login.message)
      return false
    }

    if (login?.status !== 200) {
      loginIsRunning.value = false
      toast.error('Erro não identificado no login.')
      return false
    }

    const token = login.data.data.bearer

    auth.setToken(token)

    toast.success(login.data.message)

    router.push({name: 'home'})

    return true
  }
  catch (error)
  {
    loginIsRunning.value = false
    console.warn(error)
    toast.error('Erro não identificado no login.')
    return false
  }
}

</script>

<template>
  <div class="login-form d-flex flex-column align-items-center justify-content-center vh-100 bg-light overflow-auto">
    <form class="d-flex flex-column align-items-center">
        <img src="@/assets/svg/kanji-preto.svg" class="d-inline-block mb-4" width="200">

        <div class="d-flex align-items-center mb-2 border p-1 px-4 bg-body rounded-pill mt-5">
            <img src="@/assets/svg/user.svg">
            <input name="email" @keydown.enter="login" class="form-control border-0 bg-none shadow-none" min="6" max="191" autofocus type="email" autocomplete="off" placeholder="E-mail" v-model="email">
        </div>

        <div class="d-flex align-items-center mb-2 border p-1 px-4 bg-body rounded-pill">
            <img src="@/assets/svg/lock.svg">
            <input name="password" @keydown.enter="login" id="password" class="form-control border-0 bg-none shadow-none" min="6" max="191" type="password" placeholder="••••••" autocomplete="off" v-model="password">
        </div>

        <!-- <p class="ps-3 m-0 fw-light text-center text-danger mb-2 w-100">Dados incorretos ou usuário não encontrado</p> -->

        <div class="d-grid w-100 mt-5">
          <button @click="login" class="btn btn-dark rounded-pill p-2 fw-bold shadow-sm" type="button">
            <span>LOGIN</span>
            <span v-if="loginIsRunning" class="ms-2 spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
          </button>
        </div>
    </form>
  </div>
</template>


<style scoped>
.btn-primary
{
  background-color: #0c3992;
  border-color: #123f99;
}

  .form-control
  {
    background-color: #fff !important;
    color: #1f1f1f !important;
  }

  .form-control::placeholder
  {
    color: rgba(41, 41, 41, 0.671) !important
  }

  input:-webkit-autofill,
  input:-webkit-autofill:hover,
  input:-webkit-autofill:focus,
  input:-webkit-autofill:active{
    background-color: #fff
  }
</style>