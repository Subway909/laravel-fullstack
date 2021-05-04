<template>
  <div class="text-center">
    <v-overlay v-show="overlay">
      <v-progress-circular
        indeterminate
        size="64"
      ></v-progress-circular>
    </v-overlay>
  </div>
</template>

<script>
export default {
  data: () => ({
    overlay: false,
  }),
  methods: {
    start () {
      this.overlay = true
    },
    done () {
      this.overlay = false
    }
  }
}
</script>
