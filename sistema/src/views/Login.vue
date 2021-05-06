<template>
  <v-container>
    <v-layout>
      <v-row align="center"
             justify="center">
        <v-col cols="6" sm="12" md="6">
          <v-card elevation="2" class="card-login">
            Acesso ao sistema

            <v-form
              ref="form"
              :lazy-validation="true"
              v-model="valid"
              style="margin-top: 30px;">

              <v-text-field
                v-model="usuario"
                autocomplete="new-password"
                name="userField"
                maxlength="100"
                label="Usuário"
                required
                outlined
                :rules="emailRules"
                validate-on-blur
                @keyup.enter="login">
              </v-text-field>

              <v-text-field
                v-model="senha"
                autocomplete="new-password"
                name="passField"
                maxlength="100"
                label="Senha"
                :type="pass_visible ? 'text' : 'password'"
                required
                outlined
                validate-on-blur
                @keyup.enter="login">
                <v-icon slot="append" @click="pass_visible = !pass_visible"
                        :color="pass_visible ? 'primary' : undefined"> {{ pass_visible ? 'mdi-eye-off-outline' : 'mdi-eye-outline' }}
                </v-icon>
              </v-text-field>

              <v-btn
                color="blue"
                dark
                class="mr-4"
                @click="login">
                Entrar
              </v-btn>
            </v-form>
          </v-card>
        </v-col>
      </v-row>
    </v-layout>

  </v-container>
</template>

<script>

export default {
  name: 'Login',
  data: () => ({
    pass_visible: false,
    usuario: '',
    senha: '',
    valid: true,
    emailRules: [
      v => !!v || 'E-mail é obrigatório',
      v => /.+@.+\..+/.test(v) || 'E-mail precisa ser válido',
    ],
  }),
  methods: {
    login: function () {
      let params = {
        params: {
          email: this.usuario,
          password: this.senha
        }
      }

      if (this.$refs.form.validate()) {

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
          console.log(err)
          this.$session.destroy()

          this.$alert("Não foi possível fazer login. Verifique seu usuário e senha e tente novamente.")
        })
      }
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
  margin-top: 25vh;
}
</style>
