<template>
	<transition name="bounce">
		<v-card>
			<v-card-title>Roles
				<v-spacer></v-spacer>
				<v-btn small class="primary" @click="detachAll" title="Detach all roles from the user">Detach all</v-btn>
			</v-card-title>
			<v-card-text>
				<v-data-table :items="roles" :headers="headers" no-data-text="No role attached to this user.">
					<template v-slot:[`item.name`]="{ item }">
						<inertia-link :href="'/admin/roles/' + item.id">{{ item.name }}</inertia-link>
					</template>
					<template v-slot:[`item.revoke`]="{ item }">
						<v-btn small text class="orange" @click="detachRole(item)" title="Detach Role from user" :loading="item.id === detaching">
							Detach
						</v-btn>
					</template>
				</v-data-table>
			</v-card-text>
		</v-card>
	</transition>
</template>

<script>
	export default {
		name: 'UserRoles',
		props: ['user'],
		data() {
			return {
				detaching: '',
				headers: [
					{
						text: 'Name', value: 'name'
					},
					{
						text: 'Description', value: 'label',
					},
					{
						text: 'Detach', value: 'revoke'
					}
				]
			}
		},
		methods: {
			detachRole(role) {
				swal({
					title: role.name,
					text: 'Detach this role from the user?',
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: 'red',
					confirmButtonText:'Ok',
				},
				function(){
					this.detaching = role.id
					this.$inertia.post('/admin/roles/' + role.id + '/users/' + this.user.id + '/detach', {
						preserveScroll: true,
					})
				}.bind(this))
			},
			detachAll(){
				swal({
					title: 'Detach all roles from <strong>'+this.user.email+'</strong>',
					html: true,
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: 'red',
					confirmButtonText:'Ok',
				},
				function(){
					this.$inertia.post('/admin/roles/users/' + this.user.id + '/detach', {
						preserveScroll: true
					})
				}.bind(this))
			}
		},
		computed: {
			roles () {
				return this.user.roles
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
