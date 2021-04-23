<template>
	<transition name="slide-fade">
		<v-container fluid>
			<v-layout wrap class="justify-center">
				<v-flex xs12 md11 sm12 class="pa-1">
					<v-card flat>
						<v-card-title>
							Users
							<v-spacer></v-spacer>
							<v-text-field label="Search" v-model="filterBy" prepend-icon="search" :disabled="users.length < 1" :append-icon="filterBy.length > 0 ? 'close' : ''" :append-icon-cb="() => (filterBy = '')" hint="Search by Email"></v-text-field>
						</v-card-title>
						<v-card-text>
							<v-data-table :items="users" :headers="headers" :search="filterBy" dense>
								<template v-slot:[`item.name`]="{ item }">
									<td>
										{{ item.title ? item.title + ' ' : '' }}<inertia-link :href="'/admin/users/' + item.id">{{ item.name }}</inertia-link>
									</td>
								</template>
								<template v-slot:[`item.active`]="{ item }">
									<td v-if="item.id != $user.id">
										<v-btn small text title="Activate the user" @click="activateUser(item)" v-if="!item.active">
											Activate
										</v-btn>
										<v-btn small text title="Deactivate user" @click="deactivateUser(item)" v-else>
											Deactivate
										</v-btn>
									</td>
								</template>
								<template v-slot:[`item.email_verified_at`]="{ item }">
									<v-btn small text @click="verify(item)" v-if="!item.email_verified_at">
										Verify
										<v-icon right>mdi-account-convert-outline</v-icon>
									</v-btn>
									<v-btn small text @click="unverify(item)" v-else :title="item.email_verified_at">
										{{ moment(item.email_verified_at).fromNow() }}
										<v-icon right>mdi-account-convert-outline mdi-flip-h</v-icon>
									</v-btn>
								</template>
								<template v-slot:[`item.created_at`]="{ item }">
									{{ moment(item.created_at).format('d MMM yyyy') }}
								</template>
								<template v-slot:[`item.loginas`]="{ item }">
									<mk-confirms-password :open="confirmingPassword" @close="confirmingPassword = false" content="Please confirm your password." @confirmed="loginAs(item)">
										<v-btn text small class="" title="Login as this user" icon v-if="!item.is_admin">
											<v-icon>mdi-login</v-icon>
										</v-btn>
									</mk-confirms-password>
								</template>
							</v-data-table>
						</v-card-text>
					</v-card>
				</v-flex>
			</v-layout>
		</v-container>
	</transition>
</template>

<script>
	import layout from '@/Layouts/Admin.vue'
    import MkConfirmsPassword from '@/Components/components/ConfirmsPassword.vue'

	export default {
		name: 'Users',
		layout,
		props: [
			'users',
		],
		components: {
			MkConfirmsPassword,
		},
		data() {
			return {
				filterBy: '',
				selected: [],
				sendMail: false,
				mail: {
					subject: '',
					message: ''
				},
				headers: [
					{
						text: 'Name', value: 'name'
					},
					{
						text: 'Email', value: 'email'
					},
					{
						text: 'Email Verified', value: 'email_verified_at'
					},
					{
						text: 'Active', value: 'active'
					},
					{
						text: 'Date Joined', value: 'created_at'
					},
					{
						text: '', value: 'loginas'
					}
				],
				confirmingPassword: false,
			}
		},
		metaInfo () {
			return {
				title: 'Users'
			}
		},
		methods: {
			deactivateUser(user) {
				swal({
					title: 'Deactivate User',
					text: 'Deactivate this user - <strong>'+user.email+'</strong>?<br>The user will not be able to login',
					type: 'warning',
					html: true,
					showCancelButton: true,
					confirmButtonColor: 'red',
					confirmButtonText:'Deactivate',
				},
				function(){
					this.$inertia.put('/admin/users/'+user.id+'/deactivate')
				}.bind(this))
			},
			activateUser(user) {
				swal({
					title: 'User Activation',
					text: 'Activate this user - <strong>'+user.email+'</strong>?<br>User will now be able to login',
					type: 'warning',
					html: true,
					showCancelButton: true,
					confirmButtonColor: 'green',
					confirmButtonText:'Activate'
				},
				function(){
					this.$inertia.put('/admin/users/'+user.id+'/activate')
				}.bind(this))
			},
			deleteUser(user) {
				swal({
					title: 'Delete User?',
					text: user.name + ' - ' + user.email,
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: 'red',
					confirmButtonText:'Delete',
					closeOnConfirm: false,
					showLoaderOnConfirm: true,
				},
				function () {
					this.$http.delete('users/' + user.id)
					.then(res => {
						this.$store.dispatch('users/users')
						swal({ title: 'User Deleted', type: 'success', timer: 2500 })
					})
					.catch(err => {
						swal('Error '+err.status+': '+err.statusText,'Failed to delete','error')
						this.$store.dispatch('users/users')
					})
				}.bind(this))
			},
			addId (id) {
				var index = this.selected.indexOf(id)
				if (index < 0) {
					this.selected.push(id)
				}
			},
			removeId (id) {
				var index = this.selected.indexOf(id)
				if (index > -1) {
					this.selected.splice(index, 1)
				}
			},
			addAll() {
				for (let i = 0; i < this.filtered.length; i++) {
					const element = this.filtered[i];
					var index = this.selected.indexOf(element.id)
					if (index < 0) {
						this.selected.push(element.id)
					}
				}
			},
			sendAllMail () {
				swal({
					title: 'Send email to the selected users?',
					text: 'The mail will be queued',
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: 'red',
					confirmButtonText:'Send',
					closeOnConfirm: false,
					showLoaderOnConfirm: true,
				},
				function () {
					this.$http.post('users/mail', { ids: this.selected, subject: this.mail.subject, message: this.mail.message })
					.then(res => {
						swal('The emails has been added to the queue')
						// this.selected = []
						this.sendMail = false
					})
					.catch(err => {
						swal('Error '+err.status+': '+err.statusText,'Failed to send emails','error')
					})
				}.bind(this))
			},
			verify (user) {
				swal({
					title: user.email,
					text: 'Verify this account on behalf of the user',
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: 'red',
					confirmButtonText:'Verify',
				},
				function () {
					this.$inertia.put('/admin/users/' + user.id + '/verify', null, {
						preserveScroll: true,
					})
				}.bind(this))
			},
			unverify (user) {
				swal({
					title: user.email,
					text: 'Force the user to verify this email?',
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: 'red',
					confirmButtonText:'Continue',
				},
				function () {
					this.$inertia.put('/admin/users/' + user.id + '/unverify', null, {
						preserveScroll: true,
					})
				}.bind(this))
			},
			loginAs (user) {
				swal({
					title: 'Login as ' + user.email,
					text: 'You will also be logged out of your account',
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: 'red',
					confirmButtonText:'Attempt Login',
				},
				function () {
					this.$inertia.post('/admin/users/login', { id: user.id }, {
						preserveScroll: true,
					})
				}.bind(this))
			}
		},
		created() {
			//
		},
		computed: {
			//
		},
		watch: {
			//
		}
	}
</script>

<style>
	/**/
</style>
