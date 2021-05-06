import Vue from 'vue'
import VueRouter from 'vue-router'
import Novo from '@/views/Novo.vue'
import Usuarios from '@/views/Usuarios.vue'
import BemVindo from '@/views/BemVindo'
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
        path: '/novo',
        name: 'Novo',
        component: Novo,
        meta: {title: 'Novo Usuário'}
    },
    {
        path: '/usuarios',
        name: 'Usuarios',
        component: Usuarios,
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
        path: '/bemvindo',
        name: 'bemvindo',
        component: BemVindo,
        meta: {title: 'Bem-vindo'}
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
