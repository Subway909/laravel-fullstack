<template>
  <div style="padding-left: 20px;">
    <v-form
      ref="form"
      v-model="valid"
      :lazy-validation="true">

      <v-row style="margin: 20px 0 30px 0;">

        <h2>{{ tipo_cadastro }} de Usuário</h2>
        <v-spacer></v-spacer>

        <v-btn color="green" dark @click="novo">
          <v-icon>mdi-plus</v-icon>
          Novo cadastro
        </v-btn>

      </v-row>

      <h3>Dados cadastrais</h3>
      <v-row>
        <!--nome, email, senha-->
        <v-col>
          <v-text-field
            v-model="model.name"
            :counter="100"
            maxlength="100"
            :rules="nameRules"
            label="Nome"
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
            label="E-mail"
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
                label="Data de nascimento"
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

      <h3>Telefones</h3>
      <v-row>
        <v-col>
          <v-text-field
            v-model="model.telefones.telefone_fixo"
            :rules="telefoneRules"
            label="Telefone fixo"
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
            label="Telefone celular"
            v-mask="'(##) #####-####'"
            required
            outlined
            validate-on-blur>
          </v-text-field>
        </v-col>
      </v-row>

      <h3>Endereço</h3>
      <v-row>
        <v-col>
          <v-text-field
            v-model="model.enderecos.cep"
            label="CEP"
            v-mask="'#####-###'"
            required
            outlined
            @blur="buscaCep(model.enderecos.cep)"
            validate-on-blur>
          </v-text-field>

          <v-text-field
            v-model="model.enderecos.numero"
            label="Número"
            required
            outlined
            validate-on-blur>
          </v-text-field>

          <v-text-field
            v-model="model.enderecos.cidade"
            label="Cidade"
            required
            outlined
            validate-on-blur>
          </v-text-field>
        </v-col>

        <v-col>
          <v-text-field
            v-model="model.enderecos.logradouro"
            label="Logradouro"
            required
            outlined
            validate-on-blur>
          </v-text-field>

          <v-text-field
            v-model="model.enderecos.bairro"
            label="Bairro"
            required
            outlined
            validate-on-blur>
          </v-text-field>

          <v-text-field
            v-model="model.enderecos.complemento"
            label="Complemento"
            required
            outlined
            validate-on-blur>
          </v-text-field>
        </v-col>
      </v-row>

      <v-file-input
        accept=".pem"
        label="Certificado"
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
        Enviar
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
      v => validate(v) || 'CPF inválido'
    ],
    nameRules: [
      v => !!v || 'Nome é obrigatório',
      v => (v && v.length >= 10) || 'O nome precisa ter pelo menos 10 caracteres',
    ],
    emailRules: [
      v => !!v || 'E-mail é obrigatório',
      v => /.+@.+\..+/.test(v) || 'E-mail precisa ser válido',
    ],
    telefoneRules: [
      v => !!v || 'Telefone é obrigatório',
      v => /^(?:(?:\+|00)?(55)\s?)?(?:\(?([1-9][0-9])\)?\s?)?(?:((?:9\d|[2-9])\d{3})-?(\d{4}))$/.test(v) || 'Telefone inválido'
    ],
    mensagemRules: [
      v => !!v || 'Mensagem é obrigatório',
      v => !(v && v.length > 1000) || 'Máximo de 1000 caracteres'
    ],
    arquivoRules: [
      v => !!v || 'Arquivo é obrigatório',
      value => !value || value.size < 500000 || 'Arquivo deve ser menor do que 500 kb'
    ]
  }),
  computed: {
    tipo_cadastro: function () {
      return this.edicao ? 'Edição ' : 'Cadastro'
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
              this.$alert("Usuário editado com sucesso.")
              this.$router.push('/usuarios')
            } else if (r.data.error) {
              this.$alert(r.data.error)
            }
          })
        } else {
          this.$http.post('store', dadosForm).then((r) => {
            if (r.status === 201) {
              this.$alert("Usuário cadastrado com sucesso.")
              this.$router.push('/usuarios')
            } else if (r.data.error) {
              this.$alert(r.data.error)
            }
          }).catch(err => {
            console.log(err)
            this.$alert('Ocorreu um erro: ' + err)
          })
        }
      }
    },
    preencherDados(dados) {
      if (dados) {
        this.model.id = dados.id
        this.model.name = dados.name
        this.model.cpf = dados.cpf
        this.model.data_nascimento = dados.data_nascimento
        this.model.email = dados.email

        this.model.enderecos.logradouro = dados.enderecos.logradouro
        this.model.enderecos.bairro = dados.enderecos.bairro
        this.model.enderecos.cep = dados.enderecos.cep
        this.model.enderecos.cidade = dados.enderecos.cidade
        this.model.enderecos.complemento = dados.enderecos.complemento
        this.model.enderecos.numero = dados.enderecos.numero
        this.model.enderecos.id = dados.enderecos.id

        this.model.telefones.telefone_celular = dados.telefones.telefone_celular
        this.model.telefones.telefone_fixo = dados.telefones.telefone_fixo
        this.model.telefones.id = dados.telefones.id
      }
    },
    novo() {
      this.edicao = false
      this.$refs.form.reset()
      this.model.id = ''
    }
  },
  mounted() {
    if (this.$route.params.dados) {
      this.preencherDados(this.$route.params.dados)
      this.edicao = true
    }

    console.log('edicao ' + this.edicao)

    let debug = false

    if (debug) {
      this.model.name = 'henzo teste'
      this.model.email = 'henzotestse@teste.com'
      this.model.cpf = '822.637.560-60'
      this.model.password = '123'
      this.model.data_nascimento = '2000-01-10'

      this.model.telefones.telefone_fixo = '(17) 3333-3333'
      this.model.telefones.telefone_celular = '(17) 99999-9999'

      this.model.enderecos.cep = '15085-895'
      this.model.enderecos.numero = '123'
      this.model.enderecos.logradouro = 'teste rua'
      this.model.enderecos.bairro = 'teste bairro'
      this.model.enderecos.numero = 'teste rua'
      this.model.enderecos.cidade = 'sao jose do rio preto'
      this.model.enderecos.complemento = 'casa 80'

      this.buscaCep(this.model.enderecos.cep)
    }
  }
}
</script>
