import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import axiosInstance from './services/http.js'
import VueAxios from 'vue-axios'

// toast lib setup
import Toast from 'vue-toastification'
import 'vue-toastification/dist/index.css'

// vuesax lib setup
import Vuesax from 'vuesax3'
import 'vuesax3/dist/vuesax.css'

// material icons
import 'material-icons/iconfont/material-icons.css'


// App custom styles
import "@/assets/css/app/app.css"

// Pinia (state manager)
import { createPinia } from 'pinia'

// sweetalert2
import VueSweetalert2 from 'vue-sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'

const app = createApp(App)

app.use(Toast, {})
app.use(VueAxios, axiosInstance)
app.use(createPinia())
app.use(VueSweetalert2)
app.use(router)
app.use(Vuesax, {})

app.provide('$http', app.config.globalProperties.$http)

app.mount('#app')
