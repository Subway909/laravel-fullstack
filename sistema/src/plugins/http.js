import Vue from 'vue'
import axios from 'axios'
import Qs from 'qs'
import VueAxios from 'vue-axios'
import { events } from './events'
import router from '../router'
import { get, includes } from 'lodash'
import VueTheMask from 'vue-the-mask'

const http = axios.create({
  baseURL: process.env.VUE_APP_WS_ROOT
})

Vue.use(VueAxios, http)
Vue.use(VueTheMask)

let requestId = 0

http.interceptors.request.use(function (config) {
  if (!('requestId' in config)) {
    config['requestId'] = ++requestId
  }

  const session = events.$session

  if (session.exists()) {
    // envia o token JWT
    config.headers['Authorization'] = `Bearer ${session.get('token')}`
  }

  const options = config['options'] = {
    silent: false,
    ...config['options']
  }

  if (!options.silent) {
    events.$emit('httpStart')
  }

  if (!config.url) {
    config.url = '/'
  }

  config.paramsSerializer = params => {
    return Qs.stringify(params, {
      arrayFormat: 'brackets',
      encode: true
    })
  }

  return config
}, error => {
  const silent = get(error, 'config.options.silent', false)
  if (!silent) {
    events.$emit('httpDone')
  }
  return Promise.reject(error)
})

http.interceptors.response.use(function (response) {
  const silent = get(response, 'config.options.silent', false)
  if (!silent) {
    events.$emit('httpDone')
  }

  const session = events.$session

  const token = response.headers['authorization']
  if (token) {
    session.set('token', token.replace(/^(Bearer )/, ''))
  }

  return response
}, error => {
  const silent = get(error, 'config.options.silent', false)
  if (!silent) {
    events.$emit('httpDone')
  }

  const session = events.$session

  const status = get(error, 'response.status', 500)

  if (session.exists() && includes([401, 403], status)) {
    router.replace('/login', () => {})
    session.destroy()
    return Promise.reject(error)
  } else {
    return Promise.reject(error)
  }
})

router.beforeEach((to, from, next) => {
  next()
})
