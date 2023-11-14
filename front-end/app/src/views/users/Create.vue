<template>
    <div class="component-content">
        <div class="header-component d-flex align-items-center justify-content-between mb-5 CONTAINER">
            <h1 class="h1 h1-responsive fw-light text-white">Cadastro de usuário</h1>
            <nav>
                <router-link :to="{name: 'users.read'}" tag="button" class="btn btn-primary shadow fw-bold">
                    <font-awesome-icon :icon="['fas', 'arrow-left']" class="me-2" /> VOLTAR
                </router-link>
            </nav>
        </div>

        <div class="container-fluid p-0">
            <form>
                <div class="row mb-3">
                    <div class="col col-3">
                        <label for="name_input" class="m-0 text-white h-100 d-flex flex-col align-items-center">
                            <span class="text-danger me-2 h5">*</span>
                            <span>Nome</span>
                        </label>
                    </div>
                    <div class="col col-9">
                        <input type="text" id="name_input" class="form-control form-control-dark" placeholder="Ex.: João" required min="3" max="191" v-model="user.name">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col col-3">
                        <label for="email_input" class="m-0 text-white h-100 d-flex flex-col align-items-center">
                            <span class="text-danger me-2 h5">*</span>
                            <span>E-mail</span>
                        </label>
                    </div>
                    <div class="col col-9">
                        <input id="email_input" type="email" class="form-control form-control-dark text-lowercase" placeholder="Ex.: joao@email.com" required min="5" max="191" v-model="user.email">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col col-3">
                        <label for="password_input" class="m-0 text-white h-100 d-flex flex-col align-items-center">
                            <span class="text-danger me-2 h5">*</span>
                            <span>Senha</span>
                        </label>
                    </div>
                    <div class="col col-9">
                        <input id="password_input" type="password" class="form-control form-control-dark" placeholder="••••••" required min="6" max="191" v-model="user.password">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col col-3">
                        <label for="password_confirmation_input" class="m-0 text-white h-100 d-flex flex-col align-items-center">
                            <span class="text-danger me-2 h5">*</span>
                            <span>Repetir senha</span>
                        </label>
                    </div>
                    <div class="col col-9">
                        <input id="password_confirmation_input" type="password" class="form-control form-control-dark" placeholder="••••••" required min="6" max="191" v-model="user.password_confirmation">
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col col-3">
                        <label for="role_select" class="m-0 text-white h-100 d-flex flex-col align-items-center">
                            <span class="text-danger me-2 h5">*</span>
                            <span>Acesso</span>
                        </label>
                    </div>
                    <div class="col col-9">
                        <select name="role" id="role_select" class="form-control form-control-dark" v-model="user.role">
                            <option :value="undefined">Selecione</option>
                            <option v-for="(role, index) in roles" :key="index" :value="role">{{ role }}</option>
                        </select>
                    </div>
                </div>

                <footer class="text-end">
                    <button @click="createUser" type="button" class="btn btn-primary text-uppercase shadow-sm fw-bold">
                        <span>CADASTRAR</span>
                    </button>
                </footer>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref , onMounted, inject, defineProps, reactive } from 'vue'
import { useToast }    from 'vue-toastification'
import { useState }    from '@/store/user.js'
import { getToken }    from '@/services/auth.js'
import { useRouter } from 'vue-router'

const router = useRouter()

//!! props

//!! data

const http  = inject('$http') // axios
const toast = useToast()
const roles = ref([])
const user  = ref({})

//** component methods

 async function getRoleOptions()
 {
    try
    {
        const request = await http.get('/roles', {
            headers: {
                Authorization: 'Bearer '.concat(getToken())
            }
        })

        if (request.status === 200) {
            roles.value = request.data.data
            return
        }
    }
    catch (error)
    {
        console.log(error)
    }
}

function payloadValidated()
{
    const name                  = document.querySelector('#name_input')
    const email                 = document.querySelector('#email_input')
    const password              = document.querySelector('#password_input')
    const password_confirmation = document.querySelector('#password_confirmation_input')
    const role                  = document.querySelector('#role_select')

    if (! ('name' in user.value) || user.value.name.trim() === '' || user.value.name.trim().length < 6) {
        toast.error('Preencha o campo "Nome" corretamente')
        name.classList.add('is-invalid')
        name.focus()

        return false
    }

    name.classList.remove('is-invalid')
    name.classList.add('is-valid')

    if (! ('email' in user.value) || user.value.email.trim() === '' || user.value.email.trim().length < 5) {
        toast.error('Preencha o campo "E-mail" corretamente')
        email.classList.add('is-invalid')
        email.focus()

        return false
    }

    email.classList.remove('is-invalid')
    email.classList.add('is-valid')

    if (! ('password' in user.value) || user.value.password.trim() === '' || user.value.password.trim().length < 6) {
        toast.error('Preencha o campo "Senha" corretamente')
        password.classList.add('is-invalid')
        password.focus()

        return false
    }

    password.classList.remove('is-invalid')
    password.classList.add('is-valid')

    if (! ('password_confirmation' in user.value) || user.value.password_confirmation.trim() === '' || user.value.password_confirmation.trim().length < 6) {
        toast.error('Preencha o campo "Repetir Senha" corretamente')
        password_confirmation.classList.add('is-invalid')
        password_confirmation.focus()

        return false
    }

    password_confirmation.classList.remove('is-invalid')
    password_confirmation.classList.add('is-valid')

    if (! ('role' in user.value) || user.value.role === undefined) {
        toast.error('Selecione uma opção no combo "Papel"')
        role.classList.add('is-invalid')
        role.focus()

        return false
    }

    role.classList.remove('is-invalid')
    role.classList.add('is-valid')

    return true
}

async function createUser()
{
    try
    {
        if (!payloadValidated()) {
            return false
        }

        const payload = user.value

        const request = await http.post('/users', payload, {
            headers: {
                Authorization: 'Bearer '.concat(getToken())
            }
        })

        if (request.status === 201) {
            toast.success(`Usuário ${user.value.name} cadastrado`)
            router.push({name: 'users.read'})
            return
        }

        toast.error('Erro ao cadastrar o usuário :( tente novamente')
    }
    catch (error)
    {
        toast.error('Erro ao cadastrar o usuário :(')
        console.log(error)
    }
}

onMounted(async () => {
    await getRoleOptions()
})

</script>

<style scoped>
</style>