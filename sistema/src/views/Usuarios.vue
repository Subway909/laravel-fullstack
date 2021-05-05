<template>
  <v-container>
    <v-layout column class="card table">
      <v-flex mb-5>Listagem de usuários</v-flex>

      <v-data-table
        v-show="buscou"
        :headers="headers"
        :items="usuarios"
        :items-per-page="5"
        class="elevation-1"
      >
        <template v-slot:item.data_nascimento="{ item }">
          {{ item.data_nascimento | moment }}
        </template>

        <template v-slot:item.btn="{ item }">
          <v-btn @click="detalhes(item.id)">
            Detalhes
          </v-btn>
        </template>
      </v-data-table>

      <v-row class="mt-10" v-show="exibirDetalhes">
        <v-col>
          <v-flex><h2>Listagem de usuários</h2></v-flex>

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

              <v-col cols="6">
                <h4>Dados cadastrais</h4>

                <b>Nome:</b> {{ selectedUser.name }} <br>
                <b>CPF:</b> {{ selectedUser.cpf }} <br>
                <b>E-mail:</b> {{ selectedUser.email }} <br>
                <b>Data de nascimento:</b> {{ selectedUser.data_nascimento | moment }} <br>
              </v-col>
              <v-col cols="6">
                <h4>Telefone</h4>
                <b>Telefone Fixo:</b> {{ selectedUserTelefone.telefone_fixo}} <br>
                <b>Telefone Celular:</b> {{ selectedUserTelefone.telefone_celular }} <br>
              </v-col>
              <v-col cols="6">
                <h4>Endereço</h4>

                <b>Logradouro:</b> {{ selectedUserEndereco.logradouro}} <br>
                <b>Número:</b> {{ selectedUserEndereco.numero}} <br>
                <b>Bairro:</b> {{ selectedUserEndereco.bairro}} <br>
                <b>Complemento:</b> {{ selectedUserEndereco.complemento}} <br>
                <b>Cep:</b> {{ selectedUserEndereco.cep}} <br>
                <b>Cidade:</b> {{ selectedUserEndereco.cidade}} <br>
              </v-col>
              <v-col cols="6">
                <h4>Dados do certificado</h4>
                <v-flex v-if="userLogado === selectedUser.id">
                  <b>Arquivo: </b> {{ selectedUser.certificado }} <br>
                  <b>DN: </b> {{ selectedUser.certificado_dn }} <br>
                  <b>ISSUER DN:</b> {{ selectedUser.certificado_issuer_dn }} <br>
                  <b>Válido:</b> {{ `De ${selectedUser.certificado_not_before} até ${selectedUser.certificado_not_after}` }} <br>
                </v-flex>
                <v-flex v-if="userLogado !== selectedUser.id">
                  Esse certificado não pertence a você. Para ver os dados desse certificado, faça login com esse usuário.
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
  name: "Usuarios",
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
        text: 'Nome',
        align: 'start',
        sortable: false,
        value: 'name',
      },
      {text: 'CPF', value: 'cpf'},
      {text: 'E-mail', value: 'email'},
      {text: 'Data de nascimento', value: 'data_nascimento'},
      {Text: '', value: 'btn'}
    ]
  }),
  computed: {
    userLogado: function() {
      return this.$session.get('id')
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

    }
  }
}

</script>
