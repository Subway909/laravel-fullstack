import Vue from 'vue'
import VueRouter from 'vue-router'
import NewUser from '@/views/NewUser.vue'
import Users from '@/views/Users.vue'
import Welcome from '@/views/Welcome'
import Certificado from "@/views/Certificado";
import Login from '@/views/Login.vue'
import session from '../plugins/session'

Vue.use(VueRouter)

const routes = [
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {title: 'Login', public: true}
    },
    {
        path: '/newuser',
        name: 'New User',
        component: NewUser,
        meta: {title: 'New User'}
    },
    {
        path: '/users',
        name: 'Users',
        component: Users,
        meta: {title: 'Lista de Usuários'}
    },
    {
        path: '/certificado',
        name: 'Certificado',
        component: Certificado,
        meta: {title: 'Verificar Certificado'}
    },
    {
        path: '/logout',
        name: 'logout',
        redirect: (to) => {
            session.destroy()
            return { ...to, name: 'login' }
        }
    },
    {
        path: '/welcome',
        name: 'welcome',
        component: Welcome,
        meta: {title: 'Welcome'}
    },
]

const router = new VueRouter({
    routes
})

router.beforeEach((to, from, next) => {
    checkSession(to, next)
})

function checkSession (to, next) {
    if (session.exists()) {
        if (to.name === 'login') {
            next({ path: '/' })
        } else {
            next()
        }
    } else if (!to.meta.public) {
        next({ name: 'login' })
    } else {
        next()
    }
}

export default router
