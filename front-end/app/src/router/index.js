import { createRouter, createWebHistory } from 'vue-router';
import HomeView from '../views/HomeView.vue';

import * as auth from '@/services/auth.js';

const router = createRouter({
  history: createWebHistory(import.meta.env.VIE_BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
      meta: { requiresAuth: true }
    },

    // auth.login
    {
      path: '/login',
      name: 'auth.login',
      component: () => import('@/views/auth/Login.vue'),
      meta: { requiresAuth: false }
    },

    // users module
    {
      path: '/usuarios',
      meta: { requiresAuth: true },
      children: [
        {
          path: '',
          component: () => import('@/views/users/Read.vue'),
          name: 'users.read',
        },
        {
          path: ':id/edicao',
          name: 'users.update',
          component: () => import('@/views/users/Update.vue'),
          props: true
        },
        {
          path: 'cadastro',
          component: () => import('@/views/users/Create.vue'),
          name: 'users.create',
        },
      ]
    },

    // profile module
    {
      path: '/perfil',
      name: 'profile',
      component: () => import('@/views/users/Profile.vue'),
      meta: { requiresAuth: true }
    },
  ]
});

router.beforeEach(async (to, from) => {

  if (to.meta.requiresAuth) {
    const check_auth = await auth.checkAuth()

    if (check_auth === false) {
      return { name: 'auth.login' }
    }
  }
});

export default router;
