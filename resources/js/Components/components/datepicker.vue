<template>
  <v-menu ref="menu" v-model="menu" :close-on-content-click="false" :return-value.sync="date" transition="scale-transition" offset-y min-width="auto">
    <template v-slot:activator="{ on, attrs }">
      <v-text-field v-model="date" :label="label" prepend-icon="mdi-calendar" readonly v-bind="attrs" v-on="on" :error-messages="errorMessages" :placeholder="placeholder" :required="required" :type="type"></v-text-field>
    </template>
    <v-date-picker v-model="date" no-title scrollable :type="type">
      <v-spacer></v-spacer>
      <v-btn text color="primary" @click="menu = false">Cancel</v-btn>
      <v-btn text color="primary" @click="$refs.menu.save(date); $emit('input', date)">OK</v-btn>
    </v-date-picker>
  </v-menu>
</template>

<script>
  export default {
    name: 'DatePicker',
    props: {
      label: {
        type: String,
        default: 'Date'
      },
      errorMessages: {
        default: ''
      },
      placeholder: {
        type: String
      },
      hint: {
        type: String,
      },
      required: {
        default: false,
      },
      type: {
        type: String,
        default: 'date'
      }
    },
    data () {
      return {
        // date: new Date().toISOString().substr(0, 10),
        menu: false,
        date: ''
      }
    }
  }
</script>
