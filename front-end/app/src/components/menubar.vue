<template>
<div class="card border-noround">
    <Menubar :model="items">
        <!-- <template #start></template> -->

        <template #item="{ item, props, hasSubmenu, root }">
            <router-link :to="{ name: '' }" class="flex align-items-center" v-bind="props.action">
                <span :class="item.icon" />
                <span class="ml-2">{{ item.label }}</span>
                <span v-if="item.shortcut" class="ml-auto border-1 surface-border border-round surface-100 text-xs p-1">{{ item.shortcut }}</span>
                <i v-if="hasSubmenu" :class="['pi pi-angle-down text-primary', { 'pi-angle-down ml-2': root, 'pi-angle-right ml-auto': !root }]"></i>
            </router-link>
        </template>

        <template #end>
            <router-link to="" class="p-button font-normal text-white no-underline mr-2">Conta</router-link>
            <Button v-ripple label="LogOut" icon="pi pi-sign-out"></Button>
        </template>
    </Menubar>
</div>
</template>

<script setup>
import { ref } from 'vue'
import Menubar from 'primevue/menubar'
import Button from 'primevue/button'

const items = ref([
    {
        label: 'Usuários',
        icon: 'pi pi-user',
        to: '/'
    }
])
</script>

<style scoped>
</style>