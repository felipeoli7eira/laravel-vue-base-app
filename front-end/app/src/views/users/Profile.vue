<template>
    <div class="component-content">
        <div class="header-component d-flex align-items-center justify-content-between mb-5 CONTAINER">
            <h1 class="h1 h1-responsive fw-light text-white">Meu perfil</h1>
            <!-- <nav>
                <router-link :to="{name: 'users.read'}" tag="button" class="btn btn-primary shadow">
                    <font-awesome-icon :icon="['fas', 'arrow-left']" class="me-2" /> VOLTAR
                </router-link>
            </nav> -->
        </div>

        <div class="container-fluid p-0">
            <form>
                <div class="row mb-3">
                    <div class="col col-3">
                        <label for="name_input" class="m-0 text-white h-100 d-flex flex-col align-items-center">Nome</label>
                    </div>
                    <div class="col col-9">
                        <input type="text" id="name_input" class="form-control form-control-dark" placeholder="Ex.: João" required min="3" max="191" v-model="payload.name">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col col-3">
                        <label for="email_input" class="m-0 text-white h-100 d-flex flex-col align-items-center">E-mail</label>
                    </div>
                    <div class="col col-9">
                        <input id="email_input" type="email" class="form-control form-control-dark" placeholder="Ex.: joao@email.com" required min="5" max="191" v-model="payload.email">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col col-3">
                        <label for="password_input" class="m-0 text-white h-100 d-flex flex-col align-items-center">Senha</label>
                    </div>
                    <div class="col col-9">
                        <input id="password_input" type="password" class="form-control form-control-dark" placeholder="••••••" required min="6" max="191" v-model="payload.password">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col col-3">
                        <label for="password_confirmation_input" class="m-0 text-white h-100 d-flex flex-col align-items-center">Repetir senha</label>
                    </div>
                    <div class="col col-9">
                        <input id="password_confirmation_input" type="password" class="form-control form-control-dark" placeholder="••••••" required min="6" max="191" v-model="payload.password_confirmation">
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col col-3">
                        <label for="role_select" class="m-0 text-white h-100 d-flex flex-col align-items-center">Papel</label>
                    </div>
                    <div class="col col-9">
                        <select name="role" id="role_select" class="form-control form-control-dark" required v-model="payload.role">
                            <option v-for="(role, index) in roles" :key="index" :value="role" :selected="payload.role === role">
                                {{ role }}
                            </option>
                        </select>
                    </div>
                </div>

                <footer class="text-end">
                    <button :disabled="updating" @click="update" type="button" class="btn btn-primary text-uppercase shadow-sm fw-bold">
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
import { checkAuth }   from '@/services/auth'

//!! data

const http      = inject('$http') // axios
const toast     = useToast()
const roles     = ref([])
const payload   = ref({})
const userState = useState()

// loader controller
const updating = ref(false)

//** component methods

 async function getRoleOptions()
 {
    try
    {
        console.log(userState.getUser)
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

async function update()
{
    try
    {
        updating.value = true
        const request = await http.put('/users'.concat('/', payload.value.id, '/update'), payload.value, {
            headers: {
                Authorization: 'Bearer '.concat(getToken())
            }
        })
        updating.value = false

        if (request.status === 200) {
            let user = await checkAuth()
            if (user !== false) {
                payload.value = user
                userState.setUser(user)
            }

            toast.success('Dados atualizados :)')
            return true
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
    console.log(userState.getUser)
    payload.value = {
        name:  userState.getUser.name,
        role:  userState.getUser.role,
        email: userState.getUser.email,
        id:    userState.getUser.id,
    }
    // await getUser()
})

</script>

<style scoped>
</style>