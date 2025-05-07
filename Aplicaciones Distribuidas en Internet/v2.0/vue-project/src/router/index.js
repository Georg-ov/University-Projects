import { createRouter, createWebHistory } from 'vue-router';
import LoginView from '../views/LoginView.vue';
import LogoutView from '../views/LogoutView.vue';
import RegisterView from '../views/RegisterView.vue';
import HomeView from '../views/HomeView.vue';
import UserProfile from '../views/UserProfile.vue'; // Importamos la vista del perfil
import CategoriaView from '../views/CategoriaView.vue'; 

//routado para redirigr a distintas vistas
const routes = [
  { path: '/', name: 'Home', component: HomeView },
  { path: '/login', name: 'Login', component: LoginView },
  { path: '/logout', name: 'Logout', component: LogoutView },
  { path: '/register', name: 'Register', component: RegisterView },
  { path: '/categoria/:id', component: CategoriaView }, 
  { path: '/perfil/:userId', name: 'UserProfile', component: UserProfile, props: true }, // Ruta dinámica para el perfil
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
