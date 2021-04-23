<template>
	<transition name="bounce">
		<div>
			<v-card>
				<v-card-title>Users
					<v-spacer></v-spacer>
					<v-text-field label="Search" v-model="filterBy" prepend-icon="search" :disabled="users.length < 1"></v-text-field>
				</v-card-title>
				<v-card-text>
					<v-data-table :items="users" :headers="headers" :search="filterBy" no-data-text="No user directly attached to this permission">
						<template v-slot:[`item.name`]="{ item }">
							<inertia-link :href="'/admin/users/' + item.id">{{ item.name }}</inertia-link>
						</template>
						<template v-slot:[`item.revoke`]="{ item }">
							<v-btn class="red--text" text @click="detachpermission(item)" :title="'Revoke the user\'s access to '+permission.name+'. !! User may still have roles that have this permission. !!'">
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
		name: 'permission-users',
		props: ['permission'],
		data() {
			return {
				revoking: '',
				filterBy: '',
				headers: [
					{
						text: 'Name', value: 'name'
					},
					{
						text: 'Email', value: 'email'
					},
					{
						text: 'Detach', value: 'revoke'
					}
				]
			}
		},
		methods: {
			detachpermission(user) {
				swal({
					title: user.email,
					text: 'Revoke the current permission <strong>('+this.permission.name+')</strong> from this user? Note that the user may still have access to this permission if they are assigned a role with <strong>('+this.permission.name+')</strong>',
					type: 'warning',
					html: true,
					showCancelButton: true,
					confirmButtonColor: 'red',
					confirmButtonText:'Ok',
				},
				function(){
					this.$inertia.post('/admin/permissions/' + this.permission.id + '/users/' + user.id + '/detach')
				}.bind(this))
			}
		},
		computed: {
			users () {
				return this.permission.users
			}
		},
	}
</script>

<style>
	/**/
</style>
