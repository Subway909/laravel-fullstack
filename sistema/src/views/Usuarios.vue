<template>
  <v-container>
    <v-layout column class="card table">
        <v-flex>Listagem de usuários</v-flex>
    </v-layout>
  </v-container>
</template>

<script>

  export default {
    name: "Usuarios",
    data: () => ({
      usuarios: [
        {
          id: "",
          nome: "",
          telefone: "",
          email: "",
          arquivo: "",
        }
      ]
    }),
    mounted() {
      console.log('hello')

      setInterval(() => { this.cors() }, 3000)

      console.log(this.$session.get('name'))

    },
    methods: {
      cors() {
        this.$http.get('/teste').then((r) => {
          console.log(r)
        }).catch(err => {
          alert("Verifique usuário e senha")
          console.error(err)
        })
      }
    }
  }

</script>
