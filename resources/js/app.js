import { createApp } from 'vue';
import { createPinia } from 'pinia';
import router from './router';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';
import 'bootstrap-icons/font/bootstrap-icons.css'
import '@fortawesome/fontawesome-free/css/all.min.css';
import "ionicons/css/ionicons.min.css";

import App from './App.vue';
import axios from 'axios';

const app = createApp(App);
app.use(createPinia());
app.use(router);
const token = localStorage.getItem('token');

axios.defaults.withCredentials = true;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

if (token) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}
app.mount('#app');
