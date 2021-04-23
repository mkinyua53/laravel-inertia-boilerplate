<template>
	<transition name="bounce">
		<v-card>
			<v-card-title @click="shown = !shown" class="py-0" role="button">
				Attach User
				<v-spacer></v-spacer>
				<v-icon title="Hide/Show action panel">{{ !shown ? 'mdi-arrow-down-bold' : 'mdi-arrow-up-bold' }}</v-icon>
			</v-card-title>
			<v-card-text v-if="shown">
				<form @submit.prevent="attachUser(user)">
					<v-select label="Select User" v-model="user.user_id" required="required" :error-messages="_error('user_id', 'attachRoleUser')" item-text="name" item-value="id" :items="users" autocomplete>
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
										<span>{{ item.email }}</span>
									</v-list-item-subtitle>
								</v-list-item-content>
							</v-list-item>
						</template>
					</v-select>
					<v-btn small class="primary" :disabled="!user || !user.user_id" type="submit">Submit<span v-if="attaching">ting...</span></v-btn>
				</form>
			</v-card-text>
		</v-card>
	</transition>
</template>

<script>
	import { mapGetters } from 'vuex'
	export default {
		name: 'AttachPermission',
		props: ['permission', 'users'],
		data() {
			return {
				shown: false,
				attaching: false,
				user: {
					user_id: ''
				}
			}
		},
		methods: {
			attachUser(user) {
				swal({
					title: this.permission.name,
					text: 'Attach selected user to this permission?',
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: 'red',
					confirmButtonText:'Attach',
				},
				function(){
					this.$inertia.post('/admin/permissions/' + this.permission.id + '/users/' + this.user.user_id + '/attach', {
						preserveScroll: true
					})
				}.bind(this))
			}
		},
	}
</script>

<style>
	/**/
</style>
