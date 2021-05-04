import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import Novo from '../views/Novo.vue'
import Usuarios from '../views/Usuarios.vue'
import Login from '../views/Login.vue'

Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        name: 'Home',
        meta: {title: 'Laravel Fullstack'},
        component: Home
    },
    {
        path: '/novo',
        name: 'Novo',
        component: Novo,
        meta: {title: 'Novo contato'}
    },
    {
        path: '/usuarios',
        name: 'Usuarios',
        component: Usuarios,
        meta: {title: 'Lista de Usuários'}
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
        meta: {title: 'Login'}
    },
]

const router = new VueRouter({
    routes
})

export default router
