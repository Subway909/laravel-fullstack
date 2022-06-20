<template>
  <v-container>
    <v-layout column class="card table">
      <h3 class="mb-5">User list</h3>

      <v-data-table
        v-show="buscou"
        :headers="headers"
        :items="usuarios"
        :items-per-page="5"
        :item-class= "row_classes"
        class="elevation-1"
      >
        <template v-slot:item.data_nascimento="{ item }">
          {{ item.data_nascimento | moment }}
        </template>

        <template v-slot:item.detalhes="{ item }">
          <v-btn @click="detalhes(item.id)">
            Info
          </v-btn>
        </template>

        <template v-slot:item.editar="{ item }">
          <v-btn @click="editar(item.id)" color="blue" dark>
            <v-icon>mdi-pencil</v-icon>
          </v-btn>
        </template>

        <template v-slot:item.excluir="{ item }">
          <v-btn @click="excluir(item.id)" color="red" dark>
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </template>
      </v-data-table>

      <v-row class="mt-10" v-show="exibirDetalhes">
        <v-col>
          <v-flex><h4>User info</h4></v-flex>

          <v-card mt-5 style="padding: 10px;" color="#607d8b" dark>
            <v-app-bar
              flat
              clipped-right
              color="rgba(0, 0, 0, 0)"
            >
              <v-app-bar-nav-icon color="white" style="position: absolute; right: 25px;" @click="exibirDetalhes = !exibirDetalhes">
                <v-icon>mdi-close</v-icon>
              </v-app-bar-nav-icon>
            </v-app-bar>
            <v-row wrap>

              <v-col cols="4">
                <h4>User data</h4>

                <b>Name:</b> {{ selectedUser.name }} <br>
                <b>CPF:</b> {{ selectedUser.cpf }} <br>
                <b>Email:</b> {{ selectedUser.email }} <br>
                <b>Date of birth:</b> {{ selectedUser.data_nascimento | moment }} <br>
              </v-col>
              <v-col cols="4">
                <h4>Phones</h4>
                <b>Landline:</b> {{ selectedUserTelefone.telefone_fixo}} <br>
                <b>Mobile:</b> {{ selectedUserTelefone.telefone_celular }} <br>
              </v-col>
              <v-col cols="4">
                <h4>Address</h4>

                <b>Street:</b> {{ selectedUserEndereco.logradouro}} <br>
                <b>Number:</b> {{ selectedUserEndereco.numero}} <br>
                <b>Neighborhood:</b> {{ selectedUserEndereco.bairro}} <br>
                <b>Msic.:</b> {{ selectedUserEndereco.complemento}} <br>
                <b>ZIP Code:</b> {{ selectedUserEndereco.cep}} <br>
                <b>City:</b> {{ selectedUserEndereco.cidade}} <br>
              </v-col>
              <v-col cols="12">
                <h4>Certificate information</h4>
                <v-flex v-if="podeVerCertificado">
                  <b>Subject DN: </b> {{ selectedUser.certificado_dn }} <br>
                  <b>Issuer DN:</b> {{ selectedUser.certificado_issuer_dn }} <br>
                  <b>Valid:</b> {{ `Not before ${selectedUser.certificado_not_before}` }} <br> {{ `Not after ${selectedUser.certificado_not_after}` }}
                </v-flex>
                <v-flex v-if="!podeVerCertificado && temCertificado">
                  This certificate belongs to someone else. You need to be logged in as this user to see the certificate information.
                </v-flex>
              </v-col>
            </v-row>

          </v-card>
        </v-col>
      </v-row>
    </v-layout>
  </v-container>
</template>

<script>

import moment from 'moment'
import _ from 'lodash'

export default {
  name: "Users",
  data: () => ({
    exibirDetalhes: false,
    buscou: false,
    selectedUser: [],
    selectedUserTelefone: {},
    selectedUserEndereco: {},
    usuarios: [
      {
        id: "",
        name: "",
        cpf: "",
        email: "",
        data_nascimento: "",
      }
    ],
    headers: [
      {
        text: 'Name',
        align: 'start',
        value: 'name',
      },
      {text: 'CPF', value: 'cpf'},
      {text: 'Email', value: 'email'},
      {text: 'Date of birth', value: 'data_nascimento'},
      {Text: '', value: 'info'},
      {Text: 'Edit', value: 'editar'},
      {Text: 'Delete', value: 'excluir'}
    ]
  }),
  computed: {
    userLogado: function() {
      return this.$session.get('id_usuario')
    },
    podeVerCertificado: function() {
      return this.userLogado === this.selectedUser.id && this.selectedUser.certificado
    },
    temCertificado: function() {
      return this.selectedUser.certificado
    }
  },
  filters: {
    moment: function (date) {
      return moment(date).format('DD/MM/YYYY')
    }
  },
  mounted() {
    this.listar()
  },
  methods: {
    listar() {
      this.$http.get('/index').then((r) => {
        this.usuarios = r.data
        this.buscou = true
      }).catch(err => {
        console.error(err)
      })
    },
    detalhes(id) {
      this.exibirDetalhes = true

      this.selectedUser = this.usuarios.find((s) => s.id === id)

      this.selectedUserTelefone = _.find(this.usuarios, ['id', id]).telefones
      this.selectedUserEndereco = _.find(this.usuarios, ['id', id]).enderecos

      if (!this.selectedUserTelefone)
        this.selectedUserTelefone = {}

      if (!this.selectedUserEndereco)
        this.selectedUserEndereco = {}

    },
    editar(id) {

      let params = {
        dados: this.usuarios.find((s) => s.id === id)
      }

      this.$router.push({ name: 'New User', params })
    },
    excluir(id) {
      this.$http.delete(`delete/${id}`).then((r) => {
        if (r.data.error) {
          this.$alert(r.data.error)
        } else if (r.data.id) {
          this.$alert("Usuário deletado com sucesso.")
          this.exibirDetalhes = false
          this.listar()
        }
      })
    },
    row_classes(item) {
      // seta cor diferente na linha da tabela correspondente ao usuário logado
      if (item.id === this.userLogado) {
        return "bg-user";
      }
    }
  }
}

</script>

<style>
  .bg-user {
    background: #f27474 !important;
    color: white;
    font-weight: bold;
  }
</style>
