<template>
    <div class="component-content">
        <div class="header-component d-flex align-items-center justify-content-between mb-5">
            <h1 class="h1 h1-responsive fw-light text-white">Usuários do sistema</h1>
            <nav>
                <router-link :to="{name: 'users.create'}" tag="button" class="btn btn-primary shadow fw-bold">CADASTRAR</router-link>
            </nav>
        </div>

        <table class="table table-dark table-rounded shadow-sm m-0 table-hover">
            <thead>
                <tr>
                    <th scope="col" class="p-3 fw-bold border-0">NOME</th>
                    <th scope="col" class="p-3 fw-bold border-0">E-MAIL</th>
                    <th scope="col" class="p-3 fw-bold border-0">DATA DE CADASTRO</th>
                    <th scope="col" class="p-3 fw-bold border-0">ACESSO</th>
                    <th scope="col" class="p-3 fw-bold border-0"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(user, index) in users" :key="index">
                    <td class="p-3 border-0">{{ user.name }}</td>
                    <td class="p-3 border-0">{{ user.email }}</td>
                    <td class="p-3 border-0">{{ user.created_at }}</td>
                    <td class="p-3 border-0">
                        <span class="badge fw-normal" :class="{
                            'bg-primary':         user.role === 'super_admin',
                            'bg-light text-dark': user.role !== 'super_admin'
                        }">
                            {{ user.role }}
                        </span>
                    </td>

                    <td class="p-3 fw-light border-0">
                        <router-link tag="button" :to="{name: 'users.update', params: {id: user.id}}" class="btn p-0 m-0 px-2">
                            <img src="@/assets/svg/edit.svg" alt="ver" width="22">
                        </router-link>

                        <button v-if="userState.getUser.permissions.includes('delete_user')" type="button" @click="confirmDeleteUser(user)" class="btn p-0 m-0 px-2 delete-user-btn">
                            <img src="@/assets/svg/trash.svg" alt="ver" width="22">
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { reactive, ref, onMounted, inject } from 'vue'
import { useToast } from 'vue-toastification'
import { useState } from '@/store/user'
import { getToken } from '@/services/auth.js'

const userState = useState()
const toast  = useToast()

// data:

const http = inject('$http')
const swal = inject('$swal')
const users = ref([])

// methods:

async function getUsers()
{
    try
    {
        const request = await http.get('/users', {
            headers: {
                Authorization: 'Bearer '.concat(getToken())
            }
        })

        if (request.status !== 200 || !request?.data?.success) {

            feedback_message = 'Erro ao listar os usuários do sistema'

            if (error?.status === 403) {
                feedback_message = 'Você não tem permissão suficiente para acessar este recurso'
            }

            toast.error(feedback_message)
            return
        }

        const { data } = request

        users.value = data.data
        return true
    }
    catch (error)
    {
        let feedback_message = 'Erro ao listar os usuários'

        if (error.response.status === 403) {
            feedback_message = 'Você não tem permissão suficiente para acessar este recurso'
        }

        toast.error(feedback_message)
    }
}

onMounted(async () => {
    await getUsers()
})

async function confirmDeleteUser(user)
{
    const confirm = await swal.fire({
        title: 'Deletar usuário '.concat(user.name, '?'),
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'SIM, DELETAR',
        cancelButtonText: 'CANCELAR',
        reverseButtons: true,
        cancelButtonColor: '#333333',
        allowEnterKey: true,
        customClass: {
            title: 'fw-light h4'
        },
        showLoaderOnConfirm: true,
        preConfirm: async () => {
            return http.delete('/users/'.concat(user.id), {
                headers: {
                    Authorization: 'Bearer '.concat(getToken())
                }
            })
            .then(async (response) => {
                if (response.status !== 200) {
                    throw new Error(response.data.message)
                }

                toast.success('Usuário deletado')
                await getUsers()
                return true
            })
            .catch(error => {
                console.log(error)
                swal.showValidationMessage(error.message)
                return false
            })
        }
    });
}

</script>

<style scoped>

</style>