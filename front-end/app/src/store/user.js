import { defineStore } from 'pinia'

const useState = defineStore('user_state', {
    state: () => ({
        user: {}
    }),

    getters: {
        getUser: state => state.user
    },

    actions: {
        setUser(user)
        {
            this.user = user
        },
    }
})

export { useState }