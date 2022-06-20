<template>
  <div style="padding-left: 20px;">
    <v-form
      ref="form"
      v-model="valid"
      :lazy-validation="true">

      <v-row style="margin: 20px 0 30px 0;">
        <h2>Check certificate</h2>
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
        Send
      </v-btn>
    </v-form>

    <v-card style="padding: 10px; margin-top: 30px;" color="#607d8b" dark  v-show="certificado_dn">
      <v-col>
        <h4>Certificate info</h4>
        <b>Subject DN: </b> {{ certificado_dn }} <br>
        <b>Issuer DN:</b> {{ certificado_issuer_dn }} <br>
        <b>Valid:</b> {{ `Not before ${certificado_not_before}` }} <br> {{
          `Not after ${certificado_not_after}`
        }}
      </v-col>
    </v-card>
  </div>
</template>

<script>

export default {
  data: () => ({
    certificado_dn: '',
    certificado_issuer_dn: '',
    certificado_not_before: '',
    certificado_not_after: '',
    model: {
      arquivo: null,
    },
    valid: true,
    arquivoRules: [
      v => !!v || 'Arquivo é obrigatório',
      value => !value || value.size < 500000 || 'Arquivo deve ser menor do que 500 kb'
    ]
  }),
  methods: {
    save() {
      if (this.$refs.form.validate()) {

        let dadosForm = new FormData()

        dadosForm.append('arquivo', this.model.arquivo)

        this.$http.post('certificado', dadosForm).then((r) => {
          if (r.status === 200) {
            this.certificado_dn = r.data.dn
            this.certificado_issuer_dn = r.data.issuer_dn
            this.certificado_not_before = r.data.validityNotAfter
            this.certificado_not_after = r.data.validityNotBefore

            this.$alert("Certificate successfully loaded")
          } else if (r.data.error) {
            this.$alert(r.data.error)
          }
        })

      }
    },
  }
}
</script>
