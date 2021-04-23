<template>
	<transition name="bounce">
		<v-card>
			<v-card-title>Permissions
				<v-spacer></v-spacer>
				<v-text-field label="Search" v-model="filterBy" prepend-icon="search" :disabled="permissions.length < 1" :append-icon="filterBy.length > 0 ? 'close' : ''" :append-icon-cb="() => (filterBy = '')"></v-text-field>
			</v-card-title>
			<v-card-text>
				<v-data-table :items="permissions" :headers="headers" :search="filterBy" no-data-text="No permission attached to this role.">
					<template v-slot:[`header.detach`]="{ header }">
						<th>{{ role.default ? '' : 'Detach' }}</th>
					</template>
					<template v-slot:[`item.name`]="{ item }">
						<td><inertia-link :href="'/admin/permissions/' + item.id">{{ item.name }}</inertia-link></td>
					</template>
					<template v-slot:[`item.detach`]="{ item }">
						<v-btn small text @click="detachPermission(item)" v-if="!role.default">Detach</v-btn>
					</template>
				</v-data-table>
			</v-card-text>
		</v-card>
	</transition>
</template>

<script>
	export default {
		name: 'role-permissions',
		props: ['role'],
		data() {
			return {
				filterBy: '',
				headers: [
					{
						text: 'Name', value: 'name'
					},
					{
						text: 'Description', value: 'label'
					},
					{
						text: 'Date', value: 'pivot.created_at'
					},
					{
						text: 'Detach', value: 'detach',
					}
				]
			}
		},
		watch: {
			'n': function() {
			  if (this.n < 1) {
			    this.n = 1
			  }
			  if (this.n > this.permissions.length) {
			    this.n = this.permissions.length
			  }
			}
		},
		computed: {
			permissions () {
				return this.role.permissions
			}
		},
		methods: {
			detachPermission (permission) {
				swal({
					title: permission.name,
					text: 'Detach this permission from role (' + this.role.name + ')',
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: 'red',
					confirmButtonText:'Detach',
				},
				function () {
					this.$inertia.post('/admin/roles/' + this.role.id + '/permissions/' + permission.id + '/detach', {
						preserveScroll: true,
					})
				}.bind(this))
			}
		}
	}
</script>

<style>
	/**/
</style>
