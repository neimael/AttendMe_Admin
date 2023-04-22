import './bootstrap';
import { CreateApp } from 'vue';
import app from './components/app.vue'
import router from './router/router';
createApp(app).use(router).mount('#app')