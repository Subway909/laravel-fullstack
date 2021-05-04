<template>
  <div style="padding-left: 20px;">
    <v-form
      ref="form"
      v-model="valid"
      :lazy-validation="true">

      <v-text-field
        v-model="model.nome"
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
        label="E-mail"
        required
        outlined=""
        validate-on-blur>
      </v-text-field>

      <v-text-field
        v-model="model.telefone"
        :rules="telefoneRules"
        label="Telefone"
        v-mask="['(##) #####-####', '(##) ####-####']"
        required
        outlined
        validate-on-blur>
      </v-text-field>

      <v-textarea
        label="Mensagem"
        v-model="model.mensagem"
        :rules="mensagemRules"
        maxlenght="1000"
        :counter="1000"
        required
        outlined
        validate-on-blur
      >
      </v-textarea>

      <v-file-input
        accept=".txt, .pdf, .doc, .docx, .odt"
        label="Arquivo"
        required
        ref="inputUpload"
        v-model="model.arquivo"
        :rules="arquivoRules"
        validate-on-blur>
      </v-file-input>

      <v-btn
        :disabled="!valid"
        color="success"
        class="mr-4"
        @click="save">
        Enviar
      </v-btn>
    </v-form>

    <v-dialog v-model="dialog" persistent max-width="400">
      <v-card>
        <v-card-title class="headline">Atenção!</v-card-title>
        <v-card-text>{{dialog_msg}}</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" text @click="dialog = false">OK</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-overlay :value="overlay">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </div>
</template>

<script>

  export default {
    data: () => ({
      overlay: false,
      dialog: false,
      dialog_msg: '',
      model: {
        name: '',
        email: '',
        telefone: '',
        mensagem: '',
        arquivo: null
      },
      valid: true,
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
    methods: {
      save() {
        if (this.$refs.form.validate()) {

          this.overlay = true

          let dadosForm = new FormData()

          dadosForm.append('nome', this.model.nome)
          dadosForm.append('email', this.model.email)
          dadosForm.append('telefone', this.model.telefone)
          dadosForm.append('mensagem', this.model.mensagem)

          dadosForm.append('arquivo', this.model.arquivo)

          this.$http.post('novoContato', dadosForm).then((r) => {
            if (r.data.ok) {

              this.dialog = true
              this.dialog_msg = 'Cadastro efetuado com sucesso!'
            }

            this.overlay = false
          })
        }
      }
    },
  }
</script>
