<template>
    <div class="component-content">
        <div class="header-component d-flex align-items-center justify-content-between mb-5 CONTAINER">
            <h1 class="h1 h1-responsive fw-light text-white">{{ user.name || '' }}</h1>
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
                        <label for="name_input" class="m-0 text-white h-100 d-flex flex-col align-items-center">Nome</label>
                    </div>
                    <div class="col col-9">
                        <input type="text" id="name_input" class="form-control form-control-dark" placeholder="Ex.: João" required min="3" max="191" v-model="user.name">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col col-3">
                        <label for="email_input" class="m-0 text-white h-100 d-flex flex-col align-items-center">E-mail</label>
                    </div>
                    <div class="col col-9">
                        <input id="email_input" type="email" class="form-control form-control-dark" placeholder="Ex.: joao@email.com" required min="5" max="191" v-model="user.email">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col col-3">
                        <label for="password_input" class="m-0 text-white h-100 d-flex flex-col align-items-center">Senha</label>
                    </div>
                    <div class="col col-9">
                        <input id="password_input" type="password" class="form-control form-control-dark" placeholder="••••••" required min="6" max="191" v-model="user.password">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col col-3">
                        <label for="password_confirmation_input" class="m-0 text-white h-100 d-flex flex-col align-items-center">Repetir senha</label>
                    </div>
                    <div class="col col-9">
                        <input id="password_confirmation_input" type="password" class="form-control form-control-dark" placeholder="••••••" required min="6" max="191" v-model="user.password_confirmation">
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col col-3">
                        <label for="role_select" class="m-0 text-white h-100 d-flex flex-col align-items-center">Acesso</label>
                    </div>
                    <div class="col col-9">
                        <select name="role" id="role_select" class="form-control form-control-dark" required>
                            <option v-for="(role, index) in roles" :key="index" :selected="user.role === role" :value="role">
                                {{ role }}
                            </option>
                        </select>
                    </div>
                </div>

                <footer class="text-end">
                    <button @click="update" type="button" class="btn btn-primary text-uppercase shadow-sm fw-bold">
                        <span>ATUALIZAR</span>
                        <span v-if="updating" class="ms-2 spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
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

//!! props

const props = defineProps({
    id: {
        type: String,
        required: true,
    }
})

//!! data

const http  = inject('$http') // axios
const toast = useToast()
const userState = useState()
const roles = ref([])
const user  = ref({})
const payload = ref({})

// loader controller
const updating = ref(false)

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

async function getUser()
{
    try
    {
        const request = await http.get('/users'.concat('/', props.id), {
            headers: {
                Authorization: 'Bearer '.concat(getToken())
            }
        })

        if (request.status === 200) {
            user.value = request.data.data
            return
        }
    }
    catch (error)
    {
        toast.error('Erro ao buscar o usuário')
        console.log(error)
    }
}

async function update()
{
    try
    {
        updating.value = true
        const request = await http.put('/users'.concat('/', props.id, '/update'), user.value, {
            headers: {
                Authorization: 'Bearer '.concat(getToken())
            }
        })
        updating.value = false

        if (request.status === 200) {
            toast.success('Os dados do usuário foram atualizados')
            await getUser()
            return
        }

        toast.error('Erro ao atualizar os dados do usuário')
    }
    catch (error)
    {
        toast.error('Erro ao atualizar o usuário')
        console.log(error)
    }
}

onMounted(async () => {
    await getRoleOptions()
    await getUser()
})

</script>

<style scoped>
</style>