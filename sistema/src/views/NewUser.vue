<template>
  <div style="padding-left: 20px;">
    <v-form
      ref="form"
      v-model="valid"
      :lazy-validation="true">

      <v-row style="margin: 20px 0 30px 0;">

        <h2>{{ tipo_cadastro }} user</h2>
        <v-spacer></v-spacer>

        <v-btn color="orange" dark @click="newUser">
          <v-icon>mdi-plus</v-icon>
          New user
        </v-btn>

      </v-row>

      <h3>User data</h3>
      <v-row>
        <!--nome, email, senha-->
        <v-col>
          <v-text-field
            v-model="model.name"
            :counter="100"
            maxlength="100"
            :rules="nameRules"
            label="Name"
            required
            outlined
            validate-on-blur>
          </v-text-field>

          <v-text-field
            v-model="model.email"
            :rules="emailRules"
            :counter="100"
            name="userField"
            autocomplete="new-password"
            label="Email"
            required
            outlined
            validate-on-blur>
          </v-text-field>

          <v-menu
            v-model="picker"
            :close-on-content-click="false"
            :nudge-right="40"
            transition="scale-transition"
            offset-y
            min-width="auto"
          >
            <template v-slot:activator="{ on, attrs }">
              <v-text-field
                v-model="model.data_nascimento"
                label="Date of birth"
                prepend-icon="mdi-calendar"
                readonly
                required
                v-bind="attrs"
                v-on="on"
              ></v-text-field>
            </template>
            <v-date-picker
              v-model="model.data_nascimento"
              @input="picker = false"
            ></v-date-picker>
          </v-menu>
        </v-col>

        <!--cpf, data nascimento-->
        <v-col>
          <v-text-field
            v-model="model.cpf"
            label="CPF"
            v-mask="'###.###.###-##'"
            :rules="cpfRules"
            required
            outlined
            validate-on-blur>
          </v-text-field>

          <v-text-field
            v-model="model.password"
            label="Senha"
            name="passField"
            autocomplete="new-password"
            :type="pass_visible ? 'text' : 'password'"
            required
            outlined
            validate-on-blur>
            <v-icon slot="append" @click="pass_visible = !pass_visible"
                    :color="pass_visible ? 'primary' : undefined">
              {{ pass_visible ? 'mdi-eye-off-outline' : 'mdi-eye-outline' }}
            </v-icon>
          </v-text-field>
        </v-col>
      </v-row>

      <h3>Phones</h3>
      <v-row>
        <v-col>
          <v-text-field
            v-model="model.telefones.telefone_fixo"
            :rules="telefoneRules"
            label="Landline"
            v-mask="'(##) ####-####'"
            required
            outlined
            validate-on-blur>
          </v-text-field>
        </v-col>

        <v-col>
          <v-text-field
            v-model="model.telefones.telefone_celular"
            :rules="telefoneRules"
            label="Mobile"
            v-mask="'(##) #####-####'"
            required
            outlined
            validate-on-blur>
          </v-text-field>
        </v-col>
      </v-row>

      <h3>Address</h3>
      <v-row>
        <v-col>
          <v-text-field
            v-model="model.enderecos.cep"
            label="ZIP Code"
            v-mask="'#####-###'"
            required
            outlined
            @blur="buscaCep(model.enderecos.cep)"
            validate-on-blur>
          </v-text-field>

          <v-text-field
            v-model="model.enderecos.numero"
            label="Number"
            required
            outlined
            validate-on-blur>
          </v-text-field>

          <v-text-field
            v-model="model.enderecos.cidade"
            label="City"
            required
            outlined
            validate-on-blur>
          </v-text-field>
        </v-col>

        <v-col>
          <v-text-field
            v-model="model.enderecos.logradouro"
            label="Street"
            required
            outlined
            validate-on-blur>
          </v-text-field>

          <v-text-field
            v-model="model.enderecos.bairro"
            label="Neighborhood"
            required
            outlined
            validate-on-blur>
          </v-text-field>

          <v-text-field
            v-model="model.enderecos.complemento"
            label="Misc."
            required
            outlined
            validate-on-blur>
          </v-text-field>
        </v-col>
      </v-row>

      <v-file-input
        accept=".pem"
        label="Certificate"
        required
        ref="inputUpload"
        v-model="model.arquivo"
        :rules="arquivoRules">
      </v-file-input>

      <v-btn
        :disabled="!valid"
        color="success"
        class="mr-4"
        @click="save">
        Save
      </v-btn>
    </v-form>
  </div>
</template>

<script>
import {validate} from 'gerador-validador-cpf'

export default {
  data: () => ({
    picker: false,
    pass_visible: false,
    dialog_msg: '',
    edicao: false,
    model: {
      id: '',
      name: '',
      email: '',
      password: '',
      data_nascimento: '',
      cpf: '',
      arquivo: null,
      telefones: {
        telefone_fixo: '',
        telefone_celular: '',
        id: ''
      },
      enderecos: {
        logradouro: '',
        bairro: '',
        numero: '',
        cep: '',
        cidade: '',
        complemento: '',
        id: '',
      }
    },
    valid: true,
    cpfRules: [
      v => validate(v) || 'Invalid CPF'
    ],
    nameRules: [
      v => !!v || 'Name is required',
      v => (v && v.length >= 10) || 'The name needs to be at leas 10 characters long',
    ],
    emailRules: [
      v => !!v || 'Email is required',
      v => /.+@.+\..+/.test(v) || 'Email needs to be valid',
    ],
    telefoneRules: [
      v => !!v || 'Phone is required',
      v => /^(?:(?:\+|00)?(55)\s?)?(?:\(?([1-9][0-9])\)?\s?)?(?:((?:9\d|[2-9])\d{3})-?(\d{4}))$/.test(v) || 'Invalid phone number'
    ],
    arquivoRules: [
      v => !!v || 'File is required',
      value => !value || value.size < 500000 || 'File needs to be less than 500 kb'
    ]
  }),
  computed: {
    tipo_cadastro: function () {
      return this.edicao ? 'Edit ' : 'New'
    }
  },
  methods: {
    buscaCep(cep) {
      if (cep.length === 9) {
        this.$http.get('cep', {params: {cep: cep}}).then((r) => {
          if (r.status === 200) {
            if (r.data.bairro && r.data.logradouro)
              this.model.enderecos.bairro = r.data.bairro

            if (r.data.logradouro)
              this.model.enderecos.logradouro = r.data.logradouro

            if (r.data.localidade)
              this.model.enderecos.cidade = r.data.localidade

            if (r.data.bairro)
              this.model.enderecos.bairro = r.data.bairro
          }
        }).catch(err => {
          console.error(err)
        })
      }
    },
    save() {
      if (this.$refs.form.validate()) {

        let dadosForm = new FormData()

        dadosForm.append('name', this.model.name)
        dadosForm.append('email', this.model.email)
        dadosForm.append('cpf', this.model.cpf)
        dadosForm.append('password', this.model.password)
        dadosForm.append('data_nascimento', this.model.data_nascimento)

        dadosForm.append('enderecos', JSON.stringify(this.model.enderecos))
        dadosForm.append('telefones', JSON.stringify(this.model.telefones))

        dadosForm.append('arquivo', this.model.arquivo)

        if (this.edicao) {
          let id = this.model.id

          dadosForm.append('id', id)

          this.$http.post(`update/${id}`, dadosForm).then((r) => {
            if (r.status === 200) {
              this.$alert("User successfully updated.")
              this.$router.push('/usuarios')
            } else if (r.data.error) {
              this.$alert(r.data.error)
            }
          })
        } else {
          this.$http.post('store', dadosForm).then((r) => {
            if (r.status === 201) {
              this.$alert("User successfully registered.")
              this.$router.push('/usuarios')
            } else if (r.data.error) {
              this.$alert(r.data.error)
            }
          }).catch(err => {
            console.log(err)
            this.$alert('An error occurred: ' + err)
          })
        }
      }
    },
    fillData(dados) {
      if (dados) {

        if (dados.id)
          this.model.id = dados.id

        if (dados.name)
          this.model.name = dados.name

        if (dados.cpf)
          this.model.cpf = dados.cpf

        if (dados.data_nascimento)
          this.model.data_nascimento = dados.data_nascimento

        if (dados.email)
          this.model.email = dados.email

        if (dados.enderecos) {
          if (dados.enderecos.logradouro)
            this.model.enderecos.logradouro = dados.enderecos.logradouro

          if (dados.enderecos.bairro)
            this.model.enderecos.bairro = dados.enderecos.bairro

          if (dados.enderecos.cep)
            this.model.enderecos.cep = dados.enderecos.cep

          if (dados.enderecos.cidade)
            this.model.enderecos.cidade = dados.enderecos.cidade

          if (dados.enderecos.complemento)
            this.model.enderecos.complemento = dados.enderecos.complemento

          if (dados.enderecos.numero)
            this.model.enderecos.numero = dados.enderecos.numero

          if (dados.enderecos.id)
            this.model.enderecos.id = dados.enderecos.id
        }

        if (dados.telefones) {
          if (dados.telefones.telefone_celular)
            this.model.telefones.telefone_celular = dados.telefones.telefone_celular

          if (dados.telefones.telefone_fixo)
            this.model.telefones.telefone_fixo = dados.telefones.telefone_fixo

          if (dados.telefones.id)
            this.model.telefones.id = dados.telefones.id
        }
      }
    },
    newUser() {
      this.edicao = false
      this.$refs.form.reset()
      this.model.id = ''
    }
  },
  mounted() {
    if (this.$route.params.dados) {
      this.fillData(this.$route.params.dados)
      this.edicao = true
    }
  }
}
</script>
