<template>
  <v-layout>
    <v-dialog v-on="$listeners" :scrollable="true" content-class="app-content" v-model="visible" persistent width="500">
      <v-card>
        <v-card-title class="headline">
          <slot name="title">{{options.title}}</slot>
        </v-card-title>
        <v-card-text id="content">
          <slot>{{options.text}}</slot>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <div ref="alert_actions" class="alert_actions">
            <slot name="buttons">
              <v-btn class="btn-default"
                     v-bind:key="i"
                     v-for="(btn, text, i) in options.buttons"
                     :autofocus="btn.focus"
                     :class="btn.focus ? 'focus' : ''"
                     :color="btn.color || (i == Object.keys(options.buttons).length - 1 ? 'primary' : 'secondary')"
                     @click="handle(btn)">{{text}}
              </v-btn>
            </slot>
          </div>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>
import Vue from 'vue'
import { isObject } from '../plugins/helpers'

const alertTemplate = { title: 'Atenção!', text: '', buttons: {} }

export default {
  data: () => ({
    visible: false,
    options: alertTemplate
  }),
  methods: {
    show (options) {
      if (!isObject(options)) {
        options = Object.assign({}, alertTemplate, {
          text: options, buttons: { 'OK': { focus: true } }
        })
      }

      if (options.buttons) {
        Object.keys(options.buttons).forEach(key => {
          let btn = options.buttons[key]

          if (!isObject(btn)) {
            btn = options.buttons[key] = {
              handler: btn
            }
          }

          if (!btn.handler) {
            btn.handler = () => true
          }
        })
      }

      this.options = Object.assign({}, alertTemplate, options)
      this.visible = true

      Vue.nextTick(() => {
        const el = this.$refs.alert_actions.querySelector('.focus')
        if (el) {
          el.focus()
        }
      })
    },
    dismiss () {
      this.visible = false
    },
    handle (h) {
      if (h && h.handler(this) !== false) {
        this.dismiss()
      }
    }
  }
}
</script>

<style>
.alert_actions .v-btn {
  width: auto !important;
  min-width: 120px !important;
}
</style>

<style scoped>
#content {
  max-height: 300px;
  white-space: pre-wrap;
}

.v-card {
  padding: 5px;
}

.v-card__title {
  padding-bottom: 0;
}
</style>
