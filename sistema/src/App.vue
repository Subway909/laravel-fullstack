<template>
  <v-app id="inspire">
    <v-navigation-drawer
      v-model="drawer"
      v-if="!isLogin"
      app>
      <v-list dense>
        <v-list-item>
          <v-list-item-action>
            <v-icon>mdi-account-multiple-plus</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>
              <router-link to="/novo">Cadastrar usuário</router-link>
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item>
          <v-list-item-action>
            <v-icon>mdi-account-box</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>
              <router-link to="/usuarios">Listagem de usuários</router-link>
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>

      </v-list>
    </v-navigation-drawer>

    <v-app-bar
      app
      color="green"
      dark
      v-if="!isLogin">
      <v-app-bar-nav-icon
        @click.stop="drawer = !drawer"
        v-if="!isLogin"
      ></v-app-bar-nav-icon>
      <v-toolbar-title>Sistema</v-toolbar-title>

      <v-spacer></v-spacer>

      <v-app-bar-nav-icon >
        <v-icon
          dark
          @click="logout"
        >
          mdi-logout
        </v-icon>
      </v-app-bar-nav-icon>
    </v-app-bar>

    <v-main :class="isLogin ? 'bg' : ''">
      <v-container
        fluid>
        <v-row>
          <v-col>
            <router-view/>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
    <v-footer
      color="green"
      app
      v-if="!isLogin">
      <span class="white--text">&copy; {{ new Date().getFullYear() }}</span>
    </v-footer>
  </v-app>
</template>

<script>

export default {
  props: {
    source: String,
  },

  data: () => ({
    drawer: false,
  }),
  computed: {
    login: function () {
      return (this.$session.get('login'))
    },
    currentRouteName() {
      return this.$route.name;
    },
    isLogin() {
      return this.currentRouteName === 'login'
    }
  },
  mounted() {
    console.log('loaded app')
  },
  methods: {
    logout() {
      this.$confirm("Deseja efetuar o logout?", "", "",
        {
                  confirmButtonText: 'Sim',
                  cancelButtonText: 'Não'
        }).then(() => {
        this.$router.replace("/logout")
      });
    }
  }
}
</script>


<style scoped>
.bg {
  background-image: url("assets/bg1.jpg");
  background-size: cover;
}
</style>

<style>
  .swal2-content, .swal2-actions {
    font-family: Arial, Helvetica, sans-serif;
  }
</style>
