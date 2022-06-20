<template>
  <v-container>
    <v-layout>
      <v-row justify="end">
        <div class="text-center">
          <v-dialog
            v-model="dialog"
            width="500"
          >
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                color="blue"
                dark
                v-bind="attrs"
                v-on="on"
              >
                <v-icon>
                  mdi-chat
                </v-icon>
              </v-btn>
            </template>

            <v-card>
              <v-card-title class="text-h5 grey lighten-2">
                Hello there
              </v-card-title>

              <v-card-text>
                <p class="mt-5">This is a little CRUD frontend application built with Vue and Vuetify. After login you can register a user, view the list of users, and submit a .pem certificate to view the certificate info.</p>

                <p class="mt-5">You can login using <b>login@email.com</b> and password <b>123456</b></p>

                Click <b><a @click="fillValues()">here</a></b> to fill with these values
              </v-card-text>

              <v-divider></v-divider>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  color="primary"
                  text
                  @click="dialog = false"
                >
                  CLOSE
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </div>
      </v-row>

    </v-layout>
    <v-layout>
      <v-row align="center"
             justify="center">
        <v-col cols="6" sm="12" md="6">
          <v-card elevation="2" class="card-login">
            Please log in with your email and password

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
                label="Email"
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
                label="Password"
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
                Log in
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
    dialog: false,
    pass_visible: false,
    usuario: '',
    senha: '',
    valid: true,
    emailRules: [
      v => !!v || 'Email is required',
      v => /.+@.+\..+/.test(v) || 'Email needs to be valid',
    ],
  }),
  methods: {
    fillValues: function() {
      this.usuario = 'login@email.com'
      this.senha = '123456'
      this.dialog = false
    },
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

            this.$router.replace('/welcome')
          }

        }).catch(err => {
          console.log(err)
          this.$session.destroy()

          this.$alert("Unable to log in. Check your email and password and try again.")
        })
      }
    }
  }
}
</script>


<style scoped>
.card-login {
  padding: 30px;
  margin-top: 25vh;
}
</style>
