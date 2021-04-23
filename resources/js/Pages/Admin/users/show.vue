<template>
	<transition name="slide-fade">
		<v-container fluid>
			<v-layout wrap v-if="user.id">
				<v-flex xs12>
					<h3 class="text-center">User - {{ user.name }}</h3>
				</v-flex>
				<v-flex md6 sm6 xs12 class="pa-1">
					<!--  -->
				</v-flex>
				<v-flex md6 sm6 xs12 class="pa-1">
					<v-card>
						<v-card-title>Details</v-card-title>
						<v-card-text>
							<ul class="list-group list-unstyled">
								<li>
									<dl class="dl-horizontal">
										<dt>Email</dt><dd>{{ user.email }}</dd>
										<dt>Name</dt><dd>{{ user.name }}</dd>
										<dt>Gender</dt><dd>{{user.gender}}</dd>
										<p class="divider"></p>
										<dt>Last Login</dt><dd>{{user.last_login}}</dd>
										<dt>Last Address</dt><dd>{{user.last_login_ip}}</dd>
										<!-- <dt>Type</dt>
										<dd>{{ user.type }}</dd>
										<dd v-if="user.memberable">{{ user.memberable.name }}</dd>
										<dd v-if="user.memberable">
											<v-btn small @click="removeMember(user)" class="pa-0 btn-danger">Remove</v-btn>
										</dd> -->
									</dl>
								</li>
								<li class="hidden">
									<p class="pull-right">
										<v-btn small class="btn-danger pa-0" @click="resetPass = !resetPass">Change Password</v-btn>
									</p>
								</li>
							</ul>
						</v-card-text>
					</v-card>
				</v-flex>
				<v-flex md6 sm6 xs12 class="pa-1">
					<permissions :user="user"></permissions>
				</v-flex>
				<v-flex md6 sm6 xs12 class="pa-1">
					<user-roles :user="user"></user-roles>
				</v-flex>
				<v-flex md6 sm6 xs12 class="pa-1">
					<v-card>
						<v-card-title>Attach Role</v-card-title>
						<v-card-text>
							<attach-role :user="user" :roles="roles"></attach-role>
						</v-card-text>
					</v-card>
				</v-flex>
			</v-layout>
			<v-dialog v-model="resetPass">
        <v-card>
          <v-card-title class="teal white--text">
            <v-btn class="pa-0 mt-0" icon text @click="resetPass = false" title="Close">
              <v-icon>fa-arrow-left</v-icon>
            </v-btn>
            <h4 class="text-center">Change Password</h4>
          </v-card-title>
          <v-card-text>
            <form @submit.prevent="resetPassword(password)">
							<v-text-field name="Password" label="Password" hint="The password must be atleast 8 characters long and contain atleast one lowercase letter, one uppercase letter and one number" placeholder="aB3?5Fg!" v-model="password.password" min="8" :append-icon="e1 ? 'visibility' : 'visibility_off'" :append-icon-cb="() => (e1 = !e1)" :type="e1 ? 'password' : 'text'" required  pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" maxlength="60" data-vv-name="Password" :rules="[rules.required, rules.password]" :error-messages="_error('password')"></v-text-field>
							<v-text-field name="ConfirmPassword" label="Confirm Password" hint="The password must be atleast 8 characters long and contain atleast one lowercase letter, one uppercase letter and one number" placeholder="aB3?5Fg!" v-model="password.password_confirmation" min="8" :append-icon="e1 ? 'visibility' : 'visibility_off'" :append-icon-cb="() => (e1 = !e1)" :type="e1 ? 'password' : 'text'" required  pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" maxlength="60" data-vv-name="ConfirmPassword" :rules="[rules.required, rules.password, rules.confirmed]"></v-text-field>
							<v-btn type="submit" class="btn-primary pa-0" :disabled="true">Change Password</v-btn>
						</form>
          </v-card-text>
          <v-card-actions>
            <v-btn class="pa-0" text @click="resetPass = false" title="Close">Close</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
		</v-container>
	</transition>
</template>

<script>
	import layout from '@/Layouts/Admin'

	export default {
		name: 'UserDetailsPage',
		layout,
		components: {
			userRoles: () => import('./roles.vue'),
			permissions: () => import('./permissions.vue'),
			attachRole: () => import('./attach-role.vue')
		},
		props: [
			'user', 'roles'
		],
		metaInfo () {
			return {
				title: 'User | ' + this.user.name
			}
		},
		data() {
			return {
				loading: true,
				resetPass: false,
				password: {
					password: '',
					password_confirmation: ''
				},
				rules: {
          required: (value) => !!value || 'This field is required.',
          email: (value) => {
            const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
            return pattern.test(value) || 'Please enter a valid e-mail.'
          },
          password: (value) => {
          	const pattern2 = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/
          	return pattern2.test(value) || 'Password pattern does not match the requested format'
          },
          confirmed: (v1) => {
            var v2 = this.password.password
            return v1 === v2 || "The Value don't match."
          }
				},
				e1: true,
			}
		},
		methods: {
			resetPassword(password) {
				swal({
					title: 'Change Password for user?',
					text: '',
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: 'red',
					confirmButtonText:'Ok',
					closeOnConfirm: false,
					showLoaderOnConfirm: true
				},
				function(){
					this.$http.put('users/'+this.user.id+'/reset-password-admin', password)
					.then(response => {
						swal({title:'Password Reset Successful',type:'success', text: 'Inform the user of the change and have them change the password at their profile page'})
					})
					.catch(err => {
						swal('Error '+err.status+': '+err.statusText,'Failed to Reset Password','error')
					})
				}.bind(this))
			},
			removeMember (user) {
				swal({
					title: user.name,
					text: 'Remove user\'s '+ user.type + ' account?' ,
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: 'red',
					confirmButtonText:'Remove',
					closeOnConfirm: false,
					showLoaderOnConfirm: true,
				},
				function () {
					this.$http.post('users/' + user.id + '/member')
					.then(res => {
						this.$store.dispatch('users/users')
						.then(res => {
							this.fetchData()
						})
						swal('Account type removed')
					})
					.catch(err => {
						swal('Error '+err.status+': '+err.statusText, 'Failed to Remove', 'error')
					})
				}.bind(this))
			}
		},
		created() {
			if (this.user && this.user.email && this.user.email.includes('mwatha.kinyua@hotmail.com')) {
				this.$inertia.visit('/admin/users')
			}
		},
		computed: {
			//
		},
		watch: {
			'user.email': function (value) {
				if (value.includes('mwatha.kinyua@hotmail.com')) {
					this.$inertia.visit('/admin/users')
				}
			},
			'e1': function () {
				if (!this.e1) {
					setTimeout(function() {
						this.e1 = true
					}.bind(this), 5000);
				}
			}
		}
	}
</script>

<style>
	/**/
</style>
