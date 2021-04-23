<template>
  <transition name="slide-fade">
    <v-container fluid>
      <v-layout wrap>
        <v-flex xs12>
          <form @submit.prevent="attachRole(role)">
            <v-select label="Select User" v-model="role" required="required" :error-messages="_error('user_id', 'attachRoleUser')" item-text="name" item-value="id" :items="roles" autocomplete>
              <template v-slot:item="{ active, item, attrs, on }">
                <v-list-item v-on="on" v-bind="attrs" #default="{ active }">
                  <v-list-item-action>
                  <v-checkbox :input-value="active"></v-checkbox>
                  </v-list-item-action>
                  <v-list-item-content>
                    <v-list-item-title>
                      <span>{{ item.name }}</span>
                    </v-list-item-title>
                    <v-list-item-subtitle>
                      <span>{{ item.label }}</span>
                    </v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>
              </template>
            </v-select>
            <v-btn type="submit" class="primary" small>Submit</v-btn>
          </form>
        </v-flex>
      </v-layout>
    </v-container>
  </transition>
</template>
<script>
  export default {
    name: 'AttachRoleToUser',
    props: ['user', 'roles'],
    data () {
      return {
        role: '',
        attaching: false,
      }
    },
    methods: {
      attachRole (role) {
        swal({
          title: 'Attach Role to user',
          text: 'The user will inherit all permissions attached to this role',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: 'red',
          confirmButtonText:'Attach',
        },
        function () {
          this.attaching = true
          this.$inertia.post('/admin/roles/' + role + '/users/' + this.user.id + '/attach', {
            preserveScroll: true,
          })
        }.bind(this))
      }
    },
  }
</script>
<style>
   /**/
</style>
