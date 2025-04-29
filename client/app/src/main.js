import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from './App.vue';
import Toast from 'vue-toastification';
import "vue-toastification/dist/index.css";
import router from './router';
import 'bootstrap-icons/font/bootstrap-icons.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';

const pinia = createPinia();
createApp(App).use(pinia).use(router).use(Toast).mount('#app');