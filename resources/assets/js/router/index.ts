import Vue from "vue";
import Router from "vue-router";
import store from "@/store";
import {lazyLoadRoute} from "@/helpers/base-helper";

import BaseLayout from "@/views/layouts/BaseLayout.vue";
import PageNotFound from "@/views/PageNotFound.vue";
import AppError from "@/components/AppError.vue";

const LogIn = () => lazyLoadRoute(import(/* webpackChunkName */ '@/views/auth/LogIn.vue'));
const TaskIndex = () => lazyLoadRoute(import(/* webpackChunkName */ '@/views/task/Index.vue'));
const TaskCreate = () => lazyLoadRoute(import(/* webpackChunkName */ '@/views/task/Create.vue'));

const routes = [
    {
        path: '/error',
        name: 'error',
        component: AppError,
    },
    {
        path: '/',
        component: BaseLayout,
        children: [
            // Not Found
            {
                path: 'not-found',
                name: 'not-found',
                component: PageNotFound,
            },

            // Auth
            {
                path: '/',
                name: 'auth.login.root',
                component: LogIn,
                meta: {redirectIfAuthenticated: true},
            },
            {
                path: '/login',
                name: 'auth.login',
                component: LogIn,
                meta: {redirectIfAuthenticated: true},
            },
            {
                path: '/logout',
                name: 'auth.logout',
                meta: {requiresAuth: true},
                beforeEnter: (to: any, from: any, next: any) => {
                    store.commit('auth/destroyAuthState');

                    next('/login');
                }
            },
        ],
    },
    {
        path: '/tasks',
        meta: {requiresAuth: true},
        component: BaseLayout,
        children: [
            {
                path: '',
                name: 'tasks.index',
                component: PageNotFound,
            },
            {
                path: '/tasks/all',
                name: 'tasks.all',
                component: TaskIndex,
            },
            {
                path: '/tasks/today',
                name: 'tasks.today',
                component: TaskIndex,
            },
            {
                path: '/tasks/create',
                name: 'tasks.create',
                component: TaskCreate,
            },
            {
                path: '/tasks/:id/edit',
                name: 'tasks.edit',
                component: TaskCreate,
            },
        ]
    },
    {
        path: "*",
        beforeEnter: (to: any, from: any, next: any) => {
            next("/not-found");
        },
    }
];

Vue.use(Router);

const router = new Router({
    routes,
    mode: 'history',
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(m => m.meta.requiresAuth) && !store.getters['auth/isAuthenticated']) {
        next('/login');
    } else if (to.matched.some(m => m.meta.redirectIfAuthenticated) && store.getters['auth/isAuthenticated']) {
        next('/tasks/today');
    } else {
        next();
    }
});

export default router;
