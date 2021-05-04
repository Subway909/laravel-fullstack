<template>
  <div style="padding-left: 20px;">
    <v-form
      ref="form"
      :lazy-validation="true">

      <v-text-field
        v-model="usuario"
        :counter="100"
        maxlength="100"
        label="Usuário"
        required
        outlined
        validate-on-blur>
      </v-text-field>

      <v-text-field
        v-model="senha"
        :counter="100"
        maxlength="100"
        label="Senha"
        :type="pass_visible ? 'text' : 'password'"
        required
        outlined
        validate-on-blur>
      </v-text-field>

      <v-btn
        color="success"
        class="mr-4"
        @click="login()">
        Entrar
      </v-btn>
    </v-form>
  </div>
</template>

<script>

import Rules from '../plugins/validation'

export default {
  name: 'Login',
  data: () => ({
    pass_visible: false,
    usuario: '',
    senha: ''
  }),
  computed: {
    rules: () => Rules
  },
  methods: {
    login: function() {
      console.log('login')

      let params = {
        params: {
          email: this.usuario,
          password: this.senha
        }
      }

      this.$http.get('/login', params).then((res) => {
        console.log(res)

        console.log(res.data.access_token)
        console.log(res.data.user.id)

        const token = res.data.access_token
        const id_usuario = res.data.user.id
        const name = res.data.user.name
        const email = res.data.user.email
        const login = true

        if (token && id_usuario) {
          this.$session.start()
          this.$session.set('id_usuario', id_usuario)
          this.$session.set('name', name)
          this.$session.set('token', token)
          this.$session.set('email', email)
          this.$session.set('login', login)
        }

      })
    }
  }
}
</script>
