<template>
	<transition name="">
		<v-container fluid>
			<v-layout wrap v-if="role.id">
				<v-flex xs12>
					<h3 class="text-center">Role - {{ role.name }}</h3>
				</v-flex>
				<v-flex xs12>
					<v-card>
						<v-card-text>
							<ul class="list-group">
								<li class="list-group-item"><strong>Role's Name: </strong>{{ role.name }}</li>
								<li class="list-group-item"><strong>Description: </strong>{{ role.label }}</li>
							</ul>
						</v-card-text>
						<v-card-actions v-if="!role.default">
							<v-spacer></v-spacer>
							<v-btn small title="Edit Role details" @click="edit = !edit">Edit</v-btn>
						</v-card-actions>
					</v-card>
				</v-flex>
			</v-layout>
			<v-layout wrap>
				<v-flex md6 sm6 xs12 class="pa-1">
					<attach :role="role" :users="users"></attach>
					<users :role="role"></users>
				</v-flex>
				<v-flex md6 sm6 xs12 class="pa-1">
					<v-card v-if="!role.default" class="mb-1">
						<v-card-title @click="attachingP = !attachingP" class="py-0" role="button">
							Attach Permission
							<v-spacer></v-spacer>
							<v-icon>{{ !attachingP ? 'mdi-arrow-down-bold' : 'mdi-arrow-up-bold' }}</v-icon>
						</v-card-title>
						<v-card-text v-if="attachingP">
							<form @submit.prevent="attachPermission">
								<v-select label="Select Permission" v-model="permission.permission_id" required="required" item-text="name" item-value="id" :items="permissions" autocomplete>
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
								<v-btn small type="submit" :disabled="!permission.permission_id">Attach</v-btn>
							</form>
						</v-card-text>
					</v-card>
					<permissions :role="role"></permissions>
				</v-flex>
			</v-layout>
			<modal-dialog v-model="edit">
				<template slot="title">Edit Role</template>
				<v-card>
					<v-card-text>
						<form @submit.prevent="updateRole">
							<v-text-field label="Name of Role" v-model="form.name" placeholder="Unique Name" required="required" :error-messages="_error('name', 'updateRole')"></v-text-field>
							<v-textarea label="Description" :rows="1" auto-grow v-model="form.label" required="required" placeholder="Add some description" :error-messages="_error('description', 'updateRole')"></v-textarea>
							<v-btn type="submit" small class="primary" :loading="form.processing">Update</v-btn>
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
		name: 'Role',
		layout,
		components: {
			attach: () => import(/* webpackChunkName: "js/admin" */ './attach.vue'),
			users: () => import(/* webpackChunkName: "js/admin" */ './users.vue'),
			permissions: () => import(/* webpackChunkName: "js/admin" */ './permissions.vue')
		},
		props: [
			'role', 'users', 'permissions'
		],
		data() {
			return {
				loading: true,
				form: this.$inertia.form({
					'_method': 'PUT',
					name: this.role.name,
					label: this.role.label
				}, {
					bag: 'updateRole'
				}),
				edit: false,
				permission: {
					permission_id: ''
				},
				attachingP: false
			}
		},
		metaInfo () {
			return {
				title: 'Role | ' + this.role.name
			}
		},
		methods: {
			updateRole () {
				swal({
					title: 'Update role?',
					text: '',
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: 'red',
					confirmButtonText:'Update',
				},
				function () {
					this.form.post('/admin/roles/' + this.role.id)
				}.bind(this))
			},
			attachPermission () {
				swal({
					title: 'Attach Permission to Role',
					text: '',
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: 'red',
					confirmButtonText:'Attach',
				},
				function () {
					this.$inertia.post('/admin/roles/' + this.role.id + '/permissions/' + this.permission.permission_id + '/attach', {
						onSuccess: () => {
							//
						},
						preserveState: false,
						preserveScroll: true,
					})
				}.bind(this))
			}
		},
		created() {
			// this.fetchData()
		},
		watch: {
			'$route.params.role': 'fetchData'
		},
		computed: {
			//
		}
	}
</script>

<style>
	/**/
</style>
