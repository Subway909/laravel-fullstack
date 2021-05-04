<template>
    <v-container>
      <v-row>
        <v-col>
          <v-card elevation="2" class="card-login">
            Acesso ao sistema

            <v-form
              ref="form"
              :lazy-validation="true"
              style="margin-top: 30px;">

              <v-text-field
                v-model="usuario"
                autocomplete="new-password"
                name="userField"
                :counter="100"
                maxlength="100"
                label="Usuário"
                required
                outlined
                validate-on-blur>
              </v-text-field>

              <v-text-field
                v-model="senha"
                autocomplete="new-password"
                name="passField"
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
          </v-card>
        </v-col>
      </v-row>

      <Loading ref="loading"/>

    </v-container>
</template>

<script>

import Rules from '../plugins/validation'
import Loading from "@/components/Loading";

export default {
  name: 'Login',
  components: {Loading},
  data: () => ({
    pass_visible: false,
    usuario: '',
    senha: ''
  }),
  computed: {
    rules: () => Rules
  },
  methods: {
    login: function () {
      const loading = this.$refs.loading

      loading.start()

      console.log('login')

      let params = {
        params: {
          email: this.usuario,
          password: this.senha
        }
      }

      this.$http.get('/login', params).then(res => {

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

          this.$router.replace('/bemvindo')
        }

      }).catch(err => {
        loading.done()
        console.log(err)
        this.$session.destroy()

        this.$alert("Não foi possível fazer login. Verifique seu usuário e senha e tente novamente.")
      })
    }
  },
  mounted() {
    console.log('loaded login')
  }
}
</script>


<style scoped>
.card-login {
  padding: 30px;
  margin-top: 100px;
}
</style>
