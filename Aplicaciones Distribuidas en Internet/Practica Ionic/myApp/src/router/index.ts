import { createRouter, createWebHistory } from '@ionic/vue-router';
import Home from '../views/HomePage.vue';
import Login from '../views/Login.vue'; 
import Register from '../views/Register.vue';
import CategoriaView from '../views/CategoriaView.vue';
import { Component } from 'ionicons/dist/types/stencil-public-runtime';

const routes = [
  {
    path: '/',
    redirect: '/login',
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
  },
  {
    path: '/register',
    name: 'Register',
    component: Register,
  },
  {
    path: '/home',
    name: 'Home',
    component: Home,
  },
  {
    path: '/categoria/:id',
    name: 'CategoriaView',
    component: CategoriaView,
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL), 
  routes,
});

export default router;
