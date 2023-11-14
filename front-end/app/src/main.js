import { createApp } from "vue"
import App from "./App.vue"
import router from "./router"
import axiosInstance from './services/http.js'
import VueAxios from 'vue-axios'

// Toast
import Toast from "vue-toastification"
import "vue-toastification/dist/index.css"

// Bootstrap
import "bootstrap/dist/css/bootstrap.min.css"
import "bootstrap/dist/js/bootstrap.min.js"

// App custom styles
import "@/assets/css/app/app.css"

// Pinia (state manager)
import { createPinia } from 'pinia'

// FontAwesome
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import Icons from '@/icons'

// sweetalert2
import VueSweetalert2 from 'vue-sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'

const app = createApp(App)

app.use(Toast, {})
app.use(VueAxios, axiosInstance)
app.use(createPinia())
app.use(VueSweetalert2)
app.use(router)

app.component('font-awesome-icon', FontAwesomeIcon)

app.provide('$http', app.config.globalProperties.$http)

app.mount('#app')
