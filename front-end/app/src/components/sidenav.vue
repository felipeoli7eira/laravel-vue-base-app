<template>
    <div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark vh-100">
        <a href="" class="d-flex align-items-center justify-content-center w-100 mb-3 mb-md-0 me-md-auto text-white text-decoration-none p-3">
            <!-- <img src="@/assets/svg/kanji-branco.svg" width="100" class="d-block"> -->
        </a>

        <ul class="nav nav-pills flex-column mb-auto mt-5">

        <li class="nav-item mt-1">
            <router-link :to="{name: 'users.read'}" class="nav-link" aria-current="page">
                <span class="text-white">Usuários</span>
            </router-link>
        </li>
        </ul>

        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="@/assets/svg/user-white.svg" alt="" width="32" height="32" class="rounded-circle me-2">
                <strong>{{ userState.getUser.name }}</strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                <li>
                    <router-link :to="{name: 'profile'}" class="dropdown-item">Meu perfil</router-link>
                </li>
                <li>
                    <a href="#" class="dropdown-item" @click.prevent="logOut">LogOut</a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>

import http                    from '@/services/http.js'
import * as auth               from '@/services/auth.js'
import { useToast }            from 'vue-toastification'
import { useRouter, useRoute } from 'vue-router'
import { useState }            from '@/store/user'
import { onMounted }           from 'vue'

const userState = useState()
const toast     = useToast()
const router    = useRouter()

onMounted(async () => {
    const authenticated_user = await auth.checkAuth()

    if (authenticated_user) {
        userState.setUser(authenticated_user)
    }
})

async function logOut()
{
    try
    {
        const request = await http.post('/logout', undefined, {
            headers: {
                Authorization: 'Bearer '.concat(auth.getToken())
            }
        })

        if (request?.status === 200 && request?.data?.success) {
            auth.removeToken()
            router.push({name: 'auth.login'})
            return
        }

        toast.error(`Erro ao realizar logOut ${request?.data?.message}`)
    }
    catch (error)
    {
        console.log(error)
        toast.error('Erro ao realizar logOut')
    }
}

</script>

<style scoped>
.router-link-exact-active, .router-link-active
{
    background-color: #4d4d4e !important;
}
</style>