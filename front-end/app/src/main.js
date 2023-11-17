import { createApp } from 'vue';
import App from './App.vue';
import router from './router';

// axios
import axiosInstance from './services/http.js';
import VueAxios from 'vue-axios';

// normalize.css
import 'normalize.css/normalize.css';

// primeVue
import PrimeVue from 'primevue/config';
import 'primeflex/primeflex.css';
import 'primeicons/primeicons.css'

// toast
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';

// app custom styles
import "@/assets/css/app.css";

// pinia
import { createPinia } from 'pinia';

// sweetalert2
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

const app = createApp(App);

app.use(Toast, {});
app.use(VueAxios, axiosInstance);
app.use(createPinia());
app.use(VueSweetalert2);
app.use(router);
app.use(PrimeVue);

app.provide('$http', app.config.globalProperties.$http);
app.provide('$primevue', app.config.globalProperties.$primevue);

app.mount('#app');
