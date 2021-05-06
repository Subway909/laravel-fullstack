<template>
  <v-container>
    <v-layout column class="card table">
      <h3 class="mb-5">Listagem de usuários</h3>

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
            Detalhes
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
          <v-flex><h4>Detalhes do usuário</h4></v-flex>

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
                <h4>Dados cadastrais</h4>

                <b>Nome:</b> {{ selectedUser.name }} <br>
                <b>CPF:</b> {{ selectedUser.cpf }} <br>
                <b>E-mail:</b> {{ selectedUser.email }} <br>
                <b>Data de nascimento:</b> {{ selectedUser.data_nascimento | moment }} <br>
              </v-col>
              <v-col cols="4">
                <h4>Telefone</h4>
                <b>Telefone Fixo:</b> {{ selectedUserTelefone.telefone_fixo}} <br>
                <b>Telefone Celular:</b> {{ selectedUserTelefone.telefone_celular }} <br>
              </v-col>
              <v-col cols="4">
                <h4>Endereço</h4>

                <b>Logradouro:</b> {{ selectedUserEndereco.logradouro}} <br>
                <b>Número:</b> {{ selectedUserEndereco.numero}} <br>
                <b>Bairro:</b> {{ selectedUserEndereco.bairro}} <br>
                <b>Complemento:</b> {{ selectedUserEndereco.complemento}} <br>
                <b>Cep:</b> {{ selectedUserEndereco.cep}} <br>
                <b>Cidade:</b> {{ selectedUserEndereco.cidade}} <br>
              </v-col>
              <v-col cols="12">
                <h4>Dados do certificado</h4>
                <v-flex v-if="podeVerCertificado">
                  <b>Subject DN: </b> {{ selectedUser.certificado_dn }} <br>
                  <b>Issuer DN:</b> {{ selectedUser.certificado_issuer_dn }} <br>
                  <b>Válido:</b> {{ `Não antes de ${selectedUser.certificado_not_before}` }} <br> {{ `Não depois de ${selectedUser.certificado_not_after}` }}
                </v-flex>
                <v-flex v-if="!podeVerCertificado && temCertificado">
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
        value: 'name',
      },
      {text: 'CPF', value: 'cpf'},
      {text: 'E-mail', value: 'email'},
      {text: 'Data de nascimento', value: 'data_nascimento'},
      {Text: '', value: 'detalhes'},
      {Text: 'Editar', value: 'editar'},
      {Text: 'Excluir', value: 'excluir'}
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

      this.$router.push({ name: 'Novo', params })
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
      if (item.id === this.userLogado) {
        return "green dark";
      }
    }
  }
}

</script>
