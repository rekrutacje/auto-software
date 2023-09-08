import { createApp } from 'vue'
import axios from 'axios';
import App from './App.vue'

// Globalna konfiguracja Axios
axios.defaults.baseURL = 'http://localhost:81';

createApp(App).mount('#app')
