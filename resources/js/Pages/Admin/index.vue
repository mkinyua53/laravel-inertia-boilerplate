<template>
  <transition name="slide-fade">
    <v-container fluid>
      <v-layout wrap class="align-center">
        <v-flex xs12>
          <h3 class="text-center">Admin</h3>
        </v-flex>
        <v-flex xs12 md8 sm8>
          <v-card>
            <v-card-text>
              <h1>Introduction</h1>
              <p>All actions provided by this application requires special permissions to be held by the user.</p>
              <p class="hidden">As an example, by the fact that you are reading this, then it means you have either the role of <span class="bg-info">Developer</span> or <span class="bg-info">SuperAdmnin</span></p><br>
              <p class="hidden">Every category of resources have 4 basic permissions:</p>
              <p class="text-warning hidden">Permission to </p>
              <ul class="list-group-item-danger fa-ul hidden">
                <li><i class="fa fa-tint"></i> view the category</li>
                <li><i class="fa fa-tint"></i> Edit a resource within that category*</li>
                <li><i class="fa fa-tint"></i> Create or add a new resource in that category</li>
                <li><i class="fa fa-tint"></i> Delete a resource</li>
              </ul>
              <p>You should add the permissions to your users as you see fit.</p>

              <h2>Roles</h2>
              <p>Roles normally contains a group of permissions</p>
              <p>Granting roles to users should therefore be done with caution as the user will automatically gain all the permissions held by that roles</p>
              <p>Visit each roles's page to see permissions held by the role.</p>
              <p class="red--text">You can also create your own roles to group permissions</p>
              <p>
                <v-btn title="Initialize Authorization priviledges" @click="initAuth" small color="orange">Initialize</v-btn>
                <v-btn title="Reset Authorization priviledges" @click="resetAuth" small color="red">Reset Auth</v-btn>
              </p>
            </v-card-text>
          </v-card>
				</v-flex>
        <v-flex xs12 md4 sm4>
          <v-card flat class="ma-1">
            <v-card-text class="text-center">
              <v-btn large @click="goTo('/admin/users')">
                <v-icon left>mdi-account-group</v-icon>Users
              </v-btn>
            </v-card-text>
          </v-card>
          <v-card flat class="ma-1">
            <v-card-text class="text-center">
              <v-btn large @click="goTo('/admin/roles')">
                <v-icon left>mdi-police-badge</v-icon>Roles
              </v-btn>
            </v-card-text>
          </v-card>
          <v-card flat class="ma-1">
            <v-card-text class="text-center">
              <v-btn large @click="goTo('/admin/permissions')">
                <v-icon left>mdi-shield-lock-outline</v-icon>Permissions
              </v-btn>
            </v-card-text>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
  </transition>
</template>

<script>
  import layout from '@/Layouts/Admin'

  export default {
    name: 'AdminIndex',
    layout,
    metaInfo () {
      return {
        title: 'Admin',
        titleTemplate: '%s | ' + this.$title,
      }
    },
    data () {
      return {
        data: '',
      }
    },
    methods: {
      somemethod () {
         //
      },
      initAuth () {
        this.$inertia.visit('/admin/install')
      },
      resetAuth () {
        this.$inertia.visit('/admin/reset')
      }
    },
    created () {
      this.somemethod()
    },
    computed: {
      //
    },
    watch: {
      '$route': 'somemethod',
    }
  }
</script>
<style>
   /**/
</style>
