<template>
  <v-container fill-height>
    <v-row justify="center" align="center">
      <v-col cols="12" sm="4">
        <v-icon large class="mt-5 mb-5" style="font-size: 6rem; width: 100%;">mdi-home-outline</v-icon>
        <h1 class="dark--text">Olá, {{ nome }}!</h1>
        <p class="dark--text">Utilize o menu lateral para navegar no sistema</p>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
  export default {
    name: "Bem-vindo",
    computed: {
      nome: function () {
        return this.$session.get('name')
      }
    }
  }
</script>
