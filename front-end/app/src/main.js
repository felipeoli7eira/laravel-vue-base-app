import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

// axios
import axiosInstance from './services/http.js'
import VueAxios from 'vue-axios'

// normalize.css
import 'normalize.css/normalize.css'

// primeVue
import PrimeVue from 'primevue/config'
import 'primeflex/primeflex.css'
import 'primeicons/primeicons.css'
import Ripple from 'primevue/ripple'
import StyleClass from 'primevue/styleclass'

// toast
import Toast from 'vue-toastification'
import 'vue-toastification/dist/index.css'

// app custom styles
import "@/assets/css/app.css"

// pinia
import { createPinia } from 'pinia'

// sweetalert2
import VueSweetalert2 from 'vue-sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'

const app = createApp(App)

// * toast config
app.use(Toast, {})

// * vueaxios config
app.use(VueAxios, axiosInstance)

// * pinia config
app.use(createPinia())

// * vueSweetAlert config
app.use(VueSweetalert2)

// * vueRouter config
app.use(router)

// * primevue configs
app.use(PrimeVue, {
    ripple: true
})
app.directive('ripple', Ripple)
app.directive('styleclass', StyleClass)

// * providers
app.provide('$http', app.config.globalProperties.$http)
app.provide('$primevue', app.config.globalProperties.$primevue)

app.mount('#app')
