<template>
	<transition name="bounce">
		<div>
			<v-card>
				<v-card-title>
					Users
					<v-spacer></v-spacer>
					<v-text-field label="Search" v-model="filterBy" prepend-icon="search" :disabled="users.length < 1"></v-text-field>
				</v-card-title>
				<v-card-text>
					<v-data-table :items="users" :headers="headers" no-data-text="No user attached to this role" :search="filterBy">
						<template v-slot:[`item.name`]="{ item }">
							<inertia-link :href="'/admin/users/' + item.id">{{ item.name }}</inertia-link>
						</template>
						<template v-slot:[`item.revoke`]="{ item }">
							<v-btn color="red" @click="detachrole(item)" small text :title="'Revoke the user\'s access to ' + role.name + '. !! User may still have roles that have this role. !!'">
								<span v-if="revoking == item.id">Revoking...</span>
								<span v-else>Revoke</span>
							</v-btn>
						</template>
					</v-data-table>
				</v-card-text>
			</v-card>
		</div>
	</transition>
</template>

<script>
	export default {
		name: 'role-users',
		props: ['role'],
		data() {
			return {
				revoking: '',
				filterBy: '',
				headers: [
					{
						text: 'Name', value: 'name',
					},
					{
						text: 'Email', value: 'email'
					},
					{
						text: 'Revoke', value: 'revoke'
					}
				]
			}
		},
		methods: {
			detachrole(user) {
				swal({
					title: user.email,
					text: 'Revoke the current role <strong>('+this.role.name+')</strong> from this user?',
					type: 'warning',
					html: true,
					showCancelButton: true,
					confirmButtonColor: 'red',
					confirmButtonText:'Ok',
					closeOnConfirm: true,
					showLoaderOnConfirm: false
				},
				function(){
					this.$inertia.post('/admin/roles/'+this.role.id+'/users/'+user.id+'/detach')
				}.bind(this))
			}
		},
		computed: {
			users () {
				return this.role.users
			}
		},
		watch: {
			//
		}
	}
</script>

<style>
	/**/
</style>
