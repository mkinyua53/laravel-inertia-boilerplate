<template>
	<transition name="slide-fade">
		<v-container fluid>
			<v-layout wrap class="justify-center">
				<v-flex md8 sm11 xs12 class="pa-1">
					<h3 class="text-center">Roles</h3>
					<v-card>
						<v-card-title>
							<v-btn small text @click="addNew = !addNew">New</v-btn>
							<v-spacer></v-spacer>
							<v-text-field label="Search" v-model="filterBy" prepend-icon="search" :disabled="roles.length < 1" :append-icon="filterBy.length > 0 ? 'close' : ''" :append-icon-cb="() => (filterBy = '')"></v-text-field>
						</v-card-title>
						<v-card-text>
							<v-data-table :items="roles" :headers="headers" :search="filterBy">
								<template v-slot:[`item.name`]="{ item }">
									<inertia-link :href="'/admin/roles/' + item.id">{{ item.name }}</inertia-link>
								</template>
								<template v-slot:[`item.trash`]="{ item }">
									<v-btn small icon title="Delete role" @click="deleteRole(item)" v-if="!item.default">
										<v-icon>mdi-delete</v-icon>
									</v-btn>
									<v-icon v-else>mdi-delete-off</v-icon>
								</template>
							</v-data-table>
						</v-card-text>
					</v-card>
				</v-flex>
			</v-layout>
			<modal-dialog :open="addNew" @close="addNew = false">
				<template slot="title">Add New Role</template>
				<v-card>
					<v-card-text>
						<p>You can add new roles to organize your permissions.</p>
						<p>After the role is created, add the permissions you wish to group together, then attach users to the role.</p>
						<p>Roles you add here can be editted or deleted as compared to the default ones</p>
						<p><b>Please Note: </b>If the Authorization feature is reset using the ResetAuth button, all the user added roles will be deleted.</p><br>
						<form @submit.prevent="saveRole">
							<v-text-field label="Name of Role" v-model="newrole.name" placeholder="Unique Name" required="required" :error-messages="_error('name', 'createRole')"></v-text-field>
							<v-textarea label="Description" :rows="1" auto-grow v-model="newrole.label" required="required" placeholder="Add some description" :error-messages="_error('description', 'createRole')"></v-textarea>
							<v-btn type="submit" small class="primary" :loading="newrole.processing">Save</v-btn>
						</form>
					</v-card-text>
				</v-card>
			</modal-dialog>
		</v-container>
	</transition>
</template>

<script>
	import layout from '@/Layouts/Admin'

	export default {
		name: 'roles',
		props: [
			'roles'
		],
		layout,
		data() {
			return {
				filterBy: '',
				headers: [
					{ text: 'Name', value: 'name' },
					{ text: 'Description', value: 'label' },
					{ text: 'Permissions', value: 'permissions_count' },
					{ text: 'Users', value: 'users_count' },
					{ text: 'Delete', value: 'trash' }
				],
				addNew: false,
				newrole: this.$inertia.form({
					name: '',
					label: ''
				}, {
					bag: 'createRole'
				})
			}
		},
		metaInfo () {
			return {
				title: 'Roles'
			}
		},
		methods: {
			saveRole () {
				this.newrole.post('/admin/roles')
			},
			deleteRole (role) {
				swal({
					title: role.name,
					text: 'Delete this role? <br> All users attached to this role will lose their permissions.',
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: 'red',
					confirmButtonText:'Delete',
				},
				function () {
					this.$inertia.delete('/admin/roles/' + role.id)
				}.bind(this))
			}
		}
	}
</script>

<style>
	/**/
</style>
